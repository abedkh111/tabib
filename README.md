# طبيب - المنصة التعليمية الطبية

## 🏥 نظرة عامة

**طبيب** هي منصة تعليمية طبية شاملة مطورة بـ Laravel تهدف إلى تقديم تجربة تعليمية متميزة للطلاب والأطباء في الوطن العربي. تحتوي المنصة على بنك أسئلة تفاعلي وكورسات أونلاين ونظام إدارة متقدم.

## ✨ المزايا الرئيسية

### 🔑 بنك الأسئلة التفاعلي (MCQs)
- أسئلة مرتبة حسب التخصص الطبي (باطنة، جراحة، أطفال، نسائية، إلخ)
- واجهة اختيار من متعدد مع تصحيح مباشر
- شرح مفصل لكل إجابة مع المراجع العلمية
- تتبع تقدم الطالب والإحصائيات الشخصية
- مستويات صعوبة متدرجة (سهل، متوسط، صعب، خبير)
- نظام نقاط وتقييم شامل

### 📚 الكورسات الأونلاين
- فيديوهات تعليمية عالية الجودة
- مواد PDF وشرائح تقديمية
- ملخصات ومراجع علمية
- اختبارات تقييمية بعد كل كورس
- شهادات حضور وإتمام معتمدة
- دعم البث المباشر والتفاعل

### 👥 إدارة المستخدمين
- **الطلاب**: الوصول للكورسات وبنك الأسئلة
- **الأساتذة/المحاضرين**: إضافة المحتوى والأسئلة
- **الإدارة**: التحكم الكامل في الصلاحيات والاشتراكات

### 💳 نظام الاشتراكات والدفع
- خطط مجانية ومدفوعة
- دعم بوابات الدفع الإلكتروني (PayPal, Stripe)
- إمكانية الدفع المحلي
- إدارة الاشتراكات والتجديد التلقائي

### 🎛️ لوحة الإدارة القوية
- رفع الأسئلة والكورسات بسهولة
- إدارة المستخدمين والصلاحيات
- تقارير وإحصائيات مفصلة
- نظام إشعارات متقدم

## 🛠️ التقنيات المستخدمة

- **Backend**: Laravel 10.x
- **Frontend**: Blade Templates + Alpine.js + Tailwind CSS
- **Database**: MySQL
- **Authentication**: Laravel Sanctum
- **Permissions**: Spatie Laravel Permission
- **Payments**: Laravel Cashier (Stripe)
- **File Management**: Laravel Storage
- **PDF Generation**: DomPDF
- **Excel Export**: Maatwebsite Excel
- **Real-time**: Pusher

## 📋 متطلبات النظام

- PHP >= 8.1
- Composer
- Node.js >= 16.x
- MySQL >= 8.0
- Redis (اختياري للكاش)

## 🚀 التثبيت والإعداد

### 1. استنساخ المشروع
```bash
git clone https://github.com/your-username/tabib-medical-platform.git
cd tabib-medical-platform
```

### 2. تثبيت التبعيات
```bash
# تثبيت تبعيات PHP
composer install

# تثبيت تبعيات Node.js
npm install
```

### 3. إعداد البيئة
```bash
# نسخ ملف البيئة
cp .env.example .env

# توليد مفتاح التطبيق
php artisan key:generate
```

### 4. إعداد قاعدة البيانات
```bash
# تحديث ملف .env بمعلومات قاعدة البيانات
DB_DATABASE=tabib_medical_platform
DB_USERNAME=your_username
DB_PASSWORD=your_password

# تشغيل الهجرات
php artisan migrate

# تشغيل البذور (البيانات الأولية)
php artisan db:seed
```

### 5. إعداد التخزين
```bash
# ربط مجلد التخزين
php artisan storage:link

# إنشاء المجلدات المطلوبة
mkdir -p storage/app/public/{avatars,courses,questions,question-options,specializations}
```

