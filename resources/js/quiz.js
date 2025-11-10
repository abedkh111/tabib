import { Alpine, axios } from './app.js';

// Advanced Quiz functionality
Alpine.data('advancedQuiz', (quizData) => ({
    // Quiz state
    quiz: quizData,
    currentQuestionIndex: 0,
    answers: {},
    timeRemaining: 0,
    totalTime: 0,
    timer: null,
    startTime: null,
    questionStartTime: null,
    questionTimes: {},
    
    // UI state
    showResults: false,
    showExplanation: false,
    submitted: false,
    loading: false,
    autoSubmit: false,
    
    // Results
    results: null,
    score: 0,
    correctAnswers: 0,
    wrongAnswers: 0,
    
    // Initialize quiz
    init() {
        this.totalTime = this.quiz.time_limit * 60; // Convert minutes to seconds
        this.timeRemaining = this.totalTime;
        this.startTime = Date.now();
        this.questionStartTime = Date.now();
        
        // Initialize answers object
        this.quiz.questions.forEach(question => {
            this.answers[question.id] = null;
            this.questionTimes[question.id] = 0;
        });
        
        this.startTimer();
        this.trackQuestionTime();
        
        // Auto-save answers periodically
        setInterval(() => {
            this.saveProgress();
        }, 30000); // Save every 30 seconds
        
        // Prevent page refresh/close without warning
        window.addEventListener('beforeunload', (e) => {
            if (!this.submitted) {
                e.preventDefault();
                e.returnValue = 'هل أنت متأكد من أنك تريد مغادرة الصفحة؟ ستفقد إجاباتك.';
            }
        });
    },
    
    // Timer management
    startTimer() {
        if (this.timer) clearInterval(this.timer);
        
        this.timer = setInterval(() => {
            if (this.timeRemaining > 0) {
                this.timeRemaining--;
                
                // Warning when 5 minutes remaining
                if (this.timeRemaining === 300) {
                    this.showTimeWarning('تبقى 5 دقائق فقط!');
                }
                
                // Warning when 1 minute remaining
                if (this.timeRemaining === 60) {
                    this.showTimeWarning('تبقت دقيقة واحدة فقط!');
                }
            } else {
                this.autoSubmit = true;
                this.submitQuiz();
            }
        }, 1000);
    },
    
    stopTimer() {
        if (this.timer) {
            clearInterval(this.timer);
            this.timer = null;
        }
    },
    
    // Question time tracking
    trackQuestionTime() {
        const currentQuestion = this.getCurrentQuestion();
        if (currentQuestion && this.questionStartTime) {
            const timeSpent = Math.floor((Date.now() - this.questionStartTime) / 1000);
            this.questionTimes[currentQuestion.id] += timeSpent;
        }
        this.questionStartTime = Date.now();
    },
    
    // Navigation
    getCurrentQuestion() {
        return this.quiz.questions[this.currentQuestionIndex];
    },
    
    goToQuestion(index) {
        if (index >= 0 && index < this.quiz.questions.length) {
            this.trackQuestionTime();
            this.currentQuestionIndex = index;
            this.showExplanation = false;
        }
    },
    
    nextQuestion() {
        if (this.currentQuestionIndex < this.quiz.questions.length - 1) {
            this.goToQuestion(this.currentQuestionIndex + 1);
        }
    },
    
    previousQuestion() {
        if (this.currentQuestionIndex > 0) {
            this.goToQuestion(this.currentQuestionIndex - 1);
        }
    },
    
    // Answer management
    selectAnswer(questionId, optionId) {
        if (this.submitted) return;
        
        this.answers[questionId] = optionId;
        
        // Auto-advance to next question if enabled
        if (this.quiz.auto_advance) {
            setTimeout(() => {
                this.nextQuestion();
            }, 1000);
        }
    },
    
    isAnswered(questionId) {
        return this.answers[questionId] !== null;
    },
    
    getSelectedOption(questionId) {
        return this.answers[questionId];
    },
    
    // Progress tracking
    getProgress() {
        const answeredCount = Object.values(this.answers).filter(answer => answer !== null).length;
        return Math.round((answeredCount / this.quiz.questions.length) * 100);
    },
    
    getAnsweredCount() {
        return Object.values(this.answers).filter(answer => answer !== null).length;
    },
    
    // Quiz submission
    async submitQuiz() {
        if (this.submitted) return;
        
        this.trackQuestionTime(); // Track time for current question
        this.submitted = true;
        this.loading = true;
        this.stopTimer();
        
        const totalTimeSpent = Math.floor((Date.now() - this.startTime) / 1000);
        
        try {
            const response = await axios.post(`/quiz/${this.quiz.id}/submit`, {
                answers: this.answers,
                time_taken: totalTimeSpent,
                question_times: this.questionTimes,
                auto_submit: this.autoSubmit
            });
            
            this.results = response.data;
            this.score = response.data.score;
            this.correctAnswers = response.data.correct_answers;
            this.wrongAnswers = response.data.wrong_answers;
            this.showResults = true;
            
            // Show success message
            this.showNotification('تم إرسال الاختبار بنجاح!', 'success');
            
        } catch (error) {
            console.error('Failed to submit quiz:', error);
            this.submitted = false;
            this.showNotification('حدث خطأ في إرسال الاختبار. يرجى المحاولة مرة أخرى.', 'error');
            
            // Restart timer if submission failed
            if (!this.autoSubmit) {
                this.startTimer();
            }
        } finally {
            this.loading = false;
        }
    },
    
    // Auto-save progress
    async saveProgress() {
        if (this.submitted) return;
        
        try {
            await axios.post(`/quiz/${this.quiz.id}/save-progress`, {
                answers: this.answers,
                current_question: this.currentQuestionIndex,
                time_remaining: this.timeRemaining
            });
        } catch (error) {
            console.error('Failed to save progress:', error);
        }
    },
    
    // Results and explanations
    toggleExplanation() {
        this.showExplanation = !this.showExplanation;
    },
    
    isCorrectAnswer(questionId, optionId) {
        if (!this.results) return false;
        const questionResult = this.results.question_results.find(q => q.question_id === questionId);
        return questionResult && questionResult.correct_option_id === optionId;
    },
    
    isSelectedAnswer(questionId, optionId) {
        return this.answers[questionId] === optionId;
    },
    
    getQuestionResult(questionId) {
        if (!this.results) return null;
        return this.results.question_results.find(q => q.question_id === questionId);
    },
    
    // Utility methods
    getFormattedTime(seconds = null) {
        const time = seconds !== null ? seconds : this.timeRemaining;
        const hours = Math.floor(time / 3600);
        const minutes = Math.floor((time % 3600) / 60);
        const secs = time % 60;
        
        if (hours > 0) {
            return `${hours}:${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        }
        return `${minutes}:${secs.toString().padStart(2, '0')}`;
    },
    
    getTimeWarningClass() {
        if (this.timeRemaining <= 60) return 'text-red-600 animate-pulse';
        if (this.timeRemaining <= 300) return 'text-yellow-600';
        return 'text-gray-600';
    },
    
    getProgressColor() {
        const progress = this.getProgress();
        if (progress >= 80) return 'bg-green-500';
        if (progress >= 50) return 'bg-yellow-500';
        return 'bg-red-500';
    },
    
    getScoreColor() {
        if (this.score >= 90) return 'text-green-600';
        if (this.score >= 70) return 'text-yellow-600';
        return 'text-red-600';
    },
    
    getGrade() {
        if (this.score >= 90) return 'ممتاز';
        if (this.score >= 80) return 'جيد جداً';
        if (this.score >= 70) return 'جيد';
        if (this.score >= 60) return 'مقبول';
        return 'ضعيف';
    },
    
    // Notifications
    showNotification(message, type = 'info') {
        // This would integrate with your notification system
        if (window.showNotification) {
            window.showNotification(message, type);
        } else {
            alert(message);
        }
    },
    
    showTimeWarning(message) {
        this.showNotification(message, 'warning');
    },
    
    // Quiz restart
    restartQuiz() {
        if (confirm('هل أنت متأكد من أنك تريد إعادة بدء الاختبار؟')) {
            window.location.reload();
        }
    },
    
    // Review mode
    enterReviewMode() {
        this.showResults = false;
        this.currentQuestionIndex = 0;
        this.showExplanation = true;
    },
    
    // Export results
    async exportResults() {
        try {
            const response = await axios.get(`/quiz/${this.quiz.id}/export-results`, {
                responseType: 'blob'
            });
            
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', `quiz-results-${this.quiz.id}.pdf`);
            document.body.appendChild(link);
            link.click();
            link.remove();
            
        } catch (error) {
            console.error('Failed to export results:', error);
            this.showNotification('فشل في تصدير النتائج', 'error');
        }
    }
}));

// Quiz statistics component
Alpine.data('quizStats', (quizId) => ({
    stats: null,
    loading: true,
    
    async init() {
        await this.loadStats();
    },
    
    async loadStats() {
        this.loading = true;
        try {
            const response = await axios.get(`/quiz/${quizId}/stats`);
            this.stats = response.data;
        } catch (error) {
            console.error('Failed to load quiz stats:', error);
        } finally {
            this.loading = false;
        }
    }
}));

// Question bank component
Alpine.data('questionBank', () => ({
    questions: [],
    filters: {
        specialization: '',
        difficulty: '',
        tags: []
    },
    loading: false,
    selectedQuestions: [],
    
    async init() {
        await this.loadQuestions();
    },
    
    async loadQuestions() {
        this.loading = true;
        try {
            const response = await axios.get('/questions', {
                params: this.filters
            });
            this.questions = response.data;
        } catch (error) {
            console.error('Failed to load questions:', error);
        } finally {
            this.loading = false;
        }
    },
    
    toggleQuestion(questionId) {
        const index = this.selectedQuestions.indexOf(questionId);
        if (index > -1) {
            this.selectedQuestions.splice(index, 1);
        } else {
            this.selectedQuestions.push(questionId);
        }
    },
    
    isSelected(questionId) {
        return this.selectedQuestions.includes(questionId);
    },
    
    async applyFilters() {
        await this.loadQuestions();
    },
    
    clearFilters() {
        this.filters = {
            specialization: '',
            difficulty: '',
            tags: []
        };
        this.loadQuestions();
    }
}));
