#!/bin/bash

# سكريبت نشر الموقع على Apache2
# استخدم: sudo bash deploy.sh

echo "🚀 بدء عملية النشر..."

# الألوان للرسائل
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# التحقق من الصلاحيات
if [ "$EUID" -ne 0 ]; then 
    echo -e "${RED}❌ يرجى تشغيل السكريبت بصلاحيات sudo${NC}"
    exit 1
fi

PROJECT_PATH="/home/abedkh/Documents/tabib"
APACHE_USER="www-data"

echo -e "${YELLOW}📁 المشروع: $PROJECT_PATH${NC}"

# 1. تعيين الصلاحيات
echo -e "${YELLOW}1️⃣  تعيين الصلاحيات...${NC}"
chown -R $APACHE_USER:$APACHE_USER $PROJECT_PATH/storage
chown -R $APACHE_USER:$APACHE_USER $PROJECT_PATH/bootstrap/cache
chmod -R 775 $PROJECT_PATH/storage
chmod -R 775 $PROJECT_PATH/bootstrap/cache
chmod -R 755 $PROJECT_PATH/public
echo -e "${GREEN}✅ تم تعيين الصلاحيات${NC}"

# 2. إنشاء مجلدات التخزين
echo -e "${YELLOW}2️⃣  إنشاء مجلدات التخزين...${NC}"
mkdir -p $PROJECT_PATH/storage/app/public/avatars
mkdir -p $PROJECT_PATH/storage/app/public/courses
mkdir -p $PROJECT_PATH/storage/app/public/questions
mkdir -p $PROJECT_PATH/storage/app/public/specializations
chown -R $APACHE_USER:$APACHE_USER $PROJECT_PATH/storage/app/public
echo -e "${GREEN}✅ تم إنشاء المجلدات${NC}"

# 3. ربط مجلد التخزين
echo -e "${YELLOW}3️⃣  ربط مجلد التخزين...${NC}"
cd $PROJECT_PATH
sudo -u $APACHE_USER php artisan storage:link
echo -e "${GREEN}✅ تم ربط مجلد التخزين${NC}"

# 4. تفعيل mod_rewrite
echo -e "${YELLOW}4️⃣  تفعيل mod_rewrite...${NC}"
a2enmod rewrite
echo -e "${GREEN}✅ تم تفعيل mod_rewrite${NC}"

# 5. إعداد Virtual Host
echo -e "${YELLOW}5️⃣  إعداد Virtual Host...${NC}"
if [ -f "$PROJECT_PATH/apache2.conf" ]; then
    cp $PROJECT_PATH/apache2.conf /etc/apache2/sites-available/tabib.conf
    a2ensite tabib.conf
    echo -e "${GREEN}✅ تم إعداد Virtual Host${NC}"
else
    echo -e "${RED}❌ ملف apache2.conf غير موجود${NC}"
fi

# 6. إعادة تشغيل Apache
echo -e "${YELLOW}6️⃣  إعادة تشغيل Apache...${NC}"
systemctl restart apache2
echo -e "${GREEN}✅ تم إعادة تشغيل Apache${NC}"

# 7. التحقق من الحالة
echo -e "${YELLOW}7️⃣  التحقق من الحالة...${NC}"
if systemctl is-active --quiet apache2; then
    echo -e "${GREEN}✅ Apache يعمل بشكل صحيح${NC}"
else
    echo -e "${RED}❌ Apache لا يعمل${NC}"
    echo "تحقق من الأخطاء: sudo tail -f /var/log/apache2/error.log"
fi

echo -e "${GREEN}🎉 انتهت عملية النشر!${NC}"
echo -e "${YELLOW}📝 الخطوات التالية:${NC}"
echo "1. تأكد من إعداد ملف .env بشكل صحيح"
echo "2. قم بتشغيل: php artisan migrate"
echo "3. قم بتشغيل: php artisan optimize"
echo "4. أضف tabib.local إلى /etc/hosts إذا كنت تستخدمه محلياً"

