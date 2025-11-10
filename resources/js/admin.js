import './bootstrap';
import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';
import Swal from 'sweetalert2';

// تهيئة Alpine.js
window.Alpine = Alpine;
Alpine.start();

// تهيئة Chart.js
window.Chart = Chart;

// تهيئة SweetAlert2
window.Swal = Swal;

// إعدادات Chart.js العامة
Chart.defaults.font.family = 'Cairo, sans-serif';
Chart.defaults.color = '#374151';

// وظائف لوحة الإدارة
window.AdminDashboard = {
    // إحصائيات المستخدمين
    initUserStats() {
        const ctx = document.getElementById('userStatsChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['طلاب', 'محاضرين', 'إدارة'],
                    datasets: [{
                        data: [150, 25, 5],
                        backgroundColor: [
                            '#3B82F6',
                            '#10B981',
                            '#F59E0B'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    },

    // إحصائيات الكورسات
    initCourseStats() {
        const ctx = document.getElementById('courseStatsChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['طب باطني', 'جراحة', 'أطفال', 'نسائية', 'عيون'],
                    datasets: [{
                        label: 'عدد الكورسات',
                        data: [12, 8, 15, 10, 6],
                        backgroundColor: '#3B82F6'
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    },

    // إحصائيات الاختبارات
    initQuizStats() {
        const ctx = document.getElementById('quizStatsChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'],
                    datasets: [{
                        label: 'محاولات الاختبار',
                        data: [65, 78, 90, 81, 95, 102],
                        borderColor: '#10B981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    },

    // حذف عنصر مع تأكيد
    confirmDelete(url, title = 'هل أنت متأكد؟') {
        Swal.fire({
            title: title,
            text: 'لن تتمكن من التراجع عن هذا الإجراء!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'نعم، احذف!',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                // إرسال طلب الحذف
                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('تم الحذف!', data.message, 'success')
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

    // تبديل حالة المستخدم
    toggleUserStatus(userId, currentStatus) {
        const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
        const statusText = newStatus === 'active' ? 'تفعيل' : 'إلغاء تفعيل';
        
        Swal.fire({
            title: `${statusText} المستخدم`,
            text: `هل تريد ${statusText} هذا المستخدم؟`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'نعم',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/users/${userId}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ status: newStatus })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('تم!', data.message, 'success')
                            .then(() => location.reload());
                    } else {
                        Swal.fire('خطأ!', data.message, 'error');
                    }
                });
            }
        });
    },

    // تهيئة جميع الرسوم البيانية
    initAllCharts() {
        this.initUserStats();
        this.initCourseStats();
        this.initQuizStats();
    }
};

// تهيئة لوحة الإدارة عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    // تهيئة الرسوم البيانية
    AdminDashboard.initAllCharts();
    
    // تهيئة أزرار الحذف
    document.querySelectorAll('[data-delete-url]').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('data-delete-url');
            const title = this.getAttribute('data-title') || 'هل أنت متأكد؟';
            AdminDashboard.confirmDelete(url, title);
        });
    });
    
    // تهيئة أزرار تبديل الحالة
    document.querySelectorAll('[data-toggle-status]').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const userId = this.getAttribute('data-user-id');
            const currentStatus = this.getAttribute('data-current-status');
            AdminDashboard.toggleUserStatus(userId, currentStatus);
        });
    });
});

console.log('🏥 Admin Dashboard loaded successfully!');
