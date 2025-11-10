import './bootstrap';
import Alpine from 'alpinejs';
import Swal from 'sweetalert2';
import Quill from 'quill';

// تهيئة Alpine.js
window.Alpine = Alpine;
Alpine.start();

// تهيئة SweetAlert2
window.Swal = Swal;

// تهيئة Quill Editor
window.Quill = Quill;

// وظائف الكورسات
window.CourseManager = {
    // تهيئة محرر النصوص
    initQuillEditor(selector = '#description-editor') {
        const editorElement = document.querySelector(selector);
        if (editorElement) {
            const quill = new Quill(selector, {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'color': [] }, { 'background': [] }],
                        [{ 'align': [] }],
                        ['link', 'image'],
                        ['clean']
                    ]
                },
                placeholder: 'اكتب وصف الكورس هنا...'
            });

            // ربط المحرر بـ form
            const form = editorElement.closest('form');
            if (form) {
                form.addEventListener('submit', function() {
                    const hiddenInput = form.querySelector('input[name="description"]');
                    if (hiddenInput) {
                        hiddenInput.value = quill.root.innerHTML;
                    }
                });
            }

            return quill;
        }
        return null;
    },

    // تسجيل في الكورس
    enrollCourse(courseId) {
        Swal.fire({
            title: 'تسجيل في الكورس',
            text: 'هل تريد التسجيل في هذا الكورس؟',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'نعم، سجل',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/courses/${courseId}/enroll`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('تم التسجيل!', data.message, 'success')
                            .then(() => location.reload());
                    } else {
                        Swal.fire('خطأ!', data.message, 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('خطأ!', 'حدث خطأ غير متوقع', 'error');
                });
            }
        });
    },

    // إلغاء التسجيل من الكورس
    unenrollCourse(courseId) {
        Swal.fire({
            title: 'إلغاء التسجيل',
            text: 'هل تريد إلغاء التسجيل من هذا الكورس؟',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'نعم، ألغي',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/courses/${courseId}/unenroll`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('تم الإلغاء!', data.message, 'success')
                            .then(() => location.reload());
                    } else {
                        Swal.fire('خطأ!', data.message, 'error');
                    }
                });
            }
        });
    },

    // تقييم الكورس
    rateCourse(courseId, rating) {
        fetch(`/courses/${courseId}/rate`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ rating: rating })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('شكراً!', 'تم حفظ تقييمك', 'success');
                // تحديث عرض التقييم
                this.updateRatingDisplay(data.averageRating, data.totalRatings);
            } else {
                Swal.fire('خطأ!', data.message, 'error');
            }
        });
    },

    // تحديث عرض التقييم
    updateRatingDisplay(averageRating, totalRatings) {
        const ratingElement = document.querySelector('.course-rating');
        if (ratingElement) {
            ratingElement.innerHTML = `
                <div class="flex items-center">
                    <div class="flex text-yellow-400">
                        ${this.generateStars(averageRating)}
                    </div>
                    <span class="mr-2 text-sm text-gray-600">
                        ${averageRating.toFixed(1)} (${totalRatings} تقييم)
                    </span>
                </div>
            `;
        }
    },

    // توليد النجوم للتقييم
    generateStars(rating) {
        let stars = '';
        for (let i = 1; i <= 5; i++) {
            if (i <= rating) {
                stars += '<i class="fas fa-star"></i>';
            } else if (i - 0.5 <= rating) {
                stars += '<i class="fas fa-star-half-alt"></i>';
            } else {
                stars += '<i class="far fa-star"></i>';
            }
        }
        return stars;
    },

    // تشغيل الفيديو
    playVideo(videoUrl, lessonId) {
        // تسجيل مشاهدة الدرس
        fetch(`/lessons/${lessonId}/view`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        });

        // تشغيل الفيديو
        const videoPlayer = document.querySelector('#video-player');
        if (videoPlayer) {
            videoPlayer.src = videoUrl;
            videoPlayer.play();
        }
    },

    // تحديث تقدم الكورس
    updateProgress(courseId, lessonId) {
        fetch(`/courses/${courseId}/progress`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ lesson_id: lessonId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // تحديث شريط التقدم
                const progressBar = document.querySelector('.progress-bar');
                if (progressBar) {
                    progressBar.style.width = `${data.progress}%`;
                    progressBar.textContent = `${data.progress}%`;
                }
            }
        });
    }
};

// تهيئة النجوم التفاعلية للتقييم
function initRatingStars() {
    const stars = document.querySelectorAll('.rating-star');
    stars.forEach((star, index) => {
        star.addEventListener('click', function() {
            const courseId = this.getAttribute('data-course-id');
            const rating = index + 1;
            
            // تحديث عرض النجوم
            stars.forEach((s, i) => {
                if (i <= index) {
                    s.classList.add('text-yellow-400');
                    s.classList.remove('text-gray-300');
                } else {
                    s.classList.add('text-gray-300');
                    s.classList.remove('text-yellow-400');
                }
            });
            
            // إرسال التقييم
            CourseManager.rateCourse(courseId, rating);
        });
        
        star.addEventListener('mouseenter', function() {
            stars.forEach((s, i) => {
                if (i <= index) {
                    s.classList.add('text-yellow-300');
                } else {
                    s.classList.remove('text-yellow-300');
                }
            });
        });
        
        star.addEventListener('mouseleave', function() {
            stars.forEach(s => {
                s.classList.remove('text-yellow-300');
            });
        });
    });
}

// تهيئة الكورسات عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    // تهيئة محرر النصوص
    CourseManager.initQuillEditor();
    
    // تهيئة النجوم التفاعلية
    initRatingStars();
    
    // تهيئة أزرار التسجيل
    document.querySelectorAll('[data-enroll-course]').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const courseId = this.getAttribute('data-course-id');
            CourseManager.enrollCourse(courseId);
        });
    });
    
    // تهيئة أزرار إلغاء التسجيل
    document.querySelectorAll('[data-unenroll-course]').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const courseId = this.getAttribute('data-course-id');
            CourseManager.unenrollCourse(courseId);
        });
    });
    
    // تهيئة مشغل الفيديو
    document.querySelectorAll('[data-play-video]').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const videoUrl = this.getAttribute('data-video-url');
            const lessonId = this.getAttribute('data-lesson-id');
            CourseManager.playVideo(videoUrl, lessonId);
        });
    });
});

console.log('📚 Course Manager loaded successfully!');
