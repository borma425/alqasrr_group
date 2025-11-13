<?php
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('WP_List_Table')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class AlQasrGroup_Job_Applications_Table extends WP_List_Table {
    public function __construct() {
        parent::__construct(
            array(
                'singular' => 'job_application',
                'plural'   => 'job_applications',
                'ajax'     => false,
            )
        );
    }

    public function get_columns() {
        return array(
            'cb'         => '<input type="checkbox" />',
            'applicant'  => __('المتقدم', 'AlQasrGroup'),
            'email'      => __('البريد الإلكتروني', 'AlQasrGroup'),
            'phone'      => __('رقم الهاتف', 'AlQasrGroup'),
            'job_title'  => __('الوظيفة', 'AlQasrGroup'),
            'submitted'  => __('تاريخ التقديم', 'AlQasrGroup'),
            'resume'     => __('السيرة الذاتية', 'AlQasrGroup'),
        );
    }

    public function prepare_items() {
        $columns  = $this->get_columns();
        $hidden   = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);

        $per_page     = 20;
        $current_page = $this->get_pagenum();
        $order        = isset($_REQUEST['order']) && 'asc' === strtolower(sanitize_text_field(wp_unslash($_REQUEST['order']))) ? 'ASC' : 'DESC';

        $query_args = array(
            'post_type'      => 'job_applications',
            'post_status'    => 'private',
            'posts_per_page' => $per_page,
            'paged'          => $current_page,
            'orderby'        => 'date',
            'order'          => $order,
        );

        $applications_query = new WP_Query($query_args);
        $items = array();

        if ($applications_query->have_posts()) {
            foreach ($applications_query->posts as $application_post) {
                $application_id = (int) $application_post->ID;
                $first_name = get_post_meta($application_id, 'job_application_first_name', true);
                $last_name  = get_post_meta($application_id, 'job_application_last_name', true);
                $email      = get_post_meta($application_id, 'job_application_email', true);
                $phone      = get_post_meta($application_id, 'job_application_phone', true);
                $job_id     = (int) get_post_meta($application_id, 'job_application_job_id', true);
                $job_title  = $job_id ? get_the_title($job_id) : __('غير محدد', 'AlQasrGroup');
                $submitted  = get_post_meta($application_id, 'job_application_submitted_at', true);
                if (empty($submitted)) {
                    $submitted = $application_post->post_date;
                }
                $cv_id  = (int) get_post_meta($application_id, 'job_application_cv_id', true);
                $cv_url = $cv_id ? wp_get_attachment_url($cv_id) : '';

                $items[] = array(
                    'ID'        => $application_id,
                    'applicant' => trim($first_name . ' ' . $last_name),
                    'email'     => $email,
                    'phone'     => $phone,
                    'job_title' => $job_title,
                    'submitted' => $submitted,
                    'resume'    => $cv_url,
                    'cv_id'     => $cv_id,
                );
            }
        }

        $this->items = $items;

        $this->set_pagination_args(
            array(
                'total_items' => (int) $applications_query->found_posts,
                'per_page'    => $per_page,
                'total_pages' => (int) ceil($applications_query->found_posts / $per_page),
            )
        );

        wp_reset_postdata();
    }

    protected function get_sortable_columns() {
        return array(
            'submitted' => array('submitted', true),
        );
    }

    protected function column_cb($item) {
        return sprintf('<input type="checkbox" name="application_ids[]" value="%d" />', (int) $item['ID']);
    }

    protected function column_applicant($item) {
        $application_id = (int) $item['ID'];
        $delete_url = wp_nonce_url(
            add_query_arg(
                array(
                    'page'            => 'AlQasrGroup-job-applications',
                    'action'          => 'delete',
                    'application_id'  => $application_id,
                ),
                admin_url('admin.php')
            ),
            'delete-job-application_' . $application_id
        );

        $actions = array(
            'delete' => sprintf('<a href="%s" class="submitdelete" onclick="return confirm(\'%s\');">%s</a>', esc_url($delete_url), esc_js(__('هل أنت متأكد من حذف هذا الطلب؟', 'AlQasrGroup')), __('حذف', 'AlQasrGroup')),
        );

        $name = !empty($item['applicant']) ? $item['applicant'] : __('بدون اسم', 'AlQasrGroup');

        return sprintf('<strong>%s</strong> %s', esc_html($name), $this->row_actions($actions));
    }

    protected function column_email($item) {
        if (empty($item['email']) || !is_email($item['email'])) {
            return '&mdash;';
        }
        $email = sanitize_email($item['email']);
        return sprintf('<a href="mailto:%1$s">%1$s</a>', esc_html($email));
    }

    protected function column_phone($item) {
        if (empty($item['phone'])) {
            return '&mdash;';
        }
        $phone = esc_html($item['phone']);
        $tel_link = esc_url('tel:' . preg_replace('/[^0-9\+]/', '', $item['phone']));
        return sprintf('<a href="%s">%s</a>', $tel_link, $phone);
    }

    protected function column_job_title($item) {
        return esc_html($item['job_title']);
    }

    protected function column_submitted($item) {
        if (empty($item['submitted'])) {
            return '&mdash;';
        }
        $time = strtotime($item['submitted']);
        if (!$time) {
            return esc_html($item['submitted']);
        }
        return esc_html(date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $time));
    }

    protected function column_resume($item) {
        if (empty($item['resume'])) {
            return '&mdash;';
        }
        $download_url = esc_url($item['resume']);
        return sprintf('<a href="%s" target="_blank" rel="noopener noreferrer">%s</a>', $download_url, __('تحميل', 'AlQasrGroup'));
    }

    protected function get_bulk_actions() {
        return array(
            'delete' => __('حذف', 'AlQasrGroup'),
        );
    }

    public function process_bulk_action() {
        if ('delete' === $this->current_action()) {
            check_admin_referer('bulk-job-applications');
            $ids = isset($_POST['application_ids']) ? array_map('absint', (array) $_POST['application_ids']) : array();
            foreach ($ids as $application_id) {
                AlQasrGroup_delete_job_application($application_id);
            }
        }
    }
}

