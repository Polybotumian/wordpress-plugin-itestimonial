<?php
if (!defined('ABSPATH')) {
    exit;
}

function itdb_create_testimonials_table()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'itestimonials';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id char(36) NOT NULL,
        image_url varchar(255) DEFAULT NULL,
        author varchar(255) NOT NULL,
        score int(1) NOT NULL,
        testimonial text NOT NULL,
        date date NOT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

function itdb_delete_testimonials_table()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'itestimonials';

    $wpdb->query("DROP TABLE IF EXISTS $table_name");
}

function itdb_add_testimonial($image_url, $author, $score, $testimonial, $date)
{
    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('You do not have sufficient permissions to access this action.', 'itestimonial'));
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'itestimonials';

    $created = $wpdb->insert(
        $table_name,
        array(
            'id' => wp_generate_uuid4(),
            'image_url' => sanitize_text_field($image_url),
            'author' => sanitize_text_field($author),
            'score' => sanitize_text_field($score),
            'testimonial' => sanitize_textarea_field($testimonial),
            'date' => sanitize_text_field($date),
            'created_at' => current_time('mysql')
        )
    );

    return $created !== false;
}

function itdb_get_all_testimonials($limit = -1)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'itestimonials';
    $limit_clause = ($limit > 0) ? "LIMIT $limit" : "";

    return $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC $limit_clause");
}

function itdb_get_testimonial_by_id($id)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'itestimonials';
    return $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %s", $id));
}

function itdb_get_selected_testimonials($ids)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'itestimonials';
    $placeholders = implode(',', array_fill(0, count($ids), '%s'));

    return $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $table_name WHERE id IN ($placeholders) ORDER BY created_at DESC",
        $ids
    ));
}

function itdb_update_testimonial($image_url, $id, $author, $score, $testimonial, $date)
{
    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('You do not have sufficient permissions to access this action.', 'itestimonial'));
    }

    $id = sanitize_text_field($id);
    $image_url = sanitize_text_field($image_url);
    $author = sanitize_text_field($author);
    $score = absint($score);
    $testimonial = sanitize_textarea_field($testimonial);
    $date = sanitize_text_field($date);

    if (empty($id) || strlen($id) !== 36) {
        wp_die(esc_html__('Invalid testimonial ID.', 'itestimonial'));
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'itestimonials';

    $updated = $wpdb->update(
        $table_name,
        array(
            'image_url' => $image_url,
            'author' => $author,
            'score' => $score,
            'testimonial' => $testimonial,
            'date' => $date,
            // 'updated_at' => current_time('mysql')
        ),
        array('id' => $id),
        array('%s', '%s', '%d', '%s', '%s'),
        array('%s')
    );

    return $updated !== false;
}

function itdb_delete_testimonial($id)
{
    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('You do not have sufficient permissions to access this action.', 'itestimonial'));
    }

    $id = sanitize_text_field($id);
    if (empty($id) || strlen($id) !== 36) {
        wp_die(esc_html__('Invalid testimonial ID.', 'itestimonial'));
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'itestimonials';

    $deleted = $wpdb->delete(
        $table_name,
        array('id' => $id),
        array('%s')
    );

    return $deleted !== false;
}
