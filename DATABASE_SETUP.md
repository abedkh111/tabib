# 🗄️ دليل إعداد قاعدة البيانات - منصة طبيب التعليمية

## 🚨 حل مشكلة الاتصال بقاعدة البيانات

### المشكلة:
```
SQLSTATE[HY000] [1045] Access denied for user 'root'@'localhost' (using password: NO)
```

### الحلول المتاحة:

## 🔧 الحل الأول: إنشاء قاعدة البيانات وضبط MySQL

### 1. الدخول إلى MySQL:
```bash
sudo mysql -u root -p
```

### 2. إنشاء قاعدة البيانات:
```sql
CREATE DATABASE tabib_medical_platform CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 3. إنشاء مستخدم جديد (اختياري):
```sql
CREATE USER 'tabib_user'@'localhost' IDENTIFIED BY 'tabib_password_123';
GRANT ALL PRIVILEGES ON tabib_medical_platform.* TO 'tabib_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 4. تحديث ملف `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tabib_medical_platform
DB_USERNAME=tabib_user
DB_PASSWORD=tabib_password_123
```

## 🔧 الحل الثاني: استخدام SQLite (للتطوير السريع)

### 1. تحديث ملف `.env`:
```env
DB_CONNECTION=sqlite
DB_DATABASE=/home/abedkh/Documents/tabib/database/database.sqlite
```

### 2. إنشاء ملف قاعدة البيانات:
```bash
touch database/database.sqlite
```

### 3. تشغيل الهجرات:
```bash
php artisan migrate --seed
```

## 🔧 الحل الثالث: إصلاح إعدادات MySQL Root

### 1. إيقاف MySQL:
```bash
sudo systemctl stop mysql
```

### 2. تشغيل MySQL في الوضع الآمن:
```bash
sudo mysqld_safe --skip-grant-tables &
```

### 3. الدخول بدون كلمة مرور:
```bash
mysql -u root
```

### 4. تحديث كلمة مرور Root:
```sql
USE mysql;
UPDATE user SET authentication_string=PASSWORD('new_password') WHERE User='root';
UPDATE user SET plugin='mysql_native_password' WHERE User='root';
FLUSH PRIVILEGES;
EXIT;
```

### 5. إعادة تشغيل MySQL:
```bash
sudo systemctl restart mysql
```

## 🐳 الحل الرابع: استخدام Docker

### 1. تشغيل MySQL بـ Docker:
```bash
docker run --name tabib-mysql \
  -e MYSQL_ROOT_PASSWORD=root123 \
  -e MYSQL_DATABASE=tabib_medical_platform \
  -e MYSQL_USER=tabib_user \
  -e MYSQL_PASSWORD=tabib_password \
  -p 3306:3306 \
  -d mysql:8.0
```

### 2. تحديث ملف `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tabib_medical_platform
DB_USERNAME=tabib_user
DB_PASSWORD=tabib_password
```

## ✅ التحقق من الاتصال

### اختبار الاتصال:
```bash
php artisan tinker
```

```php
DB::connection()->getPdo();
// يجب أن يعرض: PDO object
```

## 🚀 تشغيل المشروع بعد إصلاح قاعدة البيانات

```bash
# 1. تشغيل الهجرات والبذور
php artisan migrate:fresh --seed

# 2. ربط مجلد التخزين
php artisan storage:link

# 3. مسح الكاش
php artisan config:clear
php artisan cache:clear

# 4. تشغيل الخادم
php artisan serve
```

## 📊 البيانات الافتراضية

بعد تشغيل `php artisan migrate --seed` ستحصل على:

### المستخدمين:
- **Super Admin**: `superadmin@tabib.com` / `password123`
- **Admin**: `admin@tabib.com` / `password123`
- **محاضرين**: `fatima.doctor@tabib.com` / `password123`
- **طلاب**: `abdullah.student@tabib.com` / `password123`

### البيانات:
- 20 تخصص طبي
- 6 أدوار مختلفة
- 40+ صلاحية
- 15+ مستخدم للاختبار

## 🔍 استكشاف الأخطاء

### خطأ "Table doesn't exist":
```bash
php artisan migrate:fresh --seed
```

### خطأ "Class not found":
```bash
composer dump-autoload
php artisan config:clear
```

### خطأ "Permission denied":
```bash
chmod -R 775 storage bootstrap/cache
```

---

**💡 نصيحة**: للتطوير السريع، استخدم SQLite. للإنتاج، استخدم MySQL مع مستخدم مخصص.
