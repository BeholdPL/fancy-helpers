<?php

class HO_Options_Panel {

    /**
     * Options panel arguments.
     */
    protected $args = [];

    /**
     * Options panel title.
     */
    protected $title = '';

    /**
     * Options panel slug.
     */
    protected $slug = '';

    /**
     * Option name to use for saving our options in the database.
     */
    protected $option_name = '';

    /**
     * Option group name.
     */
    protected $option_group_name = '';

    /**
     * User capability allowed to access the options page.
     */
    protected $user_capability = '';

    /**
     * Our array of settings.
     */
    protected $settings = [];

    /**
     * Menu position
     */
    protected $menu_position = '';

    /**
     * Menu page type (submenu_page or options_page).
     */
    protected $menu_page_type = 'submenu_page';

    /**
     * Menu icon
     */
    protected $menu_page_icon = '';

    /**
     * Docs url
     */
    protected $docs_url = '';

    /**
     * Our class constructor.
     */
    public function __construct( array $args, array $settings, $menu_page_type = 'submenu_page' ) {
        $this->args              = $args;
        $this->settings          = $settings;
        $this->title             = $this->args['title'] ?? esc_html__( 'Options', 'handy-patterns-pro' );
        $this->slug              = $this->args['slug'] ?? sanitize_key( $this->title );
        $this->option_name       = $this->args['option_name'] ?? sanitize_key( $this->title );
        $this->option_group_name = $this->option_name . '_group';
        $this->user_capability   = $args['user_capability'] ?? 'manage_options';
        $this->menu_position     = $args['position'] ?? sanitize_key( $this->menu_position );
        $this->menu_page_icon    = $args['menu_page_icon'] ?? sanitize_key( $this->menu_page_icon );
        $this->menu_page_type    = $menu_page_type;
        $this->docs_url          = $args['docs_url'] ?? esc_html__( 'Options', 'handy-patterns-pro' );

        add_action( 'admin_menu', [ $this, 'register_menu_page' ] );
        add_action( 'admin_init', [ $this, 'register_settings' ] );
    }

    /**
     * Register the new menu page.
     */
    public function register_menu_page() {
        if ( $this->menu_page_type === 'submenu_page' ) {
            add_submenu_page(
                'options-general.php', // Slug rodzica (strony "Settings")
                $this->title,
                $this->title,
                $this->user_capability,
                $this->slug,
                [ $this, 'render_options_page' ],
                $this->docs_url 
            );
        } elseif ( $this->menu_page_type === 'menu_page' ) {
            add_menu_page(
                $this->title,
                $this->title,
                $this->user_capability,
                $this->slug,
                [ $this, 'render_options_page' ],
                $this->menu_page_icon,
                $this->menu_position,
                $this->docs_url
            );
        }
    }

    /**
     * Register the settings.
     */
    public function register_settings() {
        register_setting( $this->option_group_name, $this->option_name, [
            'sanitize_callback' => [ $this, 'sanitize_fields' ],
            'default'           => $this->get_defaults(),
        ] );

        add_settings_section(
            $this->option_name . '_sections',
            false,
            false,
            $this->option_name
        );

        foreach ( $this->settings as $key => $args ) {
            $type = $args['type'] ?? 'text';
            $callback = "render_{$type}_field";
            if ( method_exists( $this, $callback ) ) {
                $tr_class = '';
                if ( array_key_exists( 'tab', $args ) ) {
                    $tr_class .= 'hx-settings-tab-item hx-settings-tab-item--' . sanitize_html_class( $args['tab'] );
                }
                // Dodaj klasę na podstawie nazwy opcji
                $tr_class .= ' hx-settings-option-' . sanitize_html_class( $key );

                if ( array_key_exists( 'tab', $args ) ) {
                    add_settings_field(
                        $key,
                        $args['label'],
                        [ $this, $callback ],
                        $this->option_name,
                        $this->option_name . '_sections',
                        [
                            'label_for' => $key,
                            'class'     => $tr_class
                        ]
                    );
                }
            }
        }
        
    }

    /**
     * Saves our fields.
     */
    public function sanitize_fields( $value ) {
        $value = (array) $value;
        $new_value = [];
        foreach ( $this->settings as $key => $args ) {
            $field_type = $args['type'];
            $new_option_value = $value[$key] ?? '';
            if ( $new_option_value ) {
                $sanitize_callback = $args['sanitize_callback'] ?? $this->get_sanitize_callback_by_type( $field_type );
                $new_value[$key] = call_user_func( $sanitize_callback, $new_option_value, $args );
            } elseif ( 'checkbox' === $field_type ) {
                $new_value[$key] = 0;
            }
        }
        return $new_value;
    }

