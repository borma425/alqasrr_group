<?php

/**
 * Language Routing System
 * Handles URL-based language switching and template selection
 * Security: Protected against path traversal, XSS, and injection attacks
 */

// Define language constants
define('DEFAULT_LANG', 'ar');
define('ENGLISH_LANG', 'en');

/**
 * Get current language from URL
 */
function get_current_language() {
    if (!isset($_SERVER['REQUEST_URI'])) {
        return DEFAULT_LANG;
    }
    
    $path = $_SERVER['REQUEST_URI'];
    $parsed_path = parse_url($path, PHP_URL_PATH);
    if ($parsed_path !== null) {
        $path = $parsed_path;
    }
    
    // Check for lang parameter first (from .htaccess rewrite or query string)
    if (isset($_GET['lang']) && $_GET['lang'] === 'en') {
        return ENGLISH_LANG;
    }
    
    // Check if URL contains /en/ anywhere in the path
    if (preg_match('#/en(/|$)#', $path)) {
        return ENGLISH_LANG;
    }
    return DEFAULT_LANG;
}

/**
 * Check if current request is for English version
 */
function is_english_version() {
    return get_current_language() === ENGLISH_LANG;
}

/**
 * Get language-specific template path
 */
function get_language_template($template) {
    if (!function_exists('get_current_language')) {
        return $template;
    }
    
    $lang = get_current_language();
    
    if ($lang === ENGLISH_LANG) {
        // For English, look in /en/ folder
        $en_template = 'en/' . $template;
        
        // Check if English template exists by checking if file exists
        $template_path = get_template_directory() . '/views/' . $en_template;
        if (file_exists($template_path)) {
            return $en_template;
        }
    } else {
        // For Arabic, look in /ar/ folder
        $ar_template = 'ar/' . $template;
        
        // Check if Arabic template exists by checking if file exists
        $template_path = get_template_directory() . '/views/' . $ar_template;
        if (file_exists($template_path)) {
            return $ar_template;
        }
    }
    return $template;
}

/**
 * Add language context to Timber
 */
function add_language_context($context) {
    $context['current_language'] = get_current_language();
    $context['is_english'] = is_english_version();
    $context['is_arabic'] = !is_english_version();
    $context['language_prefix'] = is_english_version() ? '/en' : '';
    
    return $context;
}

/**
 * Handle language detection and cookie setting
 */
function handle_language_routing() {
    $path = $_SERVER['REQUEST_URI'] ?? '';
    $parsed_path = parse_url($path, PHP_URL_PATH);
    if ($parsed_path !== null) {
        $path = $parsed_path;
    }
    
    // Check for lang parameter first
    if (isset($_GET['lang']) && $_GET['lang'] === 'en') {
        setcookie('site_language', ENGLISH_LANG, time() + (86400 * 30), '/');
        return;
    }
    
    // Check if URL contains /en/ for English
    if (preg_match('#/en(/|$)#', $path)) {
        setcookie('site_language', ENGLISH_LANG, time() + (86400 * 30), '/');
    } else {
        setcookie('site_language', DEFAULT_LANG, time() + (86400 * 30), '/');
    }
}

/**
 * Generate language-specific URLs
 */
function get_language_url($url = '', $lang = null) {
    if ($lang === null) {
        $lang = get_current_language();
    }
    
    if ($lang === ENGLISH_LANG) {
        return home_url('/en' . $url);
    }
    
    return home_url($url);
}

/**
 * Add language switcher links
 */
function get_language_switcher_links() {
    $current_url = $_SERVER['REQUEST_URI'] ?? '/';
    $current_lang = get_current_language();
    
    $links = [];
    
    // Arabic link
    if ($current_lang === ENGLISH_LANG) {
        // Remove /en/ prefix for Arabic version (works with any path)
        $ar_url = preg_replace('#/en(/|$)#', '/', $current_url);
    } else {
        $ar_url = $current_url;
    }
    
    $links['ar'] = [
        'url' => home_url($ar_url),
        'active' => $current_lang === DEFAULT_LANG,
        'name' => 'العربية'
    ];
    
    // English link
    if ($current_lang === DEFAULT_LANG) {
        // Add /en/ prefix for English version (works with any path)
        $en_url = preg_replace('#(/|$)#', '/en$1', $current_url);
    } else {
        $en_url = $current_url;
    }
    
    $links['en'] = [
        'url' => home_url($en_url),
        'active' => $current_lang === ENGLISH_LANG,
        'name' => 'English'
    ];
    
    return $links;
}

