<?php if (!defined('ABSPATH')) {
    exit;
} ?>

<?php if (!empty($itestimonial_instances[$index]['testimonials'])): ?>
    <?php
    $read_more_text = esc_html__('Read More', 'itestimonial');
    $read_less_text = esc_html__('Read Less', 'itestimonial');
    ?>

    <div class="itestimonial-<?php echo esc_attr($itestimonial_instances[$index]['settings']['view']); ?>"
        id="itestimonial-instance-<?php echo esc_attr($index); ?>">
        <?php foreach ($itestimonial_instances[$index]['testimonials'] as $testimonial): ?>
            <div class="itestimonial-slide">
                <div class="itestimonial-header">
                    <img class="itestimonial-avatar"
                        src="<?php echo esc_url(empty($testimonial->image_url) ? get_site_url() . '/wp-content/plugins/itestimonial/assets/avatars/noprofile.svg' : $testimonial->image_url); ?>" />
                    <div class="itestimonial-info">
                        <span class="itestimonial-author"><?php echo esc_html($testimonial->author); ?></span>
                        <?php if (!empty($testimonial->date)): ?>
                            <span class="itestimonial-date">
                                <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($testimonial->date))); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="itestimonial-rating">
                    <?php if (isset($testimonial->score)): ?>
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <span class="fa fa-star <?php echo $i <= intval($testimonial->score) ? 'checked' : 'unchecked'; ?>"></span>
                        <?php endfor; ?>
                    <?php endif; ?>
                </div>

                <?php
                $is_long_text = (strlen($testimonial->testimonial) > 50);
                ?>

                <?php if ($itestimonial_instances[$index]['settings']['view'] !== 'grid'): ?>
                    <div class="itestimonial-content">
                        <span class="itestimonial-text"><?php echo esc_html($testimonial->testimonial); ?></span>
                    </div>
                    <?php if ($is_long_text): ?>
                        <a class="itestimonial-read-more" data-rm="<?php echo esc_attr($read_more_text); ?>"
                            data-rl="<?php echo esc_attr($read_less_text); ?>">
                            <?php echo esc_html($read_more_text); ?>
                        </a>
                    <?php endif; ?>
                <?php elseif ($itestimonial_instances[$index]['settings']['view'] === 'grid'): ?>
                    <div class="itestimonial-content expanded">
                        <span class="itestimonial-text"><?php echo esc_html($testimonial->testimonial); ?></span>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>