    /**
     * Returns sanitize callback based on field type.
     */
    protected function get_sanitize_callback_by_type( $field_type ) {
        switch ( $field_type ) {
            case 'select':
                return [ $this, 'sanitize_select_field' ];
                break;
            case 'textarea':
                return 'wp_kses_post';
                break;
            case 'checkbox':
                return [ $this, 'sanitize_checkbox_field' ];
                break;
            case 'info':
                return [ $this, 'sanitize_paragrapf_field' ];
                break;
            default:
            case 'text':
                return 'sanitize_text_field';
                break;
        }
    }

    /**
     * Returns default values.
     */
    protected function get_defaults() {
        $defaults = [];
        foreach ( $this->settings as $key => $args ) {
            $defaults[$key] = $args['default'] ?? '';
        }
        return $defaults;
    }

    /**
     * Sanitizes the checkbox field.
     */
    protected function sanitize_checkbox_field( $value = '', $field_args = [] ) {
        return ( 'on' === $value ) ? 1 : 0;
    }

     /**
     * Sanitizes the select field.
     */
    protected function sanitize_select_field( $value = '', $field_args = [] ) {
        $choices = $field_args['choices'] ?? [];
        if ( array_key_exists( $value, $choices ) ) {
            return $value;
        }
    }

    /**
 * Renders the options page.
 */
public function render_options_page() {
    if ( ! current_user_can( $this->user_capability ) ) {
        return;
    }

    settings_errors( $this->option_name . '_mesages' );

    ?>
    <div class="hx-settings-header">
        <div class="hx-settings-title-section">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <?php
            if ( isset( $this->docs_url ) && ! empty( $this->docs_url ) ) {
                ?>
                <span><a href="<?php echo esc_html( $this->docs_url ); ?>" target="_blank"><?php echo esc_html__( 'Documentation', 'handy-patterns-pro' ); ?></a></span>
                <?php
            }
            ?>
        </div>
        <?php $this->render_tabs(); ?>
    </div>
    <hr class="wp-header-end">
    <div class="hx-settings-body hide-if-no-js">
        <form action="options.php" method="post" class="hx-settings-form">
            <?php
                settings_fields( $this->option_group_name );
                do_settings_sections( $this->option_name );
                submit_button( 'Save Settings' );
            ?>
        </form>
    </div>
    <?php
}


    /**
     * Renders options page tabs.
     */
    protected function render_tabs() {
        if ( empty( $this->args['tabs'] ) ) {
            return;
        }

        $tabs = $this->args['tabs'];
        ?>


        <nav class="hx-settings-tabs-wrapper" aria-label="Secondary menu"><?php
            $first_tab = true;
            foreach ( $tabs as $id => $label ) {?>
                <a href="#" data-tab="<?php echo esc_attr( $id ); ?>" class="hx-settings-tab<?php echo ( $first_tab ) ? ' active' : ''; ?>"><?php echo ucfirst( $label ); ?></a>
                <?php
                $first_tab = false;
            }
        ?></nav>

        <script>
            ( function() {
                document.addEventListener( 'click', ( event ) => {
                    const target = event.target;
                    if ( ! target.closest( '.hx-settings-tabs-wrapper a' ) ) {
                        return;
                    }
                    event.preventDefault();
                    document.querySelectorAll( '.hx-settings-tabs-wrapper a' ).forEach( ( tablink ) => {
                        tablink.classList.remove( 'active' );
                    } );
                    target.classList.add( 'active' );
                    targetTab = target.getAttribute( 'data-tab' );
                    document.querySelectorAll( '.hx-settings-tab-item' ).forEach( ( item ) => {
                        if ( item.classList.contains( `hx-settings-tab-item--${targetTab}` ) ) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    } );
                } );
                document.addEventListener( 'DOMContentLoaded', function () {
                    document.querySelector( '.hx-settings-tabs-wrapper .hx-settings-tab' ).click();
                }, false );
            } )();
        </script>

        <?php
    }

    /**
     * Returns an option value.
     */
    protected function get_option_value( $option_name ) {
        $option = get_option( $this->option_name );
        if ( ! array_key_exists( $option_name, $option ) ) {
            return array_key_exists( 'default', $this->settings[$option_name] ) ? $this->settings[$option_name]['default'] : '';
        }
        return $option[$option_name];
    }

