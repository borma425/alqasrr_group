# الفرق الحقيقي بين استخدام Arguments وعدم استخدامها

## ✅ صحيح: Timber مجرد Wrapper

Timber **ليس له علاقة مباشرة بالروابط**. Timber مجرد wrapper حول `WP_Query`.

## 🔍 الفرق الحقيقي

### 1. Timber::get_posts() بدون arguments

```php
// في archive-blog.php
$posts = Timber::get_posts(); // بدون arguments
```

**ماذا يحدث داخلياً:**
```php
// في vendor/timber/timber/src/Timber.php (السطر 489)
global $wp_query;
return $factory->from($query ?: $wp_query); // يستخدم global $wp_query
```

**كيف يعمل Pagination:**
```php
// في vendor/timber/timber/src/Pagination.php (السطر 67)
$url = \explode('?', (string) \get_pagenum_link(0, false));
```

`get_pagenum_link()` يقرأ من `global $wp_query`:
- WordPress قام بتحليل URL (`/blog/page/2/`)
- WordPress وضع البيانات في `$wp_query->query_vars`
- `get_pagenum_link()` يقرأ من `$wp_query` → الروابط صحيحة ✅

---

### 2. Timber::get_posts() مع arguments

```php
// في archive-blog.php
$paged = get_query_var('paged') ? get_query_var('paged') : 1;
$posts = Timber::get_posts([
    'post_type' => 'blog',
    'posts_per_page' => 2,
    'paged' => $paged
]);
```

**ماذا يحدث داخلياً:**
```php
// في vendor/timber/timber/src/Factory/PostFactory.php (السطر 45)
if (\is_array($params)) {
    return $this->from_wp_query(new WP_Query($params)); // ينشئ WP_Query جديد
}
```

**المشكلة:**
```php
// في vendor/timber/timber/src/Pagination.php (السطر 67)
$url = \explode('?', (string) \get_pagenum_link(0, false));
```

`get_pagenum_link()` **لا يزال يقرأ من `global $wp_query`** وليس من الـ query الجديد!

**النتيجة:**
- أنت أنشأت `WP_Query` جديد مع `post_type = 'blog'`, `paged = 2`
- لكن `get_pagenum_link()` يقرأ من `global $wp_query`
- إذا كان `$wp_query` يحتوي على بيانات مختلفة → الروابط خاطئة ❌

---

## 🔑 النقطة المهمة

**المشكلة ليست في Timber، بل في `get_pagenum_link()`:**

```php
// WordPress function
function get_pagenum_link($pagenum = 1, $escape = true) {
    global $wp_rewrite, $wp_query; // ← يقرأ من global $wp_query!
    
    // ... يقوم بإنشاء الروابط بناءً على $wp_query
}
```

`get_pagenum_link()` **دائماً يقرأ من `global $wp_query`** وليس من الـ query الممرر.

---

## 📊 المقارنة

| | بدون Arguments | مع Arguments |
|---|---|---|
| **Query المستخدم** | `global $wp_query` (main query) | `new WP_Query()` (custom query) |
| **WordPress يعرف URL?** | ✅ نعم (قام بتحليل URL) | ❌ لا (أنشأنا query جديد) |
| **get_pagenum_link()** | يقرأ من `$wp_query` ✅ | يقرأ من `$wp_query` ❌ (ليس من query الجديد) |
| **الروابط** | صحيحة ✅ | قد تكون خاطئة ❌ |

---

## 🎯 الخلاصة

### ✅ لماذا نستخدم `Timber::get_posts()` بدون arguments؟

1. **WordPress يعرف URL structure:**
   - WordPress قام بتحليل `/blog/page/2/`
   - WordPress وضع البيانات في `$wp_query->query_vars`

2. **Pagination يقرأ من `$wp_query`:**
   - `get_pagenum_link()` يقرأ من `global $wp_query`
   - لذلك الروابط صحيحة

3. **pre_get_posts يعمل:**
   - `pre_get_posts` يعدل `$wp_query`
   - `Timber::get_posts()` يستخدم `$wp_query` المعدل

### ❌ لماذا لا نستخدم arguments؟

1. **WordPress لا يعرف عن الـ query الجديد:**
   - أنشأنا `WP_Query` جديد
   - WordPress لا يعرف أنه مرتبط بالـ URL

2. **Pagination لا يزال يقرأ من `$wp_query`:**
   - `get_pagenum_link()` يقرأ من `global $wp_query`
   - ليس من الـ query الجديد
   - لذلك الروابط قد تكون خاطئة

---

## 🔧 الحل البديل (إذا أردت استخدام arguments)

إذا أردت استخدام arguments، يجب تمرير `WP_Query` إلى Pagination:

```php
// في archive-blog.php
$paged = get_query_var('paged') ? get_query_var('paged') : 1;

// إنشاء WP_Query جديد
$custom_query = new WP_Query([
    'post_type' => 'blog',
    'posts_per_page' => 2,
    'paged' => $paged
]);

// استخدام WP_Query في Timber
$posts = Timber::get_posts($custom_query);

// Pagination يجب أن يستخدم custom_query
if (method_exists($posts, 'pagination')) {
    // لكن Pagination لا يزال يستخدم global $wp_query!
    // لذلك الروابط قد تكون خاطئة
    $pagination = $posts->pagination();
}
```

**لكن هذا لا يحل المشكلة** لأن `get_pagenum_link()` لا يزال يقرأ من `global $wp_query`.

---

## ✅ التوصية

**استخدم `Timber::get_posts()` بدون arguments للأرشيفات** لأن:
1. WordPress يعرف URL structure
2. Pagination يعمل بشكل صحيح
3. pre_get_posts يعمل
4. أبسط وأكثر تنظيماً

**استخدم arguments فقط للـ custom queries** (مثل: latest posts widget) حيث لا تحتاج pagination links صحيحة.

---

## 📝 ملاحظة مهمة

**Timber مجرد wrapper** - الفرق الحقيقي هو:
- `global $wp_query` = WordPress يعرف URL structure
- `new WP_Query()` = WordPress لا يعرف URL structure
- `get_pagenum_link()` = يقرأ دائماً من `global $wp_query`

**لذلك:** استخدام `global $wp_query` أفضل للأرشيفات! ✅

