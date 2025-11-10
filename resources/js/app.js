import './bootstrap';
import Alpine from 'alpinejs';
import axios from 'axios';

// Make Alpine available globally
window.Alpine = Alpine;

// Configure axios defaults
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['Accept'] = 'application/json';

// Add CSRF token to all requests
const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}

// Global Alpine data and utilities
Alpine.data('app', () => ({
    // Global state
    sidebarOpen: false,
    darkMode: localStorage.getItem('darkMode') === 'true',
    notifications: [],
    loading: false,

    // Initialize
    init() {
        this.applyTheme();
        this.loadNotifications();
        
        // Listen for theme changes
        this.$watch('darkMode', () => {
            this.applyTheme();
            localStorage.setItem('darkMode', this.darkMode);
        });
    },

    // Theme management
    applyTheme() {
        if (this.darkMode) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    },

    toggleTheme() {
        this.darkMode = !this.darkMode;
    },

    // Sidebar management
    toggleSidebar() {
        this.sidebarOpen = !this.sidebarOpen;
    },

    closeSidebar() {
        this.sidebarOpen = false;
    },

    // Notifications
    async loadNotifications() {
        try {
            const response = await axios.get('/api/notifications');
            this.notifications = response.data;
        } catch (error) {
            console.error('Failed to load notifications:', error);
        }
    },

    addNotification(notification) {
        this.notifications.unshift(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            this.removeNotification(notification.id);
        }, 5000);
    },

    removeNotification(id) {
        const index = this.notifications.findIndex(n => n.id === id);
        if (index > -1) {
            this.notifications.splice(index, 1);
        }
    },

    // Utility methods
    formatDate(date) {
        return new Date(date).toLocaleDateString('ar-SA', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    },

    formatTime(date) {
        return new Date(date).toLocaleTimeString('ar-SA', {
            hour: '2-digit',
            minute: '2-digit'
        });
    },

    formatDuration(seconds) {
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        const secs = seconds % 60;

        if (hours > 0) {
            return `${hours}:${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        }
        return `${minutes}:${secs.toString().padStart(2, '0')}`;
    },

    // Loading state
    setLoading(state) {
        this.loading = state;
    }
}));

// Quiz functionality
Alpine.data('quiz', () => ({
    currentQuestion: 0,
    answers: {},
    timeRemaining: 0,
    timer: null,
    showResults: false,
    submitted: false,

    init() {
        this.startTimer();
    },

    startTimer() {
        if (this.timer) clearInterval(this.timer);
        
        this.timer = setInterval(() => {
            if (this.timeRemaining > 0) {
                this.timeRemaining--;
            } else {
                this.submitQuiz();
            }
        }, 1000);
    },

    selectAnswer(questionId, optionId) {
        if (!this.submitted) {
            this.answers[questionId] = optionId;
        }
    },

    nextQuestion() {
        if (this.currentQuestion < this.totalQuestions - 1) {
            this.currentQuestion++;
        }
    },

    previousQuestion() {
        if (this.currentQuestion > 0) {
            this.currentQuestion--;
        }
    },

    goToQuestion(index) {
        this.currentQuestion = index;
    },

    async submitQuiz() {
        if (this.submitted) return;
        
        this.submitted = true;
        clearInterval(this.timer);
        
        try {
            const response = await axios.post('/quiz/submit', {
                answers: this.answers,
                time_taken: this.totalTime - this.timeRemaining
            });
            
            this.showResults = true;
            this.results = response.data;
        } catch (error) {
            console.error('Failed to submit quiz:', error);
            this.submitted = false;
        }
    },

    getFormattedTime() {
        const minutes = Math.floor(this.timeRemaining / 60);
        const seconds = this.timeRemaining % 60;
        return `${minutes}:${seconds.toString().padStart(2, '0')}`;
    }
}));

// Course player functionality
Alpine.data('coursePlayer', () => ({
    currentLesson: 0,
    progress: 0,
    playing: false,
    completed: false,

    init() {
        this.loadProgress();
    },

    async loadProgress() {
        try {
            const response = await axios.get(`/courses/${this.courseId}/progress`);
            this.progress = response.data.progress;
            this.currentLesson = response.data.current_lesson || 0;
        } catch (error) {
            console.error('Failed to load progress:', error);
        }
    },

    async markLessonComplete(lessonId) {
        try {
            await axios.post(`/lessons/${lessonId}/complete`);
            this.updateProgress();
        } catch (error) {
            console.error('Failed to mark lesson complete:', error);
        }
    },

    async updateProgress() {
        try {
            const response = await axios.post(`/courses/${this.courseId}/progress`);
            this.progress = response.data.progress;
            
            if (response.data.completed) {
                this.completed = true;
                this.showCompletionModal();
            }
        } catch (error) {
            console.error('Failed to update progress:', error);
        }
    },

    showCompletionModal() {
        // Show course completion modal
        this.$dispatch('show-modal', { type: 'course-completion' });
    }
}));

// Form validation
Alpine.data('form', () => ({
    errors: {},
    submitting: false,

    async submit(url, data) {
        this.submitting = true;
        this.errors = {};

        try {
            const response = await axios.post(url, data);
            return response.data;
        } catch (error) {
            if (error.response && error.response.status === 422) {
                this.errors = error.response.data.errors;
            }
            throw error;
        } finally {
            this.submitting = false;
        }
    },

    hasError(field) {
        return this.errors[field] && this.errors[field].length > 0;
    },

    getError(field) {
        return this.hasError(field) ? this.errors[field][0] : '';
    },

    clearError(field) {
        if (this.errors[field]) {
            delete this.errors[field];
        }
    }
}));

// Modal functionality
Alpine.data('modal', () => ({
    show: false,
    title: '',
    content: '',
    type: 'info',

    open(options = {}) {
        this.title = options.title || '';
        this.content = options.content || '';
        this.type = options.type || 'info';
        this.show = true;
        
        // Prevent body scroll
        document.body.style.overflow = 'hidden';
    },

    close() {
        this.show = false;
        document.body.style.overflow = '';
    },

    confirm(message, callback) {
        this.open({
            title: 'تأكيد',
            content: message,
            type: 'confirm'
        });
        
        this.onConfirm = callback;
    }
}));

// Search functionality
Alpine.data('search', () => ({
    query: '',
    results: [],
    loading: false,
    debounceTimer: null,

    init() {
        this.$watch('query', () => {
            this.debounceSearch();
        });
    },

    debounceSearch() {
        clearTimeout(this.debounceTimer);
        this.debounceTimer = setTimeout(() => {
            if (this.query.length >= 2) {
                this.performSearch();
            } else {
                this.results = [];
            }
        }, 300);
    },

    async performSearch() {
        this.loading = true;
        
        try {
            const response = await axios.get('/search', {
                params: { q: this.query }
            });
            this.results = response.data;
        } catch (error) {
            console.error('Search failed:', error);
            this.results = [];
        } finally {
            this.loading = false;
        }
    },

    clearSearch() {
        this.query = '';
        this.results = [];
    }
}));

// File upload functionality
Alpine.data('fileUpload', () => ({
    files: [],
    uploading: false,
    progress: 0,

    handleFiles(event) {
        const selectedFiles = Array.from(event.target.files);
        this.files = [...this.files, ...selectedFiles];
    },

    removeFile(index) {
        this.files.splice(index, 1);
    },

    async uploadFiles(url) {
        if (this.files.length === 0) return;

        this.uploading = true;
        this.progress = 0;

        const formData = new FormData();
        this.files.forEach((file, index) => {
            formData.append(`files[${index}]`, file);
        });

        try {
            const response = await axios.post(url, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                },
                onUploadProgress: (progressEvent) => {
                    this.progress = Math.round(
                        (progressEvent.loaded * 100) / progressEvent.total
                    );
                }
            });

            this.files = [];
            return response.data;
        } catch (error) {
            console.error('Upload failed:', error);
            throw error;
        } finally {
            this.uploading = false;
            this.progress = 0;
        }
    }
}));

// Initialize Alpine
Alpine.start();

// Global utilities
window.showNotification = function(message, type = 'info') {
    const notification = {
        id: Date.now(),
        message,
        type,
        timestamp: new Date()
    };
    
    Alpine.store('app').addNotification(notification);
};

window.confirmAction = function(message, callback) {
    if (confirm(message)) {
        callback();
    }
};

// Export for use in other modules
export { Alpine, axios };
