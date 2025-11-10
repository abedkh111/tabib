# دليل نشر الموقع على Apache2

## المتطلبات الأساسية
- Apache2 مثبت ومفعل
- PHP 8.1 أو أحدث مع Extensions:
  - BCMath
  - Ctype
  - Fileinfo
  - JSON
  - Mbstring
  - OpenSSL
  - PDO
  - Tokenizer
  - XML
- mod_rewrite مفعل في Apache
- MySQL/MariaDB

## خطوات النشر

### 1. إعداد الملفات والصلاحيات

```bash
# الانتقال إلى مجلد المشروع
cd /home/abedkh/Documents/tabib

# تعيين الصلاحيات المناسبة
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
sudo chmod -R 755 public

# إنشاء مجلدات التخزين إذا لم تكن موجودة
mkdir -p storage/app/public/avatars
mkdir -p storage/app/public/courses
mkdir -p storage/app/public/questions
mkdir -p storage/app/public/specializations
sudo chown -R www-data:www-data storage/app/public
```

### 2. إعداد ملف .env

```bash
# نسخ ملف .env.example
cp .env.example .env

# تعديل ملف .env
nano .env
```

تأكد من تعديل هذه القيم:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=http://tabib.local

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tabib_medical_platform
DB_USERNAME=your_db_username
DB_PASSWORD=your_db_password

FILESYSTEM_DISK=local
```

### 3. تثبيت التبعيات

```bash
# تثبيت Composer dependencies
composer install --no-dev --optimize-autoloader

# إنشاء مفتاح التطبيق
php artisan key:generate

# ربط مجلد التخزين
php artisan storage:link
```

### 4. إعداد قاعدة البيانات

```bash
# تشغيل الهجرات
php artisan migrate --force

# (اختياري) تشغيل البذور
php artisan db:seed
```

### 5. تحسين الأداء

```bash
# تحسين التطبيق للإنتاج
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### 6. إعداد Apache2

#### أ. تفعيل mod_rewrite
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

#### ب. إضافة Virtual Host
```bash
# نسخ ملف الإعداد
sudo cp apache2.conf /etc/apache2/sites-available/tabib.conf

# تفعيل الموقع
sudo a2ensite tabib.conf

# إعادة تشغيل Apache
sudo systemctl restart apache2
```

#### ج. إضافة إلى /etc/hosts (للوصول المحلي)
```bash
sudo nano /etc/hosts
```

أضف السطر:
```
127.0.0.1    tabib.local
```

### 7. التحقق من الإعدادات

```bash
# التحقق من الأخطاء
sudo tail -f /var/log/apache2/tabib_error.log

# التحقق من الصلاحيات
ls -la storage bootstrap/cache
```

### 8. إعداد SSL (اختياري)

إذا كنت تريد استخدام HTTPS:
```bash
# تثبيت certbot
sudo apt install certbot python3-certbot-apache

# الحصول على شهادة SSL
sudo certbot --apache -d tabib.local
```

## حل المشاكل الشائعة

### خطأ 500 Internal Server Error
```bash
# التحقق من الأخطاء
tail -f storage/logs/laravel.log
sudo tail -f /var/log/apache2/error.log

# التحقق من الصلاحيات
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### خطأ 403 Forbidden
```bash
# التحقق من صلاحيات المجلدات
sudo chmod -R 755 public
sudo chown -R www-data:www-data public
```

### mod_rewrite لا يعمل
```bash
# تفعيل mod_rewrite
sudo a2enmod rewrite
sudo systemctl restart apache2

# التحقق من أن AllowOverride All موجود في VirtualHost
```

### مشاكل في رفع الملفات
```bash
# التحقق من إعدادات PHP
php -i | grep upload_max_filesize
php -i | grep post_max_size

# تعديل php.ini إذا لزم الأمر
sudo nano /etc/php/8.1/apache2/php.ini
```

## الأوامر المفيدة

```bash
# إعادة تحميل التكوين
php artisan config:clear
php artisan config:cache

# إعادة تحميل Routes
php artisan route:clear
php artisan route:cache

# مسح الكاش
php artisan optimize:clear
php artisan optimize
```

## الأمان

1. تأكد من أن `APP_DEBUG=false` في الإنتاج
2. لا تضع ملف `.env` في Git
3. استخدم HTTPS في الإنتاج
4. راجع صلاحيات الملفات والمجلدات
5. قم بتحديث Laravel والتبعيات بانتظام