// Hook into WordPress early for language detection
add_action('init', 'handle_language_routing', 1);

// Disable WordPress redirects for language URLs
add_action('init', function() {
    $path = $_SERVER['REQUEST_URI'] ?? '';
    $parsed_path = parse_url($path, PHP_URL_PATH);
    if ($parsed_path !== null) {
        $path = $parsed_path;
    }
    
    if (preg_match('#/en(/|$)#', $path)) {
        // Remove WordPress redirect filters for language URLs
        remove_filter('template_redirect', 'redirect_canonical');
        remove_action('template_redirect', 'wp_redirect_admin_locations', 1000);
    }
}, 2);

// Prevent WordPress from redirecting language URLs
add_action('template_redirect', function() {
    $path = $_SERVER['REQUEST_URI'] ?? '';
    
    // If this is a language URL, don't redirect
    if (preg_match('#/en(/|$)#', $path)) {
        return;
    }
    
    // Force taxonomy template for project-type URLs
    // فرض استخدام قالب taxonomy لروابط project-type
    if (preg_match('#/project-type/#', $path)) {
        $taxonomy_template = get_template_directory() . '/taxonomy-project_type.php';
        if (file_exists($taxonomy_template)) {
            // Set query vars to help WordPress recognize this as taxonomy archive
            global $wp_query;
            $path_parts = explode('/', trim(parse_url($path, PHP_URL_PATH), '/'));
            $project_type_index = array_search('project-type', $path_parts);
            if ($project_type_index !== false && isset($path_parts[$project_type_index + 1])) {
                $term_slug_raw = $path_parts[$project_type_index + 1];
                $term_slug = urldecode($term_slug_raw);
                if (strpos($term_slug, '%') !== false) {
                    $term_slug = urldecode($term_slug);
                }
                $term = get_term_by('slug', $term_slug, 'project_type');
                if (!$term || is_wp_error($term)) {
                    $term = get_term_by('slug', sanitize_title($term_slug), 'project_type');
                }
                if ($term && !is_wp_error($term)) {
                    $wp_query->set('taxonomy', 'project_type');
                    $wp_query->set('term', $term->slug);
                    $wp_query->is_tax = true;
                    $wp_query->is_archive = true;
                }
            }
        }
    }
}, 1);

// Disable canonical redirects for language URLs
add_filter('redirect_canonical', function($redirect_url, $requested_url) {
    $path = $_SERVER['REQUEST_URI'] ?? '';
    
    if (preg_match('#/en(/|$)#', $path)) {
        return false;
    }
    
    return $redirect_url;
}, 10, 2);

// Add custom rewrite rules for language URLs
// Use priority 20 to ensure CPTs are registered first (CPTs register at priority 10)
// استخدام أولوية 20 للتأكد من تسجيل CPTs أولاً (CPTs تسجل في أولوية 10)
add_action('init', 'add_language_rewrite_rules', 20);

// Archive pagination is now handled in inc/archive-pagination.php
// معالجة pagination للأرشيفات الآن في inc/archive-pagination.php

// Register query variables
add_filter('query_vars', 'add_language_query_vars');

/**
 * Add custom rewrite rules for language URLs
 */
