<?php

include_once plugin_dir_path(__FILE__) . 'itestimonial-utils.php';

function itestimonial_add_admin_menu()
{
    add_menu_page(
        'ITestimonials Admin',
        'ITestimonials',
        'manage_options',
        'itdb-admin-page',
        function () {
            if (!current_user_can('manage_options')) {
                wp_die(__('You do not have sufficient permissions to access this page.', 'itestimonial'));
            }
            itestimonial_render_page('itestimonial-admin-page');
        },
        'dashicons-admin-comments',
        20
    );
}
