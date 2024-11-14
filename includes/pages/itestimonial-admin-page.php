<?php
if (!defined('ABSPATH')) {
    exit;
}

if (isset($_GET['created']) && $_GET['created'] === 'true') {
    echo '<div class="updated"><p>' . esc_html__('Testimonial created successfully.', 'itestimonial') . '</p></div>';
}

if (isset($_GET['deleted']) && $_GET['deleted'] === 'true') {
    echo '<div class="updated"><p>' . esc_html__('Testimonial deleted successfully.', 'itestimonial') . '</p></div>';
}

if (isset($_GET['updated']) && $_GET['updated'] === 'true') {
    echo '<div class="updated"><p>' . esc_html__('Testimonial updated successfully.', 'itestimonial') . '</p></div>';
}

$is_edit = (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id']));
$testimonial_data = null;

if ($is_edit) {
    $testimonial_id = sanitize_text_field($_GET['id']);
    $testimonial_data = itdb_get_testimonial_by_id($testimonial_id);
    if (!$testimonial_data) {
        echo '<div class="error"><p>' . esc_html__('Testimonial not found.', 'itestimonial') . '</p></div>';
        $is_edit = false;
    }
}
?>

<div class="wrap">
    <h1><?php esc_html_e('ITestimonials Management', 'itestimonial'); ?></h1>
    <p>
        <?php esc_html_e('
        Welcome to the ITestimonials admin page. Here you can manage your testimonials. 
        After saving any of your testimonial, you can place it as shortcode to use it.
        ', 'itestimonial'); ?>
    </p>
    <h3><?php esc_html_e('Shortcode Attributes:', 'itestimonial'); ?></h3>
    <ul>
        <li><?php esc_html_e('limit: Maximum number of testimonials (e.g., limit="5").', 'itestimonial'); ?></li>
        <li><?php esc_html_e('view: Layout style (slider or grid).', 'itestimonial'); ?></li>
        <li><?php esc_html_e('slidestoshow: Number of slides visible at once in the carousel (e.g., slidestoshow="3").', 'itestimonial'); ?>
        </li>
        <li><?php esc_html_e('slidestoscroll: Number of slides to move with each scroll (e.g., slidestoscroll="1").', 'itestimonial'); ?>
        </li>
        <li><?php esc_html_e('autoplay: Enable/disable autoplay (autoplay="true" or autoplay="false").', 'itestimonial'); ?>
        </li>
        <li><?php esc_html_e('autoplayspeed: Time between autoplay slides in milliseconds (e.g., autoplayspeed="5000").', 'itestimonial'); ?>
        </li>
        <li><?php esc_html_e('selection: Comma-separated list of testimonial IDs (e.g., selection="b2454407-9ae5-4746-a5d3-9a540b4d1932,de6565c7-9281-4b75-90bd-87e2d4ed943a,fcee34d7-1f97-4691-9939-6d6c09d40279, ..").', 'itestimonial'); ?>
        </li>
    </ul>
    <h3><?php esc_html_e('Examples', 'itestimonial'); ?></h3>
    <p>
        <code>[itestimonials limit="5" view="slider" slidestoshow="3" slidestoscroll="3" autoplay="true" autoplayspeed="5000"]</code>
    </p>
    <p>
        <code>[itestimonials view="grid" slidestoshow="2" slidestoscroll="1" autoplay="true" autoplayspeed="5000"]</code>
    </p>
    <h2><?php echo $is_edit ? esc_html__('Edit Testimonial', 'itestimonial') : esc_html__('Add New Testimonial', 'itestimonial'); ?>
    </h2>
    <form method="post"
        action="<?php echo esc_url(admin_url('admin-post.php?action=' . ($is_edit ? 'itdb_update_testimonial' : 'itdb_create_testimonial'))); ?>">
        <?php wp_nonce_field($is_edit ? 'itdb_update_testimonial' : 'itdb_create_testimonial', 'itdb_nonce'); ?>
        <?php if ($is_edit): ?>
            <input type="hidden" name="id" value="<?php echo esc_attr($testimonial_data->id); ?>">
        <?php endif; ?>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="image_url"><?php esc_html_e('Image', 'itestimonial'); ?></label></th>
                <td>
                    <input type="text" name="image_url" id="image_url" class="regular-text"
                        value="<?php echo $is_edit ? esc_attr($testimonial_data->image_url) : ''; ?>">
                    <button type="button" id="select_image_button"
                        class="button"><?php esc_html_e('Select Image', 'itestimonial'); ?></button>
                    <div id="image_preview" style="margin-top: 10px;">
                        <?php if ($is_edit && !empty($testimonial_data->image_url)): ?>
                            <img src="<?php echo esc_url($testimonial_data->image_url); ?>" style="max-width: 150px;">
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="author"><?php esc_html_e('Author', 'itestimonial'); ?></label></th>
                <td><input name="author" type="text" id="author" class="regular-text" required
                        value="<?php echo $is_edit ? esc_attr($testimonial_data->author) : ''; ?>"></td>
            </tr>
            <tr>
                <th scope="row"><label for="score"><?php esc_html_e('Score', 'itestimonial'); ?></label></th>
                <td><input name="score" type="number" id="score" min="1" max="5" class="regular-text" required
                        value="<?php echo $is_edit ? esc_attr($testimonial_data->score) : ''; ?>"></td>
            </tr>
            <tr>
                <th scope="row"><label for="date"><?php esc_html_e('Date', 'itestimonial'); ?></label></th>
                <td><input name="date" type="date" id="date" class="regular-text" required
                        value="<?php echo $is_edit ? esc_attr($testimonial_data->date) : ''; ?>"></td>
            </tr>
            <tr>
                <th scope="row"><label for="testimonial"><?php esc_html_e('Testimonial', 'itestimonial'); ?></label>
                </th>
                <td><textarea name="testimonial" id="testimonial" rows="5" class="large-text" maxlength="500"
                        style="resize: none;"
                        required><?php echo $is_edit ? esc_textarea($testimonial_data->testimonial) : ''; ?></textarea>
                </td>
            </tr>
        </table>
        <p class="submit">
            <input type="submit" class="button button-primary"
                value="<?php echo $is_edit ? esc_attr__('Update Testimonial', 'itestimonial') : esc_attr__('Add Testimonial', 'itestimonial'); ?>">
        </p>
    </form>

    <h2><?php esc_html_e('Existing Testimonials', 'itestimonial'); ?></h2>
    <table class="widefat fixed">
        <thead>
            <tr>
                <th><?php esc_html_e('Image', 'itestimonial'); ?></th>
                <th><?php esc_html_e('Author', 'itestimonial'); ?></th>
                <th><?php esc_html_e('Score', 'itestimonial'); ?></th>
                <th><?php esc_html_e('Testimonial', 'itestimonial'); ?></th>
                <th><?php esc_html_e('Date', 'itestimonial'); ?></th>
                <th><?php esc_html_e('Created At', 'itestimonial'); ?></th>
                <th><?php esc_html_e('Actions', 'itestimonial'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $testimonials = itdb_get_all_testimonials();
            if (!empty($testimonials)) {
                foreach ($testimonials as $testimonial) {
                    $edit_url = admin_url('admin.php?page=itdb-admin-page&action=edit&id=' . urlencode($testimonial->id));
                    $delete_url = wp_nonce_url(admin_url('admin-post.php?action=itdb_delete_testimonial&id=' . urlencode($testimonial->id)), 'itdb_delete_testimonial', 'itdb_nonce');
                    ?>
                    <tr>
                        <td><?php echo esc_html($testimonial->image_url); ?></td>
                        <td><?php echo esc_html($testimonial->author); ?></td>
                        <td><?php echo esc_html($testimonial->score); ?></td>
                        <td><?php echo esc_html(wp_trim_words($testimonial->testimonial, 10, '..')); ?></td>
                        <td><?php echo esc_html(date_i18n(get_option('date_format'), strtotime($testimonial->date))); ?>
                        <td><?php echo esc_html(date_i18n(get_option('date_format'), strtotime($testimonial->created_at))); ?>
                        </td>
                        <td>
                            <a href="<?php echo esc_url($edit_url); ?>"
                                class="button"><?php esc_html_e('Edit', 'itestimonial'); ?></a>
                            <a href="<?php echo esc_url($delete_url); ?>" class="button delete-button"
                                onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this testimonial?', 'itestimonial'); ?>');"><?php esc_html_e('Delete', 'itestimonial'); ?></a>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                ?>
                <tr>
                    <td colspan="5"><?php esc_html_e('No testimonials found.', 'itestimonial'); ?></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>

<script type="text/javascript">
    jQuery(document).ready(function ($) {
        var file_frame;

        $('#select_image_button').on('click', function (e) {
            e.preventDefault();

            if (file_frame) {
                file_frame.open();
                return;
            }

            file_frame = wp.media({
                title: '<?php esc_html_e("Select or Upload an Image", "itestimonial"); ?>',
                button: {
                    text: '<?php esc_html_e("Use this image", "itestimonial"); ?>'
                },
                multiple: false
            });

            file_frame.on('select', function () {
                var attachment = file_frame.state().get('selection').first().toJSON();

                $('#image_url').val(attachment.url);
                $('#image_preview').html('<img src="' + attachment.url + '" style="max-width: 150px;">');
            });

            file_frame.open();
        });
    });
</script>