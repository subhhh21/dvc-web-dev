<?php
/**
 * Plugin Name: DVC Testimonials Manager
 * Description: A complete testimonial management system with CPT, custom fields, and slider shortcode.
 * Version: 1.0
 * Author: Suvalaxmi Mohanty
 */

if (!defined('ABSPATH')) exit; // Security: Direct access block

// --- Part A: Backend (Register Custom Post Type) ---
add_action('init', 'dvc_register_testimonials_cpt');
function dvc_register_testimonials_cpt() {
    $labels = array(
        'name'               => 'Testimonials',
        'singular_name'      => 'Testimonial',
        'menu_name'          => 'Testimonials',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Testimonial',
        'edit_item'          => 'Edit Testimonial',
        'all_items'          => 'All Testimonials',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'menu_icon'          => 'dashicons-format-quote', // Custom menu icon
        'supports'           => array('title', 'editor', 'thumbnail'), // Title (Client Name), Editor (Text), Featured Image
        'show_in_rest'       => true, // Gutenberg support
    );

    register_post_type('testimonials', $args);
}

// --- Part B: Custom Fields (Meta Box) ---
add_action('add_meta_boxes', 'dvc_testimonials_add_metabox');
function dvc_testimonials_add_metabox() {
    add_meta_box('dvc_testimonial_details', 'Client Details', 'dvc_render_metabox', 'testimonials', 'normal', 'high');
}

function dvc_render_metabox($post) {
    // Security nonce
    wp_nonce_field('dvc_save_testimonial_action', 'dvc_testimonial_nonce');

    // Retrieve existing values
    $position = get_post_meta($post->ID, '_client_position', true);
    $company  = get_post_meta($post->ID, '_client_company', true);
    $rating   = get_post_meta($post->ID, '_client_rating', true);

    ?>
    <p>
        <label>Client Position/Title:</label>
        <input type="text" name="client_position" value="<?php echo esc_attr($position); ?>" class="widefat">
    </p>
    <p>
        <label>Company Name:</label>
        <input type="text" name="client_company" value="<?php echo esc_attr($company); ?>" class="widefat">
    </p>
    <p>
        <label>Rating (1-5 Stars):</label>
        <select name="client_rating" class="widefat">
            <?php for($i=1; $i<=5; $i++): ?>
                <option value="<?php echo $i; ?>" <?php selected($rating, $i); ?>><?php echo $i; ?> Star<?php echo ($i > 1) ? 's' : ''; ?></option>
            <?php endfor; ?>
        </select>
    </p>
    <?php
}

// Save & Sanitize Meta Box Data
add_action('save_post', 'dvc_save_testimonial_meta');
function dvc_save_testimonial_meta($post_id) {
    if (!isset($_POST['dvc_testimonial_nonce']) || !wp_verify_nonce($_POST['dvc_testimonial_nonce'], 'dvc_save_testimonial_action')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (isset($_POST['client_position'])) update_post_meta($post_id, '_client_position', sanitize_text_field($_POST['client_position']));
    if (isset($_POST['client_company']))  update_post_meta($post_id, '_client_company', sanitize_text_field($_POST['client_company']));
    if (isset($_POST['client_rating']))   update_post_meta($post_id, '_client_rating', sanitize_text_field($_POST['client_rating']));
}

// --- Part C & D: Frontend Display (Shortcode) ---
add_shortcode('testimonials', 'dvc_testimonials_shortcode');
function dvc_testimonials_shortcode($atts) {
    $a = shortcode_atts(array(
        'count'   => -1,
        'orderby' => 'date',
        'order'   => 'DESC'
    ), $atts);

    $query = new WP_Query(array(
        'post_type'      => 'testimonials',
        'posts_per_page' => $a['count'],
        'orderby'        => $a['orderby'],
        'order'          => $a['order']
    ));

    ob_start();

    if ($query->have_posts()) : 
        echo '<div class="dvc-testimonials-slider" style="max-width:800px; margin: 20px auto; font-family: sans-serif;">';
        while ($query->have_posts()) : $query->the_post();
            $pos = get_post_meta(get_the_ID(), '_client_position', true);
            $com = get_post_meta(get_the_ID(), '_client_company', true);
            $rat = get_post_meta(get_the_ID(), '_client_rating', true);
            $photo = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');
            ?>
            <div class="testimonial-card" style="border: 1px solid #ddd; padding: 20px; border-radius: 10px; margin-bottom: 15px; background: #fff;">
                <?php if ($photo): ?>
                    <img src="<?php echo esc_url($photo); ?>" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;">
                <?php endif; ?>
                
                <div class="rating" style="color: #ffcc00; margin: 10px 0;">
                    <?php echo str_repeat('★', $rat) . str_repeat('☆', 5 - $rat); ?>
                </div>

                <div class="content" style="font-style: italic; color: #555;">
                    <?php the_content(); ?>
                </div>

                <div class="client-meta" style="margin-top: 15px; font-weight: bold;">
                    <?php the_title(); ?> <br>
                    <span style="font-weight: normal; font-size: 0.9rem; color: #888;">
                        <?php echo esc_html($pos); ?> @ <?php echo esc_html($com); ?>
                    </span>
                </div>
            </div>
        <?php endwhile;
        echo '</div>';
    endif;

    wp_reset_postdata();
    return ob_get_clean();
}