function AlQasrGroup_delete_job_application($application_id) {
    $application_id = absint($application_id);
    if (!$application_id) {
        return;
    }

    $cv_id = (int) get_post_meta($application_id, 'job_application_cv_id', true);
    if ($cv_id) {
        wp_delete_attachment($cv_id, true);
    }

    wp_delete_post($application_id, true);
}

function AlQasrGroup_register_job_applications_page() {
    $jobs_post_type_object = get_post_type_object('jobs');
    $jobs_capability = $jobs_post_type_object && isset($jobs_post_type_object->cap->edit_posts)
        ? $jobs_post_type_object->cap->edit_posts
        : 'edit_posts';

    add_menu_page(
        __('طلبات الوظائف', 'AlQasrGroup'),
        __('طلبات الوظائف', 'AlQasrGroup'),
        $jobs_capability,
        'AlQasrGroup-job-applications',
        'AlQasrGroup_render_job_applications_page',
        'dashicons-businessperson',
        29.1
    );
}
add_action('admin_menu', 'AlQasrGroup_register_job_applications_page');

function AlQasrGroup_render_job_applications_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('ليس لديك صلاحية للوصول إلى هذه الصفحة.', 'AlQasrGroup'));
    }

    $list_table = new AlQasrGroup_Job_Applications_Table();

    $single_delete = isset($_GET['action']) ? sanitize_text_field(wp_unslash($_GET['action'])) : '';
    if ('delete' === $single_delete && isset($_GET['application_id'])) {
        $application_id = absint($_GET['application_id']);
        $nonce = isset($_GET['_wpnonce']) ? sanitize_text_field(wp_unslash($_GET['_wpnonce'])) : '';
        if ($application_id && wp_verify_nonce($nonce, 'delete-job-application_' . $application_id)) {
            AlQasrGroup_delete_job_application($application_id);
            add_settings_error('AlQasrGroup_job_applications', 'job_application_deleted', __('تم حذف الطلب بنجاح.', 'AlQasrGroup'), 'updated');
        } else {
            add_settings_error('AlQasrGroup_job_applications', 'job_application_delete_failed', __('فشل التحقق الأمني لحذف الطلب.', 'AlQasrGroup'), 'error');
        }
    }

    $list_table->process_bulk_action();
    $list_table->prepare_items();
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline"><?php echo esc_html__('طلبات الوظائف', 'AlQasrGroup'); ?></h1>
        <hr class="wp-header-end">
        <?php settings_errors('AlQasrGroup_job_applications'); ?>
        <form method="post">
            <?php wp_nonce_field('bulk-job-applications'); ?>
            <?php $list_table->display(); ?>
        </form>
    </div>
    <?php
}