function add_language_rewrite_rules() {
    // Add rewrite rule for /en/ URLs (home page)
    add_rewrite_rule(
        '^en/?$',
        'index.php?lang=en',
        'top'
    );
    
    // Get all registered post types that have archives
    // الحصول على جميع أنواع المقالات المسجلة التي لديها أرشيف
    $post_types = get_post_types(array(
        'public' => true,
        'has_archive' => true
    ), 'objects');
    
    // Add rewrite rules for each CPT archive with pagination (English only)
    // إضافة قواعد rewrite لكل أرشيف CPT مع pagination (للإنجليزية فقط)
    // Note: WordPress handles Arabic URLs like /{cpt}/page/X/ automatically
    // ملاحظة: WordPress يتعامل مع روابط العربية مثل /{cpt}/page/X/ تلقائياً
    foreach ($post_types as $post_type) {
        $slug = $post_type->rewrite['slug'] ?? $post_type->name;
        
        // Add rewrite rule for /en/{cpt}/page/X/ (CPT archive with pagination) - English
        // إضافة قاعدة rewrite لـ /en/{cpt}/page/X/ (أرشيف CPT مع pagination) - للإنجليزية
        add_rewrite_rule(
            '^en/' . preg_quote($slug, '/') . '/page/([0-9]+)/?$',
            'index.php?lang=en&post_type=' . $post_type->name . '&paged=$matches[1]',
            'top'
        );
        
        // Add rewrite rule for /en/{cpt}/ (CPT archive without pagination) - English
        // إضافة قاعدة rewrite لـ /en/{cpt}/ (أرشيف CPT بدون pagination) - للإنجليزية
        add_rewrite_rule(
            '^en/' . preg_quote($slug, '/') . '/?$',
            'index.php?lang=en&post_type=' . $post_type->name,
            'top'
        );

        // Add rewrite rule for /en/{cpt}/{single}/ (single posts)
        add_rewrite_rule(
            '^en/' . preg_quote($slug, '/') . '/([^/]+)/?$',
            'index.php?lang=en&post_type=' . $post_type->name . '&name=$matches[1]',
            'top'
        );
    }

    // Add rewrite rule for /en/project-type/{term}/ (taxonomy archive with pagination)
    add_rewrite_rule(
        '^en/project-type/([^/]+)/?$',
        'index.php?lang=en&taxonomy=project_type&term=$matches[1]',
        'top'
    );
    add_rewrite_rule(
        '^en/project-type/([^/]+)/page/([0-9]+)/?$',
        'index.php?lang=en&taxonomy=project_type&term=$matches[1]&paged=$matches[2]',
        'top'
    );

    // Build list of CPT slugs to exclude from general /en/ rules
    // بناء قائمة بـ CPT slugs لاستثناءها من قواعد /en/ العامة
    $cpt_slugs = array();
    foreach ($post_types as $post_type) {
        $slug = $post_type->rewrite['slug'] ?? $post_type->name;
        $cpt_slugs[] = $slug;
    }
    $cpt_slugs_pattern = implode('|', array_map('preg_quote', $cpt_slugs));
    
    // Add rewrite rule for /en/ with additional path (posts/pages)
    // BUT exclude CPT archives (they are handled above)
    // لكن استثناء أرشيفات CPT (يتم التعامل معها أعلاه)
    // Only match if it's NOT a CPT archive
    // المطابقة فقط إذا لم يكن أرشيف CPT
    if (!empty($cpt_slugs_pattern)) {
        add_rewrite_rule(
            '^en/(?!(' . $cpt_slugs_pattern . ')(/|$))([^/]+)/?$',
            'index.php?lang=en&pagename=$matches[3]',
            'bottom' // Lower priority so CPT rules take precedence
        );
        
        // Add rewrite rule for /en/ with nested paths
        // BUT exclude CPT archives with pagination (they are handled above)
        // لكن استثناء أرشيفات CPT مع pagination (يتم التعامل معها أعلاه)
        add_rewrite_rule(
            '^en/(?!(' . $cpt_slugs_pattern . ')/page/)([^/]+)/(.*)$',
            'index.php?lang=en&pagename=$matches[2]/$matches[3]',
            'bottom' // Lower priority so CPT rules take precedence
        );
    } else {
        // Fallback if no CPTs found
        // احتياطي إذا لم يتم العثور على CPTs
        add_rewrite_rule(
            '^en/([^/]+)/?$',
            'index.php?lang=en&pagename=$matches[1]',
            'bottom'
        );
        
        add_rewrite_rule(
            '^en/([^/]+)/(.*)$',
            'index.php?lang=en&pagename=$matches[1]/$matches[2]',
            'bottom'
        );
    }
    
    // Note: Rewrite rules should be flushed manually via WordPress Admin
    // Settings → Permalinks → Save Changes
    // ملاحظة: يجب إعادة حفظ Permalinks يدوياً عبر WordPress Admin
    // Settings → Permalinks → Save Changes
}

/**
 * Register custom query variables
 */
function add_language_query_vars($vars) {
    $vars[] = 'lang';
    $vars[] = 'name';
    $vars[] = 'pagename';
    $vars[] = 'post_type';
    $vars[] = 'paged';
    $vars[] = 'is_archive';
    return $vars;
}

/**
 * Check if a post exists by slug and post type
 */
function check_post_exists($slug, $post_type = 'post') {
    $post = get_posts(array(
        'name' => $slug,
        'post_type' => $post_type,
        'post_status' => 'publish',
        'posts_per_page' => 1
    ));
    
    return !empty($post);
}

