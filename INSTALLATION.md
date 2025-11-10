# دليل التثبيت والتشغيل - منصة طبيب التعليمية

## 📋 متطلبات النظام

### المتطلبات الأساسية
- **PHP**: الإصدار 8.1 أو أحدث
- **Composer**: لإدارة تبعيات PHP
- **Node.js**: الإصدار 16.x أو أحدث
- **NPM**: لإدارة تبعيات JavaScript
- **MySQL**: الإصدار 8.0 أو أحدث
- **Redis**: (اختياري) للكاش والجلسات

### امتدادات PHP المطلوبة
- BCMath PHP Extension
- Ctype PHP Extension
- Fileinfo PHP Extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- GD PHP Extension
- ZIP PHP Extension

## 🚀 التثبيت السريع

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

### 4. تحديث ملف .env
قم بتحديث المتغيرات التالية في ملف `.env`:

```env
APP_NAME="طبيب - المنصة التعليمية الطبية"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tabib_medical_platform
DB_USERNAME=your_username
DB_PASSWORD=your_password

MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@tabib-platform.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 5. إعداد قاعدة البيانات
```bash
# إنشاء قاعدة البيانات (تأكد من إنشائها في MySQL أولاً)
# CREATE DATABASE tabib_medical_platform;

# تشغيل الهجرات
php artisan migrate

# تشغيل البذور (البيانات الأولية)
php artisan db:seed
```

### 6. إعداد التخزين
```bash
# ربط مجلد التخزين
php artisan storage:link

# إنشاء المجلدات المطلوبة
mkdir -p storage/app/public/{avatars,courses,questions,question-options,specializations}
```

### 7. بناء الأصول الأمامية
```bash
# للتطوير
npm run dev

# للإنتاج
npm run build
```

### 8. تشغيل التطبيق
```bash
# تشغيل الخادم المحلي
php artisan serve

# في نافذة طرفية أخرى (للتطوير)
npm run dev
```

## 🐳 التثبيت باستخدام Docker

### 1. استنساخ المشروع
```bash
git clone https://github.com/your-username/tabib-medical-platform.git
cd tabib-medical-platform
```

### 2. إعداد البيئة
```bash
cp .env.example .env
# قم بتحديث متغيرات قاعدة البيانات في .env لتتوافق مع Docker
```

### 3. بناء وتشغيل الحاويات
```bash
# بناء وتشغيل جميع الخدمات
docker-compose up -d --build

# تشغيل الهجرات والبذور
docker-compose exec app php artisan migrate --seed

# ربط مجلد التخزين
docker-compose exec app php artisan storage:link
```

### 4. الوصول للتطبيق
- **التطبيق**: http://localhost
- **phpMyAdmin**: http://localhost:8080
- **MailHog**: http://localhost:8025

## 🔧 الإعداد المتقدم

### إعداد بوابات الدفع

#### Stripe
```env
STRIPE_KEY=pk_test_your_stripe_publishable_key
STRIPE_SECRET=sk_test_your_stripe_secret_key
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret
```

#### PayPal
```env
PAYPAL_CLIENT_ID=your_paypal_client_id
PAYPAL_CLIENT_SECRET=your_paypal_client_secret
PAYPAL_MODE=sandbox  # أو live للإنتاج
```

### إعداد التخزين السحابي (AWS S3)
```env
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your_access_key
AWS_SECRET_ACCESS_KEY=your_secret_key
AWS_DEFAULT_REGION=your_region
AWS_BUCKET=your_bucket_name
```

### إعداد Redis للكاش
```env
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### إعداد Pusher للإشعارات المباشرة
```env
BROADCAST_DRIVER=pusher

PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_APP_CLUSTER=your_app_cluster

VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

## 👥 الحسابات الافتراضية

بعد تشغيل `php artisan db:seed`، ستتوفر الحسابات التالية:

### مدير النظام الرئيسي
- **البريد الإلكتروني**: superadmin@tabib.com
- **كلمة المرور**: password123
- **الدور**: Super Admin

### المدير
- **البريد الإلكتروني**: admin@tabib.com
- **كلمة المرور**: password123
- **الدور**: Admin

### المحاضرين
- **د. فاطمة أحمد**: fatima.doctor@tabib.com
- **د. محمد علي**: mohammed.surgeon@tabib.com
- **د. سارة خالد**: sara.pediatrician@tabib.com
- **كلمة المرور**: password123

### الطلاب
- **عبدالله سعد**: abdullah.student@tabib.com
- **نورا أحمد**: nora.student@tabib.com
- **خالد محمد**: khalid.resident@tabib.com
- **كلمة المرور**: password123

## 🔄 المهام الدورية

### إعداد Cron Jobs
أضف السطر التالي إلى crontab:
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

### تشغيل Queue Workers
```bash
# تشغيل معالج المهام
php artisan queue:work

# أو باستخدام Supervisor (موصى به للإنتاج)
sudo supervisorctl start laravel-worker:*
```

## 🧪 تشغيل الاختبارات

```bash
# تشغيل جميع الاختبارات
php artisan test

# تشغيل اختبارات محددة
php artisan test --filter=QuestionTest

# تشغيل الاختبارات مع تقرير التغطية
php artisan test --coverage
```

## 📊 مراقبة الأداء

### تفعيل التسجيل المتقدم
```env
LOG_LEVEL=info
LOG_CHANNEL=daily
```

### مراقبة قاعدة البيانات
```bash
# تفعيل تسجيل استعلامات قاعدة البيانات
php artisan tinker
DB::enableQueryLog();
```

## 🔒 الأمان

### تحديث كلمات المرور الافتراضية
```bash
# تغيير كلمة مرور المدير
php artisan tinker
$user = App\Models\User::where('email', 'admin@tabib.com')->first();
$user->password = Hash::make('new_secure_password');
$user->save();
```

### تفعيل HTTPS (للإنتاج)
```env
APP_URL=https://your-domain.com
FORCE_HTTPS=true
```

### إعداد معدل الطلبات
```bash
# في config/app.php
'throttle' => [
    'api' => '60,1',
    'web' => '1000,1',
],
```

## 🚨 استكشاف الأخطاء

### مشاكل شائعة وحلولها

#### خطأ في الصلاحيات
```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

#### خطأ في مفتاح التطبيق
```bash
php artisan key:generate
php artisan config:cache
```

#### مشاكل في قاعدة البيانات
```bash
php artisan migrate:fresh --seed
```

#### مشاكل في الكاش
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## 📞 الدعم الفني

إذا واجهت أي مشاكل:

1. تحقق من ملف السجلات: `storage/logs/laravel.log`
2. راجع الوثائق: [docs.tabib-platform.com](https://docs.tabib-platform.com)
3. تواصل معنا: support@tabib-platform.com
4. أنشئ issue في GitHub: [github.com/tabib-platform/issues](https://github.com/tabib-platform/issues)

## 🎉 تهانينا!

تم تثبيت منصة طبيب التعليمية بنجاح! يمكنك الآن:

- الوصول للموقع عبر المتصفح
- تسجيل الدخول باستخدام الحسابات الافتراضية
- إنشاء كورسات وأسئلة جديدة
- استكشاف جميع المزايا المتاحة

**نتمنى لك تجربة تعليمية ممتعة ومفيدة! 🏥✨**
