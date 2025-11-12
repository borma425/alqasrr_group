# دليل إعداد Archives - Archive Setup Guide

## ✅ النظام الجديد - منظم وواضح

كل archive يمكن تحديد عدد العناصر بشكل مباشر في ملف واحد منظم.

---

## 📝 كيفية الاستخدام

### 1. تعديل عدد العناصر في `inc/archive-pagination.php`

افتح ملف `inc/archive-pagination.php` وعدّل الـ array:

```php
$archive_posts_per_page = array(
    'blog' => 2,        // Blog archive: 2 posts per page
    'projects' => 9,    // Projects archive: 9 posts per page
    'jobs' => 9,        // Jobs archive: 9 posts per page
);
```

### 2. مثال: تغيير عدد عناصر Blog إلى 9

```php
// في inc/archive-pagination.php
$archive_posts_per_page = array(
    'blog' => 9,        // تغيير من 2 إلى 9
    'projects' => 12,   // تغيير من 9 إلى 12
    'jobs' => 9,
);
```

### 3. إضافة Archive جديد

عند إضافة archive جديد (مثل `archive-products.php`):

1. أضف الإعداد في `inc/archive-pagination.php`:
```php
$archive_posts_per_page = array(
    'blog' => 2,
    'projects' => 9,
    'jobs' => 9,
    'products' => 12,  // ← أضف هذا السطر
);
```

2. أنشئ ملف `archive-products.php`:
```php
<?php
use Timber\Timber;

$context = Timber::context();
$posts = Timber::get_posts();
// باقي الكود...
```

---

## 🎯 المميزات

### ✅ منظم وواضح
- جميع الإعدادات في ملف واحد (`inc/archive-pagination.php`)
- سهل التعديل والإدارة
- الكود واضح وسهل الفهم

### ✅ مرن
- يمكن تغيير الرقم بسهولة في ملف واحد
- كل archive مستقل
- Default value: 9 (إذا لم يتم التحديد)

### ✅ نظيف
- لا يوجد spaghetti code
- لا يوجد filters معقدة
- نظام بسيط ومنظم
- جميع الإعدادات في مكان واحد

---

## 📊 الإعدادات الحالية

| Archive File | Post Type | Posts Per Page |
|---|---|---|
| `archive-blog.php` | `blog` | 2 |
| `archive-projects.php` | `projects` | 9 (default) |
| `archive-jobs.php` | `jobs` | 9 (default) |

---

## 🔧 كيفية التغيير

### لتغيير عدد العناصر في `archive-blog.php`:

```php
// في inc/archive-pagination.php
$archive_posts_per_page = array(
    'blog' => 9,        // غيّر من 2 إلى 9
    'projects' => 9,
    'jobs' => 9,
);
```

### لإضافة archive جديد:

1. أضف الإعداد في `inc/archive-pagination.php`:
```php
$archive_posts_per_page = array(
    'blog' => 2,
    'projects' => 9,
    'jobs' => 9,
    'new_post_type' => 12,  // ← أضف هذا السطر
);
```

2. أنشئ ملف `archive-{post_type}.php` (اختياري إذا لم يكن موجوداً)

---

## 💡 ملاحظات

1. **الترتيب مهم**: يجب استدعاء `set_archive_posts_per_page()` قبل `Timber::context()`
2. **Default value**: إذا لم يتم تحديد عدد، القيمة الافتراضية هي 9
3. **Performance**: النظام محسّن ولا يؤثر على الأداء

---

## 📝 مثال كامل

### ملف `inc/archive-pagination.php`:
```php
$archive_posts_per_page = array(
    'blog' => 2,        // Blog: 2 posts per page
    'projects' => 9,    // Projects: 9 posts per page
    'jobs' => 9,        // Jobs: 9 posts per page
);
```

### ملف `archive-blog.php`:
```php
<?php

use Timber\Timber;

/**
 * Archive: Blog
 * أرشيف: المدونة
 * 
 * Posts per page is set in inc/archive-pagination.php
 * عدد المقالات لكل صفحة يتم تعيينه في inc/archive-pagination.php
 */

$context = Timber::context();

// Get posts using Timber (uses main query)
$posts = Timber::get_posts();

// Process posts...
foreach ($posts as $post) {
    // Your processing code...
}

$context['posts'] = $posts;

// Get pagination
if (method_exists($posts, 'pagination')) {
    $pagination = $posts->pagination();
    $context['pagination'] = [
        'pages' => $pagination->pages ?? [],
        'prev' => !empty($pagination->prev) ? $pagination->prev : null,
        'next' => !empty($pagination->next) ? $pagination->next : null,
        'current' => $pagination->current ?? 1,
        'total' => $pagination->total ?? 0
    ];
}

// Render template
$template = get_language_template('archive-blog.twig');
Timber::render($template, $context);
```

---

## ✅ الخلاصة

النظام الجديد:
- ✅ منظم وواضح - جميع الإعدادات في ملف واحد
- ✅ سهل الاستخدام - فقط عدّل الـ array
- ✅ مرن وقابل للتخصيص - كل archive مستقل
- ✅ لا يوجد spaghetti code - نظام بسيط ونظيف
- ✅ سهل الصيانة - كل شيء في مكان واحد

## 📍 الموقع

جميع الإعدادات في ملف واحد:
- **`inc/archive-pagination.php`** - عدّل الـ array هنا

**استمتع بالكود النظيف والمنظم!** 🎉