/**
 * Handle language URL parsing for English URLs only
 * معالجة تحليل URL للغة الإنجليزية فقط
 * Note: Arabic URLs are handled automatically by WordPress
 * ملاحظة: روابط العربية يتم التعامل معها تلقائياً من WordPress
 */
add_filter('request', function($query_vars) {
    $path = $_SERVER['REQUEST_URI'] ?? '';
    $parsed_path = parse_url($path, PHP_URL_PATH);
    if ($parsed_path !== null) {
        $path = $parsed_path;
    }
    
    // Only handle English URLs - Early return for Arabic pages (performance optimization)
    // التعامل مع روابط الإنجليزية فقط - إرجاع مبكر للصفحات العربية (تحسين الأداء)
    if (!preg_match('#/en(/|$)#', $path)) {
        return $query_vars; // ← Early return - no processing for Arabic pages
    }
    
    // Extract path parts first (before getting post types - performance optimization)
    // استخراج أجزاء المسار أولاً (قبل الحصول على post types - تحسين الأداء)
    $path_parts = array_filter(explode('/', trim($path, '/')));
    $path_parts = array_values($path_parts);
    
    if (empty($path_parts)) {
        return $query_vars; // ← Early return - empty path
    }
    
    // Get all registered post types that have archives (only if needed)
    // الحصول على جميع أنواع المقالات المسجلة التي لديها أرشيف (فقط عند الحاجة)
    $post_types = get_post_types(array(
        'public' => true,
        'has_archive' => true
    ), 'objects');
    
    // Handle English URLs: /en/{cpt}/page/2/
    // التعامل مع روابط الإنجليزية: /en/{cpt}/page/2/
    $en_index = array_search('en', $path_parts);
    
    if ($en_index !== false && isset($path_parts[$en_index + 1])) {
        $next_part = $path_parts[$en_index + 1];
        
        // Handle taxonomy archive: /en/project-type/{term}/
        if ($next_part === 'project-type' && isset($path_parts[$en_index + 2])) {
            $encoded_term = $path_parts[$en_index + 2];
            $term_slug = sanitize_text_field(rawurldecode($encoded_term));

            $query_vars['lang'] = 'en';
            $query_vars['taxonomy'] = 'project_type';
            $query_vars['term'] = $term_slug;
            $query_vars['project_type'] = $term_slug;

            // Handle pagination: /en/project-type/{term}/page/{n}/
            if (isset($path_parts[$en_index + 3]) && $path_parts[$en_index + 3] === 'page' && isset($path_parts[$en_index + 4])) {
                $query_vars['paged'] = (int) $path_parts[$en_index + 4];
            }

            $query_vars['is_tax'] = true;
            $query_vars['is_archive'] = true;
            $query_vars['error'] = null;
            unset($query_vars['is_404']);

            return $query_vars;
        }

        // Check if this is a CPT archive (for any CPT, not just blog)
        // التحقق من أن هذا أرشيف CPT (لأي CPT، وليس فقط blog)
        foreach ($post_types as $post_type) {
            $slug = $post_type->rewrite['slug'] ?? $post_type->name;
            
            // Check if the URL matches this CPT's slug
            // التحقق من أن URL يطابق slug هذا CPT
            if ($next_part === $slug) {
                $query_vars['lang'] = 'en';
                $query_vars['post_type'] = $post_type->name;

                // Check if this is a single post: /en/{cpt}/{slug}/
                if (isset($path_parts[$en_index + 2]) && $path_parts[$en_index + 2] !== 'page') {
                    $single_slug_raw = $path_parts[$en_index + 2];
                    $single_slug = sanitize_text_field(rawurldecode($single_slug_raw));

                    $query_vars['name'] = $single_slug;
                    $query_vars[$post_type->name] = $single_slug;
                    $query_vars['is_single'] = true;
                    $query_vars['is_singular'] = true;
                    $query_vars['is_archive'] = false;
                    unset($query_vars['is_post_type_archive']);
                    $query_vars['error'] = null;
                    unset($query_vars['is_404']);

                    return $query_vars;
                }

                // Check if there's pagination: /en/{cpt}/page/2/
                if (isset($path_parts[$en_index + 2]) && $path_parts[$en_index + 2] === 'page' && isset($path_parts[$en_index + 3])) {
                    $query_vars['paged'] = (int) $path_parts[$en_index + 3];
                }
                
                $query_vars['is_archive'] = true;
                $query_vars['is_post_type_archive'] = true;
                $query_vars['error'] = null;
                unset($query_vars['is_404']);
                
                return $query_vars;
            }
        }
    }
    
    // Continue with existing logic for /en/ pages (non-archive pages)
    // الاستمرار مع المنطق الموجود لصفحات /en/ (صفحات غير أرشيف)
    if (preg_match('#/en(/|$)#', $path)) {
        $en_index = array_search('en', $path_parts);
        
        if ($en_index !== false && isset($path_parts[$en_index + 1])) {
            $next_part_raw = $path_parts[$en_index + 1];
            $next_part = explode('?', $next_part_raw)[0];
            
            // Check if this is a page (existing logic)
            // التحقق من أن هذه صفحة (المنطق الموجود)
            $page_exists = check_post_exists($next_part, 'page');
            if ($page_exists) {
                // Set the correct query vars for pages
                $query_vars['lang'] = 'en';
                $query_vars['page_id'] = null;
                $query_vars['pagename'] = $next_part;
                $query_vars['post_type'] = 'page';
                
                // Force WordPress to treat this as a page
                $query_vars['is_page'] = true;
                $query_vars['is_single'] = false;
                $query_vars['is_home'] = false;
            } else {
                // Special handling for Contact_us page (case insensitive)
                if (strtolower($next_part) === 'contact_us') {
                    $query_vars['lang'] = 'en';
                    $query_vars['pagename'] = 'contact_us';
                    $query_vars['post_type'] = 'page';
                    $query_vars['is_page'] = true;
                    $query_vars['is_single'] = false;
                    $query_vars['is_home'] = false;
                } else {
                    // Page doesn't exist, return 404
                    $query_vars['error'] = '404';
                    $query_vars['is_404'] = true;
                }
            }
        }
    }
    
    return $query_vars;
});