### 6. تشغيل التطبيق
```bash
# تشغيل الخادم المحلي
php artisan serve

# في نافذة طرفية أخرى، تشغيل Vite
npm run dev
```

## 📁 هيكل المشروع

```
tabib/
├── app/
│   ├── Models/           # نماذج البيانات
│   ├── Http/
│   │   ├── Controllers/  # المتحكمات
│   │   └── Middleware/   # الوسطاء
│   └── Services/         # خدمات الأعمال
├── database/
│   ├── migrations/       # ملفات الهجرة
│   ├── seeders/         # بذور البيانات
│   └── factories/       # مصانع البيانات
├── resources/
│   ├── views/           # قوالب Blade
│   ├── js/              # ملفات JavaScript
│   └── css/             # ملفات CSS
└── public/              # الملفات العامة
```

## 🎯 الاستخدام

### للطلاب
1. التسجيل في المنصة
2. اختيار التخصص المناسب
3. الوصول لبنك الأسئلة أو الكورسات
4. متابعة التقدم والإحصائيات

### للأساتذة
1. طلب صلاحيات المحاضر
2. إضافة الكورسات والمحتوى
3. إنشاء الأسئلة والاختبارات
4. متابعة أداء الطلاب

### للإدارة
1. إدارة المستخدمين والصلاحيات
2. مراجعة وموافقة المحتوى
3. متابعة الإحصائيات العامة
4. إدارة الاشتراكات والمدفوعات

## 🔧 الإعدادات المتقدمة

### إعداد بوابات الدفع
```env
# Stripe
STRIPE_KEY=your_stripe_key
STRIPE_SECRET=your_stripe_secret

# PayPal
PAYPAL_CLIENT_ID=your_paypal_client_id
PAYPAL_CLIENT_SECRET=your_paypal_secret
```

### إعداد البريد الإلكتروني
```env
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
```

### إعداد التخزين السحابي (اختياري)
```env
AWS_ACCESS_KEY_ID=your_access_key
AWS_SECRET_ACCESS_KEY=your_secret_key
AWS_DEFAULT_REGION=your_region
AWS_BUCKET=your_bucket_name
```

## 📊 قاعدة البيانات

### الجداول الرئيسية
- `users` - المستخدمين
- `specializations` - التخصصات الطبية
- `courses` - الكورسات
- `questions` - الأسئلة
- `question_options` - خيارات الأسئلة
- `quizzes` - الاختبارات
- `quiz_attempts` - محاولات الاختبار

## 🧪 الاختبار

```bash
# تشغيل الاختبارات
php artisan test

# تشغيل اختبارات محددة
php artisan test --filter=QuestionTest
```

## 🚀 النشر

### على خادم مشترك
1. رفع الملفات عبر FTP
2. تحديث ملف `.env`
3. تشغيل `composer install --optimize-autoloader --no-dev`
4. تشغيل `php artisan migrate --force`

### باستخدام Docker
```bash
# بناء الصورة
docker build -t tabib-app .

# تشغيل الحاوية
docker run -p 8000:8000 tabib-app
```

## 🤝 المساهمة

نرحب بمساهماتكم! يرجى:

1. عمل Fork للمشروع
2. إنشاء فرع للميزة الجديدة
3. إجراء التغييرات مع الاختبارات
4. إرسال Pull Request

## 📝 الترخيص

هذا المشروع مرخص تحت رخصة MIT. راجع ملف [LICENSE](LICENSE) للتفاصيل.

## 📞 الدعم والتواصل

- **البريد الإلكتروني**: support@tabib-platform.com
- **الموقع**: https://tabib-platform.com
- **التوثيق**: https://docs.tabib-platform.com

## 🙏 شكر وتقدير

شكر خاص لجميع المساهمين والمطورين الذين ساعدوا في تطوير هذه المنصة التعليمية المتميزة.

---

**طبيب - نحو مستقبل طبي أفضل** 🏥✨
# tabib
