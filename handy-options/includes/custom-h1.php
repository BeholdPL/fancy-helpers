<?php

// Dodawanie metaboxu z uwzględnieniem sprawdzania uprawnień
function ho_header_h1_metabox() {
    $post_types = get_post_types(); // Get all post types
    $excluded_post_types = ['revision', 'acf-field-group', 'loos-cbp', 'blockmeister_pattern', 'acf-taxonomy', 'acf-post-type', 'acfe-dop', 'wp_block'];
    
    $allowed_post_types = array_diff($post_types, $excluded_post_types);

    foreach ($allowed_post_types as $post_type) {
        add_meta_box(
            'metabox-ho-header-h1',
            'Header H1',
            'ho_render_header_h1_metabox',
            $post_type,
            'side',
            'high'
        );
    }
}
add_action('add_meta_boxes', 'ho_header_h1_metabox');

// Renderowanie zawartości metaboxu
function ho_render_header_h1_metabox($post) {
    $value = get_post_meta($post->ID, 'ho-header-h1', true);
    ?>
    <div style="display: flex; flex-direction: column">
        <label for="ho-header-h1-input"><?php _e( 'Type header here', 'handy-options' ); ?>:</label>
        <input type="text" id="ho-header-h1-input" name="ho-header-h1-input" value="<?php echo esc_attr($value); ?>">
        <label class="hx-metabox-underlabel ho-shortcode-metabox-label" for="ho-header-h1-input">[ho_custom_meta name='ho-header-h1']</label>
    </div>
    <?php
}

// Zapisywanie wartości pola metaboxu z uwzględnieniem uwierzytelniania i walidacji danych
function ho_save_header_h1_metabox($post_id) {
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (isset($_POST['ho-header-h1-input'])) {
        $value = sanitize_text_field($_POST['ho-header-h1-input']);
        update_post_meta($post_id, 'ho-header-h1', $value);
    }
}
add_action('save_post', 'ho_save_header_h1_metabox');

// Dodawanie kolumny w widoku admina tylko dla wybranych typów wpisów
function ho_add_header_h1_to_posts_view($columns) {
    $excluded_post_types = array('revision', 'acf-field-group', 'loos-cbp', 'blockmeister_pattern', 'acf-taxonomy', 'acf-post-type', 'wp_block');
    if ( ! in_array(get_current_screen()->post_type, $excluded_post_types)) {
        $columns['header_h1'] = 'Header H1';
    }
    return $columns;
}
add_filter('manage_posts_columns', 'ho_add_header_h1_to_posts_view');
add_filter('manage_pages_columns', 'ho_add_header_h1_to_posts_view');

// Wyświetlanie zawartości kolumny w widoku admina tylko dla wybranych typów wpisów
function ho_display_header_h1_in_posts($column_name, $post_id) {
    $post_types = array('post', 'page'); // Dodaj tutaj inne typy wpisów, dla których chcesz wyświetlać zawartość kolumny
    if ($column_name === 'header_h1' && in_array(get_post_type($post_id), $post_types)) {
        $value = get_post_meta($post_id, 'ho-header-h1', true);
        echo esc_html($value);
    }
}
add_action('manage_posts_custom_column', 'ho_display_header_h1_in_posts', 10, 2);
add_action('manage_pages_custom_column', 'ho_display_header_h1_in_posts', 10, 2);