// Template redirect is no longer needed - WordPress handles Arabic URLs automatically
// template_redirect لم يعد ضرورياً - WordPress يتعامل مع روابط العربية تلقائياً

// Add language context to Timber
add_filter('timber/context', 'add_language_context');

// Auto-select language-specific templates
add_filter('timber/template', 'select_language_template', 10, 2);

// Handle template selection for language URLs
add_filter('template_include', function($template) {
    global $wp_query;
    $path = $_SERVER['REQUEST_URI'] ?? '';
    
    // PRIORITY 1: Check URL pattern for project-type taxonomy FIRST (before anything else)
    // الأولوية 1: التحقق من نمط URL لـ taxonomy project-type أولاً (قبل أي شيء آخر)
    if (preg_match('#/project-type/#', $path)) {
        // Use locate_template to find the taxonomy template (WordPress way)
        $taxonomy_template = locate_template(['taxonomy-project_type.php']);
        if (!empty($taxonomy_template)) {
            // Check if WordPress already recognizes this as a taxonomy archive
            $queried_object = get_queried_object();
            if ($queried_object instanceof WP_Term && $queried_object->taxonomy === 'project_type') {
                return $taxonomy_template;
            }
            // If queried object is not set yet, try to get it from the URL
            $path_parts = explode('/', trim(parse_url($path, PHP_URL_PATH), '/'));
            $project_type_index = array_search('project-type', $path_parts);
            if ($project_type_index !== false && isset($path_parts[$project_type_index + 1])) {
                $term_slug_raw = $path_parts[$project_type_index + 1];
                // Decode URL-encoded term slug (handle double encoding)
                $term_slug = urldecode($term_slug_raw);
                if (strpos($term_slug, '%') !== false) {
                    $term_slug = urldecode($term_slug);
                }
                // Try to find term by slug
                $term = get_term_by('slug', $term_slug, 'project_type');
                if ($term && !is_wp_error($term)) {
                    return $taxonomy_template;
                }
                // If not found, try with sanitized slug
                $term = get_term_by('slug', sanitize_title($term_slug), 'project_type');
                if ($term && !is_wp_error($term)) {
                    return $taxonomy_template;
                }
                // If still not found, try to find by name (for Arabic terms)
                $all_terms = get_terms([
                    'taxonomy' => 'project_type',
                    'hide_empty' => false,
                ]);
                if (!is_wp_error($all_terms)) {
                    foreach ($all_terms as $t) {
                        if (urldecode($t->slug) === $term_slug || $t->slug === $term_slug) {
                            return $taxonomy_template;
                        }
                    }
                }
            }
            // If we found project-type in URL, use the template anyway
            return $taxonomy_template;
        }
    }
    
    // PRIORITY 2: Check if this is a taxonomy archive (e.g., project_type)
    // الأولوية 2: التحقق من أن هذه صفحة أرشيف taxonomy
    if (is_tax()) {
        $queried_object = get_queried_object();
        if ($queried_object instanceof WP_Term) {
            $taxonomy = $queried_object->taxonomy;
            $taxonomy_template = get_template_directory() . '/taxonomy-' . $taxonomy . '.php';
            if (file_exists($taxonomy_template)) {
                return $taxonomy_template;
            }
        }
        return $template;
    }

    // Check if this is a post type archive (blog, projects, etc.)
    // التحقق من أن هذه صفحة أرشيف لنوع المقالات (blog, projects, إلخ)
    $post_type = $wp_query->get('post_type');
    if (!empty($post_type) && is_post_type_archive($post_type)) {
        // WordPress will automatically use archive-{post_type}.php template
        // WordPress سيستخدم تلقائياً قالب archive-{post_type}.php
        return $template;
    }
    
    // Check if this is a 404 error
    if (is_404()) {
        // Use 404.php template
        $template_404 = get_template_directory() . '/404.php';
        if (file_exists($template_404)) {
            return $template_404;
        }
    }

    if (preg_match('#/en(/|$)#', $path)) {
        $page_name = '';

        // Try to use query var first
        $pagename_query = $wp_query->get('pagename');
        if (!empty($pagename_query)) {
            $page_name = $pagename_query;
        } else {
            $path_parts = explode('/', trim($path, '/'));
            $en_index = array_search('en', $path_parts);
            if ($en_index !== false && isset($path_parts[$en_index + 1])) {
                $page_name_raw = $path_parts[$en_index + 1];
                $page_name = explode('?', $page_name_raw)[0];
            }
        }

        if (empty($page_name)) {
            return $template;
        }

        $page_slug = sanitize_title($page_name);
        $page_exists = check_post_exists($page_slug, 'page');

        $template_candidates = [];
        $slug_variations = array_unique(array_filter([
            $page_slug,
            strtolower($page_name),
            str_replace('-', '_', strtolower($page_name)),
            str_replace('_', '-', strtolower($page_name)),
            $page_name,
        ]));

        foreach ($slug_variations as $candidate_slug) {
            $candidate_slug = trim($candidate_slug);
            if ($candidate_slug !== '') {
                $template_candidates[] = 'page-' . $candidate_slug . '.php';
            }
        }

        $template_candidates = array_values(array_unique($template_candidates));
        $located_template = '';
        if (!empty($template_candidates)) {
            $located_template = locate_template($template_candidates, false, false);
        }

        if (!$page_exists && empty($located_template)) {
            // Not an actual page slug and no dedicated template - fall back to WordPress
            return $template;
        }

        if (!empty($located_template)) {
            return $located_template;
        }

        $page_template = locate_template(['page.php'], false, false);
        if (!empty($page_template)) {
            return $page_template;
        }
    }
    
    // Skip template override for other singular content
    if (is_singular()) {
        return $template;
    }
    
    return $template;
}, 1); // Use priority 1 to run before other template filters

/**
 * Automatically select language-specific template
 */
function select_language_template($template, $context) {
    if (!function_exists('get_current_language')) {
        return $template;
    }
    
    $lang = get_current_language();
    

    
    if ($lang === ENGLISH_LANG) {
        // For English, look in /en/ folder
        $en_template = 'en/' . $template;
        
        // Check if English template exists by checking if file exists
        $template_path = get_template_directory() . '/views/' . $en_template;
        if (file_exists($template_path)) {
            return $en_template;
        }
    } else {
        // For Arabic, look in /ar/ folder
        $ar_template = 'ar/' . $template;
        
        // Check if Arabic template exists by checking if file exists
        $template_path = get_template_directory() . '/views/' . $ar_template;
        if (file_exists($template_path)) {
            return $ar_template;
        }
    }
    return $template;
}

// Make functions available globally
if (!function_exists('get_language_url')) {
    function get_language_url($url = '', $lang = null) {
        return \get_language_url($url, $lang);
    }
}

if (!function_exists('get_language_switcher_links')) {
    function get_language_switcher_links() {
        return \get_language_switcher_links();
    }
}