    /**
     * Renders a text field.
     */
    public function render_text_field( $args ) {
        $option_name = $args['label_for'];
        $value       = $this->get_option_value( $option_name );
        $description = $this->settings[$option_name]['description'] ?? '';
        $shortcode = $this->settings[$option_name]['shortcode'] ?? '';
        ?>
            <input
                type="text"
                id="<?php echo esc_attr( $args['label_for'] ); ?>"
                name="<?php echo $this->option_name; ?>[<?php echo esc_attr( $args['label_for'] ); ?>]"
                value="<?php echo esc_attr( $value ); ?>">
            <?php if ( $description ) { ?>
                <p class="description"><?php echo esc_html( $description ); ?></p>
            <?php } ?>
            <?php if ( $shortcode ) { ?>
                <p class="shortcode"><?php echo esc_html( $shortcode ); ?></p>
            <?php } ?>
        <?php
    }

    /**
     * Renders a textarea field.
     */
    public function render_textarea_field( $args ) {
        $option_name = $args['label_for'];
        $value       = $this->get_option_value( $option_name );
        $description = $this->settings[$option_name]['description'] ?? '';
        $shortcode = $this->settings[$option_name]['shortcode'] ?? '';
        $rows        = $this->settings[$option_name]['rows'] ?? '11';
        $cols        = $this->settings[$option_name]['cols'] ?? '50';
        ?>
            <textarea
                type="text"
                id="<?php echo esc_attr( $args['label_for'] ); ?>"
                rows="<?php echo esc_attr( absint( $rows ) ); ?>"
                cols="<?php echo esc_attr( absint( $cols ) ); ?>"
                name="<?php echo $this->option_name; ?>[<?php echo esc_attr( $args['label_for'] ); ?>]"><?php echo esc_attr( $value ); ?></textarea>
            <?php if ( $description ) { ?>
                <p class="description"><?php echo esc_html( $description ); ?></p>
            <?php } ?>
            <?php if ( $shortcode ) { ?>
                <p class="shortcode"><?php echo esc_html( $shortcode ); ?></p>
            <?php } ?>
        <?php
    }

    /**
     * Renders a checkbox field.
     */
    public function render_checkbox_field( $args ) {
        $option_name = $args['label_for'];
        $value       = $this->get_option_value( $option_name );
        $description = $this->settings[$option_name]['description'] ?? '';
        $shortcode = $this->settings[$option_name]['shortcode'] ?? '';
        ?>
            <input
                type="checkbox"
                id="<?php echo esc_attr( $args['label_for'] ); ?>"
                name="<?php echo $this->option_name; ?>[<?php echo esc_attr( $args['label_for'] ); ?>]"
                <?php checked( $value, 1, true ); ?>
            >
            <?php if ( $description ) { ?>
                <p class="description"><?php echo esc_html( $description ); ?></p>
            <?php } ?>
            <?php if ( $shortcode ) { ?>
                <p class="shortcode"><?php echo esc_html( $shortcode ); ?></p>
            <?php } ?>
        <?php
    }

    /**
     * Renders a select field.
     */
    public function render_select_field( $args ) {
        $option_name = $args['label_for'];
        $value       = $this->get_option_value( $option_name );
        $description = $this->settings[$option_name]['description'] ?? '';
        $shortcode = $this->settings[$option_name]['shortcode'] ?? '';
        $choices     = $this->settings[$option_name]['choices'] ?? [];
        ?>
            <select
                id="<?php echo esc_attr( $args['label_for'] ); ?>"
                name="<?php echo $this->option_name; ?>[<?php echo esc_attr( $args['label_for'] ); ?>]"
            >
                <?php foreach ( $choices as $choice_v => $label ) { ?>
                    <option value="<?php echo esc_attr( $choice_v ); ?>" <?php selected( $choice_v, $value, true ); ?>><?php echo esc_html( $label ); ?></option>
                <?php } ?>
            </select>
            <?php if ( $description ) { ?>
                <p class="description"><?php echo esc_html( $description ); ?></p>
            <?php } ?>
            <?php if ( $shortcode ) { ?>
                <p class="shortcode"><?php echo esc_html( $shortcode ); ?></p>
            <?php } ?>
        <?php
    }

    /**
     * Renders a info field.
     */
    public function render_info_field( $args ) {
        $description = $this->settings[$args['label_for']]['description'] ?? '';
        $shortcode   = $this->settings[$args['label_for']]['shortcode'] ?? '';
        if ( $description ) { ?>
            <p class="description"><?php echo esc_html( $description ); ?></p>
        <?php }
        if ( $shortcode ) { ?>
            <p class="shortcode"><?php echo esc_html( $shortcode ); ?></p>
        <?php }
    }

}