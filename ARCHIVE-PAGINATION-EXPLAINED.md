# شرح الفرق بين استخدام Arguments وعدم استخدامها في Timber::get_posts()

## الطريقة الحالية (بدون Arguments) ✅ **الأفضل للـ Archive Pages**

```php
// في archive-blog.php
$posts = Timber::get_posts(); // بدون arguments

// في inc/archive-pagination.php
add_action('pre_get_posts', 'set_cpt_archive_posts_per_page');
```

### المميزات:
1. ✅ **يعمل pagination بشكل تلقائي** - WordPress يعرف الـ URL structure
2. ✅ **يعمل مع rewrite rules** - `/blog/page/2/` يتم التعرف عليه تلقائياً
3. ✅ **SEO أفضل** - WordPress يعرف أن هذه archive page
4. ✅ **أبسط وأكثر تنظيماً** - الكود في مكان واحد (pre_get_posts)

### كيف يعمل:
- WordPress يقوم بتحليل URL (`/blog/page/2/`)
- `pre_get_posts` يقوم بتعديل main query
- `Timber::get_posts()` يستخدم main query المعدل
- Pagination يعمل تلقائياً لأن WordPress يعرف الـ URL structure

---

## الطريقة البديلة (مع Arguments) ⚠️ **قد تسبب مشاكل**

```php
// في archive-blog.php
$paged = get_query_var('paged') ? get_query_var('paged') : 1;
$posts = Timber::get_posts([
    'post_type' => 'blog',
    'posts_per_page' => 2,
    'paged' => $paged
]);
```

### المشاكل:
1. ❌ **Pagination قد لا يعمل بشكل صحيح** - WordPress قد لا يعرف أن هذا query مرتبط بالـ URL
2. ❌ **Rewrite rules قد لا تعمل** - `/blog/page/2/` قد لا يتم التعرف عليه
3. ❌ **404 errors** - WordPress قد يعتبر الصفحة غير موجودة
4. ❌ **SEO أسوأ** - WordPress قد لا يعرف أن هذه archive page

### متى نستخدمها:
- ✅ للـ custom queries (مثل: latest posts widget)
- ✅ للـ search results (مثل: search.php)
- ✅ عندما نريد query منفصل تماماً

---

## لماذا الطريقة الحالية أفضل؟

### 1. WordPress URL Structure
```
/blog/page/2/  ← WordPress يعرف هذا تلقائياً
```

عندما نستخدم `Timber::get_posts()` بدون arguments:
- WordPress يعرف أن `/blog/page/2/` هي archive page
- WordPress يعرف `paged = 2` من URL
- WordPress ينشئ rewrite rules بشكل صحيح

عندما نستخدم arguments:
- WordPress قد لا يعرف أن هذا query مرتبط بالـ URL
- Pagination links قد تكون خاطئة
- 404 errors قد تحدث

### 2. Pagination Links
عندما نستخدم main query:
```php
// Pagination links تكون صحيحة تلقائياً
/blog/page/1/
/blog/page/2/
/blog/page/3/
```

عندما نستخدم custom query:
```php
// Pagination links قد تكون خاطئة
/blog/en/page/2/  ← خطأ!
?paged=2  ← قد لا يعمل مع permalinks
```

### 3. Code Organization
مع `pre_get_posts`:
```php
// كل شيء في مكان واحد
inc/archive-pagination.php  ← إعدادات pagination لجميع CPTs
archive-blog.php  ← بسيط ونظيف
```

مع arguments:
```php
// الكود مكرر في كل archive file
archive-blog.php  ← arguments هنا
archive-projects.php  ← arguments هنا أيضاً
archive-jobs.php  ← arguments هنا أيضاً
```

---

## الخلاصة

### ✅ استخدم `Timber::get_posts()` بدون arguments عندما:
- لديك archive page
- تريد pagination يعمل بشكل صحيح
- تريد rewrite rules تعمل بشكل صحيح
- تريد SEO أفضل

### ⚠️ استخدم arguments عندما:
- لديك custom query (مثل: latest posts widget)
- لديك search results
- لديك query منفصل تماماً عن main query

---

## مثال: كيفية استخدام Arguments بشكل صحيح

إذا أردت استخدام arguments (غير موصى به للـ archives):

```php
// في archive-blog.php
$paged = get_query_var('paged') ? get_query_var('paged') : 1;

$posts = Timber::get_posts([
    'post_type' => 'blog',
    'posts_per_page' => 2,
    'paged' => $paged,
    'post_status' => 'publish'
]);

// يجب أيضاً تمرير pagination يدوياً
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
```

لكن هذا قد يسبب مشاكل مع:
- Rewrite rules
- 404 errors
- Pagination links

---

## التوصية النهائية

✅ **استمر في استخدام الطريقة الحالية** (بدون arguments) لأنها:
1. تعمل بشكل صحيح مع pagination
2. تعمل مع rewrite rules
3. أفضل للـ SEO
4. أبسط وأكثر تنظيماً

