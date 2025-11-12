borma425

## Tailwind CSS Setup

### التثبيت
تم تثبيت Tailwind CSS بنجاح مع أحدث إصدار (v4.1.16).

### الأوامر المتاحة

```bash
# بناء ملف CSS محسّن (للإنتاج)
npm run build

# بناء ملف CSS بدون minify (للاختبار)
npm run build:dev

# وضع التطوير مع watch mode (يراقب التغييرات تلقائياً)
npm run dev
```

### الملفات

- `src/input.css` - ملف CSS الرئيسي الذي يحتوي على `@import "tailwindcss";`
- `tailwind.config.js` - ملف إعدادات Tailwind CSS (للتخصيصات الإضافية)
- `assets/css/tailwind.css` - ملف CSS المُنشأ (يتم إنشاؤه تلقائياً)

### الطريقة المستخدمة

يستخدم المشروع الطريقة الرسمية لـ Tailwind CSS v4:
- `@import "tailwindcss";` في ملف CSS الرئيسي
- `npx @tailwindcss/cli` للأوامر

### الاستخدام

بعد بناء ملف CSS، يمكنك إضافة `tailwind.css` إلى ملف `enqueues.php` أو استخدامه في القوالب.

### ملاحظات

- ملف `tailwind.css` مضاف إلى `.gitignore` لأنه يُنشأ تلقائياً
- إعدادات Tailwind تتضمن الألوان والخطوط المخصصة من المشروع
- يتم مسح الملفات التلقائي (content) من مجلدات `views`, `*.php`, و `assets/js`
# alqasrr_group
