# Ghanem ERP - Cron Jobs Documentation
# توثيق المهام المجدولة لنظام غانم

**آخر تحديث / Last Updated:** November 2025

---

## نظرة عامة / Overview

يحتاج نظام Ghanem ERP إلى تشغيل المهام المجدولة لضمان عمل النظام بشكل صحيح.
The Ghanem ERP system requires scheduled tasks to run properly.

---

## إعداد cPanel / cPanel Setup

### الخطوة 1: الوصول إلى Cron Jobs
1. افتح لوحة تحكم cPanel
2. ابحث عن "Cron Jobs" في قسم "Advanced"
3. انقر على أيقونة Cron Jobs

### Step 1: Access Cron Jobs
1. Open your cPanel dashboard
2. Search for "Cron Jobs" in the "Advanced" section
3. Click on the Cron Jobs icon

---

## المهام المطلوبة / Required Jobs

### 1. Laravel Scheduler (المهمة الرئيسية / Main Task)
**مهم جداً:** هذا الأمر يشغل جميع المهام المجدولة في Laravel

```bash
* * * * * cd /home/USERNAME/public_html && /usr/local/bin/php artisan schedule:run >> /dev/null 2>&1
```

**التكرار:** كل دقيقة (Every Minute)
**الإعدادات في cPanel:**
- Minute: * (كل دقيقة)
- Hour: * (كل ساعة)
- Day: * (كل يوم)
- Month: * (كل شهر)
- Weekday: * (كل أيام الأسبوع)

---

### 2. Queue Worker (معالج الطابور)
**لمعالجة الإيميلات والإشعارات:**

```bash
*/5 * * * * cd /home/USERNAME/public_html && /usr/local/bin/php artisan queue:work --stop-when-empty --max-time=60 >> /dev/null 2>&1
```

**التكرار:** كل 5 دقائق
**الإعدادات في cPanel:**
- Minute: */5
- Hour: *
- Day: *
- Month: *
- Weekday: *

---

### 3. Cache Cleanup (تنظيف الكاش) - اختياري
```bash
0 */6 * * * cd /home/USERNAME/public_html && /usr/local/bin/php artisan cache:prune-stale-tags >> /dev/null 2>&1
```

**التكرار:** كل 6 ساعات

---

## المهام المجدولة تلقائياً / Auto-Scheduled Tasks

هذه المهام تعمل تلقائياً من خلال Laravel Scheduler:

| المهمة / Task | التوقيت / Schedule | الوصف / Description |
|---------------|-------------------|---------------------|
| `reports:send-scheduled` | يومياً 08:00 صباحاً | إرسال التقارير المجدولة بالإيميل (Send scheduled reports via email) |
| `reports:run-scheduled` | يومياً 08:00 صباحاً | تشغيل وإرسال التقارير المجدولة مع الملفات (Run and send scheduled reports with file attachments) |
| `stock:check-low` | يومياً 07:00 صباحاً | فحص تنبيهات المخزون المنخفض (Check low stock alerts) |
| `pos:close-day` | يومياً 23:55 مساءً | إغلاق يوم نقطة البيع (Close POS day for all branches) |
| `rental:generate-recurring` | يومياً 00:30 صباحاً | إنشاء فواتير الإيجار المتكررة (Generate recurring rental invoices) |
| `system:backup --verify` | يومياً 02:00 صباحاً | عمل نسخة احتياطية مع التحقق (Run verified system backup) |
| `hrm:payroll` | شهرياً (اليوم 1 - 01:30) | تشغيل الرواتب الشهرية (Run monthly payroll) |
| `queue:work --stop-when-empty` | كل دقيقة | معالجة طابور المهام (Process job queue) |
| `cache:prune-stale-tags` | كل ساعة | تنظيف الكاش القديم (Clean stale cache tags) |

---

## الأوامر المتاحة للتشغيل اليدوي / Available Manual Commands

### تقارير / Reports
```bash
# تشغيل جميع التقارير المجدولة المستحقة
php artisan reports:run-scheduled

# تشغيل تقرير محدد فوراً
php artisan reports:run-scheduled --id=1

# تشغيل جميع التقارير بغض النظر عن الوقت
php artisan reports:run-scheduled --force

# إرسال التقارير المجدولة
php artisan reports:send-scheduled
```

