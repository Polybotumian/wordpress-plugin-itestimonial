<?php
function itestimonial_render_page($page_name)
{
    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'itestimonial'));
    }
    $file_path = plugin_dir_path(__FILE__) . 'pages/' . sanitize_file_name($page_name) . '.php';
    if (file_exists($file_path)) {
        include $file_path;
    } else {
        echo '<div class="wrap"><h1>' . esc_html__('Page Not Found', 'itestimonial') . '</h1><p>' . esc_html__('The specified page could not be found.', 'itestimonial') . '</p></div>';
    }
}
