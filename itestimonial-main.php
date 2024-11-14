<?php
/*
 * Plugin Name: ITestimonial
 * Plugin URI: undefined
 * Description: A plugin to create and manage simple testimonials.
 * Version: 3.0
 * Author: Yiğit Leblebicier
 * Author URI: https://www.linkedin.com/in/yi%C4%9Fit-leblebicier-0bb2601b6/
 * License: GPL2
 */

if (!defined('ABSPATH')) {
    exit;
}

include_once plugin_dir_path(__FILE__) . 'includes/itestimonial-db-funcs.php';
include_once plugin_dir_path(__FILE__) . 'includes/itestimonial-admin.php';

function itestimonial_shortcode($user_shortcode_attributes)
{
    global $itestimonial_instances;
    if (!isset($itestimonial_instances)) {
        $itestimonial_instances = array();
    }
    $index = count($itestimonial_instances);

    $default_shortcode_attributes = shortcode_atts(
        [
            'view' => '',
            'limit' => '-1',
            'selection' => '',
            'slidestoshow' => '',
            'slidestoscroll' => '',
            'rows' => '',
            'speed' => '',
            'autoplay' => '',
            'autoplayspeed' => '',
            'slidesperrow' => '',
            'dots' => '',
            'arrows' => '',
        ],
        $user_shortcode_attributes,
        'itestimonials'
    );

    $default_shortcode_attributes['limit'] = intval($default_shortcode_attributes['limit']);
    $default_shortcode_attributes['slidestoshow'] = intval($default_shortcode_attributes['slidestoshow']);
    $default_shortcode_attributes['slidestoscroll'] = intval($default_shortcode_attributes['slidestoscroll']);
    $default_shortcode_attributes['rows'] = intval($default_shortcode_attributes['rows']);
    $default_shortcode_attributes['speed'] = intval($default_shortcode_attributes['speed']);
    $default_shortcode_attributes['autoplayspeed'] = intval($default_shortcode_attributes['autoplayspeed']);
    $default_shortcode_attributes['autoplay'] = filter_var($default_shortcode_attributes['autoplay'], FILTER_VALIDATE_BOOLEAN);
    $default_shortcode_attributes['slidesperrow'] = intval($default_shortcode_attributes['slidesperrow']);
    $default_shortcode_attributes['dots'] = filter_var($default_shortcode_attributes['dots'], FILTER_VALIDATE_BOOLEAN);
    $default_shortcode_attributes['arrows'] = filter_var($default_shortcode_attributes['arrows'], FILTER_VALIDATE_BOOLEAN);

    $selection = array_filter(array_map('trim', explode(',', $default_shortcode_attributes['selection'])));
    $itestimonial_instances[$index] = array(
        'settings' => $default_shortcode_attributes,
        'testimonials' => !empty($selection) ? itdb_get_selected_testimonials($selection) : itdb_get_all_testimonials($default_shortcode_attributes['limit']),
    );
    ob_start();
    include plugin_dir_path(__FILE__) . 'includes/shortcode-views/itestimonial-slider.php';
    return ob_get_clean();
}

function itdb_handle_create_request()
{
    if (!isset($_POST['itdb_nonce'])) {
        wp_die(esc_html__('Missing required parameters.', 'itestimonial'));
    }

    if (!wp_verify_nonce($_POST['itdb_nonce'], 'itdb_create_testimonial')) {
        wp_die(esc_html__('Security check failed.', 'itestimonial'));
    }

    $image_url = sanitize_text_field($_POST['image_url']);
    $author = sanitize_text_field($_POST['author']);
    $score = absint($_POST['score']);
    $testimonial = sanitize_text_field($_POST['testimonial']);
    $date = sanitize_text_field($_POST['date']);

    $created = itdb_add_testimonial($image_url, $author, $score, $testimonial, $date, );

    if ($created) {
        wp_redirect(admin_url('admin.php?page=itdb-admin-page&created=true'));
    } else {
        wp_redirect(admin_url('admin.php?page=itdb-admin-page&error=true'));
    }
    exit;
}