### نقطة البيع / POS
```bash
# إغلاق يوم نقطة البيع
php artisan pos:close-day

# إغلاق يوم محدد
php artisan pos:close-day --date=2025-11-25
```

### المخزون / Inventory
```bash
# فحص تنبيهات المخزون المنخفض
php artisan stock:check-low
```

### الإيجارات / Rental
```bash
# إنشاء فواتير الإيجار المتكررة
php artisan rental:generate-recurring

# لتاريخ محدد
php artisan rental:generate-recurring --date=2025-11-25
```

### النسخ الاحتياطي / Backup
```bash
# عمل نسخة احتياطية
php artisan system:backup

# نسخة احتياطية مع التحقق
php artisan system:backup --verify
```

### الموارد البشرية / HRM
```bash
# تشغيل الرواتب للشهر الحالي
php artisan hrm:payroll

# لفترة محددة
php artisan hrm:payroll --period=2025-11
```

---

## ملاحظات مهمة / Important Notes

### 1. تغيير المسار / Change Path
استبدل `/home/USERNAME/public_html` بمسار التطبيق الفعلي على السيرفر.

Replace `/home/USERNAME/public_html` with your actual application path.

### 2. مسار PHP / PHP Path
تأكد من مسار PHP الصحيح. المسارات الشائعة:
- `/usr/local/bin/php`
- `/usr/bin/php`
- `/usr/local/bin/php82`
- `/opt/cpanel/ea-php82/root/usr/bin/php`

للتحقق من مسار PHP:
```bash
which php
```

### 3. الصلاحيات / Permissions
تأكد من صلاحيات الملفات:
```bash
chmod -R 755 /home/USERNAME/public_html/storage
chmod -R 755 /home/USERNAME/public_html/bootstrap/cache
```

### 4. ملف السجلات / Log Files
لتتبع الأخطاء، يمكنك حفظ السجلات:
```bash
* * * * * cd /home/USERNAME/public_html && /usr/local/bin/php artisan schedule:run >> /home/USERNAME/logs/cron.log 2>&1
```

---

## استكشاف الأخطاء / Troubleshooting

### المشكلة: المهام لا تعمل
1. تحقق من مسار PHP
2. تحقق من مسار التطبيق
3. تحقق من صلاحيات الملفات
4. راجع ملف السجلات

### المشكلة: الإيميلات لا ترسل
1. تحقق من إعدادات SMTP في ملف `.env`
2. تأكد من تشغيل Queue Worker
3. راجع `storage/logs/laravel.log`

### المشكلة: التقارير لا تُنشأ
1. تحقق من وجود قوالب التقارير في قاعدة البيانات
2. تأكد من صحة البريد الإلكتروني للمستلمين
3. راجع مجلد `storage/app/reports` للملفات المُنشأة

### اختبار Cron يدوياً
```bash
cd /home/USERNAME/public_html
php artisan schedule:run

# عرض جميع المهام المجدولة
php artisan schedule:list
```

---

## ملف .env للإنتاج / Production .env

```env
APP_NAME="Ghanem ERP"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
APP_TIMEZONE=Africa/Cairo
APP_LOCALE=ar

DB_CONNECTION=pgsql
DB_HOST=your_db_host
DB_PORT=5432
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"

QUEUE_CONNECTION=database
CACHE_STORE=database
SESSION_DRIVER=database
```

---

## جدول التوقيتات السريع / Quick Schedule Reference

| الوقت | المهمة |
|-------|--------|
| كل دقيقة | queue:work, schedule:run |
| كل ساعة | cache:prune-stale-tags |
| 00:30 | rental:generate-recurring |
| 01:30 (يوم 1 شهرياً) | hrm:payroll |
| 02:00 | system:backup |
| 07:00 | stock:check-low |
| 08:00 | reports:send-scheduled, reports:run-scheduled |
| 23:55 | pos:close-day |

---

## الدعم / Support

في حالة وجود مشاكل، تواصل مع فريق الدعم الفني.
For any issues, contact technical support.