function itdb_handle_update_request()
{
    if (!isset($_POST['id']) || !isset($_POST['itdb_nonce'])) {
        wp_die(esc_html__('Missing required parameters.', 'itestimonial'));
    }

    if (!wp_verify_nonce($_POST['itdb_nonce'], 'itdb_update_testimonial')) {
        wp_die(esc_html__('Security check failed.', 'itestimonial'));
    }

    $image_url = sanitize_text_field($_POST['image_url']);
    $id = sanitize_text_field($_POST['id']);
    $author = sanitize_text_field($_POST['author']);
    $score = absint($_POST['score']);
    $testimonial = sanitize_textarea_field($_POST['testimonial']);
    $date = sanitize_text_field($_POST['date']);

    $updated = itdb_update_testimonial($image_url, $id, $author, $score, $testimonial, $date);
    if ($updated) {
        wp_redirect(admin_url('admin.php?page=itdb-admin-page&updated=true'));
    } else {
        wp_redirect(admin_url('admin.php?page=itdb-admin-page&error=true'));
    }
    exit;
}
add_action('admin_post_itdb_update_testimonial', 'itdb_handle_update_request');


function itdb_handle_delete_request()
{
    if (!isset($_GET['id']) || !isset($_GET['itdb_nonce'])) {
        wp_die(esc_html__('Missing required parameters.', 'itestimonial'));
    }

    if (!wp_verify_nonce($_GET['itdb_nonce'], 'itdb_delete_testimonial')) {
        wp_die(esc_html__('Security check failed.', 'itestimonial'));
    }

    $id = sanitize_text_field($_GET['id']);

    $deleted = itdb_delete_testimonial($id);

    if ($deleted) {
        wp_redirect(admin_url('admin.php?page=itdb-admin-page&deleted=true'));
    } else {
        wp_redirect(admin_url('admin.php?page=itdb-admin-page&error=true'));
    }
    exit;
}

function itdb_enqueue_assets()
{
    if (is_singular() && has_shortcode(get_post()->post_content, 'itestimonials')) {
        wp_enqueue_script('itestimonial-slider-js', plugins_url('assets/js/itestimonial-slider.js', __FILE__), array('jquery'), '1.0.0', true);
        wp_enqueue_style('itestimonial-slider-css', plugins_url('assets/css/itestimonial-slider.css', __FILE__), array(), '1.0.0');
        wp_enqueue_style('slick-css', '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css', array(), '1.8.1');
        wp_enqueue_style('slick-theme-css', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css', array(), '1.8.1');
        wp_enqueue_script('slick-js', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array('jquery'), '1.8.1', true);
        wp_enqueue_style('itestimonial-font-awesome-css', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
        wp_enqueue_style('itestimonial-font-css', plugins_url('assets/css/itestimonial-font.css', __FILE__), array(), '1.0.0');
    }
}

function itestimonial_localize_script()
{
    global $itestimonial_instances;
    if (!empty($itestimonial_instances)) {
        wp_localize_script('itestimonial-slider-js', 'itestimonialInstances', $itestimonial_instances);
    }
}

function itestimonial_load_textdomain()
{
    load_plugin_textdomain('itestimonial', false, dirname(plugin_basename(__FILE__)) . '/languages');
}

function itestimonial_enqueue_admin_assets()
{
    wp_enqueue_media();
}

add_action('plugins_loaded', 'itestimonial_load_textdomain');
register_activation_hook(__FILE__, 'itdb_create_testimonials_table');
register_uninstall_hook(__FILE__, 'itdb_delete_testimonials_table');
add_action('admin_menu', 'itestimonial_add_admin_menu');
add_action('admin_post_itdb_create_testimonial', 'itdb_handle_create_request');
add_action('admin_post_itdb_update_testimonial', 'itdb_handle_update_request');
add_action('admin_post_itdb_delete_testimonial', 'itdb_handle_delete_request');
add_shortcode('itestimonials', 'itestimonial_shortcode');
add_action('admin_enqueue_scripts', 'itestimonial_enqueue_admin_assets');
add_action('wp_enqueue_scripts', 'itdb_enqueue_assets');
add_action('wp_footer', 'itestimonial_localize_script');