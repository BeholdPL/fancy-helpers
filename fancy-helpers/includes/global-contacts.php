<?php
/**
 * Fancy Helpers Global Contacts
 *
 * This plugin provides a settings page for managing global contact information
 * and generates shortcodes and tags for easy content insertion.
 */

// Prevent direct access to the file
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add the Contacts page to the WordPress admin menu
 */
function fancy_helpers_add_contacts_page() {
    add_theme_page(
        __('Global Contacts', 'fancy-helpers'),
        __('Contacts', 'fancy-helpers'),
        'manage_options',
        'fancy-helpers-contacts',
        'fancy_helpers_contacts_page_content'
    );
}
add_action('admin_menu', 'fancy_helpers_add_contacts_page');

/**
 * Render the content of the Contacts settings page
 */
function fancy_helpers_contacts_page_content() {
    if (!current_user_can('manage_options')) {
        return;
    }

    $active_tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'hours';
    $tabs = [
        'hours' => __('Opening Hours', 'fancy-helpers'),
        'addresses' => __('Addresses', 'fancy-helpers'),
        'phones' => __('Phone Numbers', 'fancy-helpers'),
        'emails' => __('Email Addresses', 'fancy-helpers'),
        'social_media' => __('Social Media', 'fancy-helpers'),
        'contacts' => __('Contacts', 'fancy-helpers'),
        'customs' => __('Custom Fields', 'fancy-helpers')
    ];
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <h2 class="nav-tab-wrapper">
            <?php foreach ($tabs as $tab_key => $tab_label) : ?>
                <a href="<?php echo esc_url(add_query_arg('tab', $tab_key)); ?>" class="nav-tab <?php echo ($active_tab == $tab_key) ? 'nav-tab-active' : ''; ?>">
                    <?php echo esc_html($tab_label); ?>
                </a>
            <?php endforeach; ?>
        </h2>

        <form id="fancy-helpers-contacts-form" action="options.php" method="post">
            <?php
            settings_fields('fancy_helpers_contacts_' . esc_attr($active_tab) . '_options');

            global $wp_settings_fields;
            $page = 'fancy-helpers-contacts-' . esc_attr($active_tab);
            $section = 'fancy_helpers_' . esc_attr($active_tab) . '_section';

            // Wyświetlenie nagłówka sekcji na podstawie aktywnej zakładki
            if (isset($tabs[$active_tab])) {
                echo '<h2 class="settings-header">' . esc_html($tabs[$active_tab]) . '</h2>';
            }

            // Sprawdzenie, czy istnieją pola w danej sekcji
            if (isset($wp_settings_fields[$page][$section])) {
                foreach ($wp_settings_fields[$page][$section] as $field) {
                    call_user_func($field['callback'], $field['args']);
                }
            }

            submit_button(__('Save Settings', 'fancy-helpers'));
            ?>
        </form>
    </div>

    <script>
    jQuery(document).ready(function($) {
        var form = $('#fancy-helpers-contacts-form');
        var initialFormData = form.serialize();

        function formHasUnsavedChanges() {
            return form.serialize() !== initialFormData;
        }

        $(window).on('beforeunload', function(e) {
            if (formHasUnsavedChanges()) {
                e.preventDefault();
                e.returnValue = '';
                return '';
            }
        });

        form.on('submit', function() {
            initialFormData = form.serialize();
        });

        $('.nav-tab').on('click', function(e) {
            if (formHasUnsavedChanges()) {
                e.preventDefault();
                if (confirm('<?php echo esc_js(__('You have unsaved changes. Are you sure you want to leave this tab?', 'fancy-helpers')); ?>')) {
                    window.location.href = $(this).attr('href');
                }
            }
        });

        // Copy functionality
        $('body').on('click', '.copyable', function() {
            var textToCopy = $(this).data('copy');
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(textToCopy).select();
            document.execCommand("copy");
            $temp.remove();

            // Show notification
            var $notification = $('<div id="copy-notification"><?php echo esc_js(__('Copied to clipboard!', 'fancy-helpers')); ?></div>');
            $('body').append($notification);
            $notification.fadeIn().delay(2000).fadeOut(function() {
                $(this).remove();
            });
        });
    });
    </script>
    <?php
}

/**
 * Initialize settings for the Contacts plugin
 */
function fancy_helpers_contacts_settings_init() {
    $tabs = array('hours', 'addresses', 'phones', 'emails', 'social_media', 'contacts', 'customs');

    foreach ($tabs as $tab) {
        register_setting('fancy_helpers_contacts_' . $tab . '_options', 'fancy_helpers_contacts_' . $tab . '_options', 'fancy_helpers_sanitize_' . $tab . '_options');

        add_settings_section(
            'fancy_helpers_' . $tab . '_section',
            __(ucfirst(str_replace('_', ' ', $tab)), 'fancy-helpers'),
            'fancy_helpers_section_callback',
            'fancy-helpers-contacts-' . $tab
        );

        switch ($tab) {
            case 'hours':
                fancy_helpers_add_hours_fields();
                break;
            case 'addresses':
            case 'phones':
            case 'emails':
            case 'social_media':
            case 'contacts':
            case 'customs':
                add_settings_field(
                    'fancy_helpers_' . $tab,
                    __(ucfirst($tab), 'fancy-helpers'),
                    'fancy_helpers_' . $tab . '_field_callback',
                    'fancy-helpers-contacts-' . $tab,
                    'fancy_helpers_' . $tab . '_section'
                );
                break;
        }
    }
}
add_action('admin_init', 'fancy_helpers_contacts_settings_init');

/**
 * Add fields for business hours
 */
function fancy_helpers_add_hours_fields() {
    $hours_fields = array(
        'mon_fri' => __('Monday - Friday', 'fancy-helpers'),
        'sat' => __('Saturday', 'fancy-helpers'),
        'sun' => __('Sunday', 'fancy-helpers'),
        'mon' => __('Monday', 'fancy-helpers'),
        'tue' => __('Tuesday', 'fancy-helpers'),
        'wed' => __('Wednesday', 'fancy-helpers'),
        'thu' => __('Thursday', 'fancy-helpers'),
        'fri' => __('Friday', 'fancy-helpers'),
    );

    foreach ($hours_fields as $field_name => $field_label) {
        add_settings_field(
            'fancy_helpers_hours_' . $field_name,
            $field_label,
            'fancy_helpers_hours_field_callback',
            'fancy-helpers-contacts-hours',
            'fancy_helpers_hours_section',
            array(
                'field_name' => $field_name,
                'label_for' => 'fancy_helpers_hours_' . $field_name,
                'field_label' => $field_label,
            )
        );
    }
}

/**
 * Callback for hours fields
 */
function fancy_helpers_hours_field_callback($args) {
    $options = get_option('fancy_helpers_contacts_hours_options');
    $field_name = $args['field_name'];
    $field_id = $args['label_for'];
    $field_label = $args['field_label'];
    $value = isset($options[$field_name]) ? $options[$field_name] : '';
    $shortcode = '[contacts_hours day="' . esc_attr($field_name) . '"]';
    $tag = '{{contacts.hours-' . esc_attr($field_name) . '}}';

    echo '<div class="fancy-helpers-field">';
    echo '<label for="' . esc_attr($field_id) . '">' . esc_html($field_label) . '</label>';
    echo '<input type="text" id="' . esc_attr($field_id) . '" name="fancy_helpers_contacts_hours_options[' . esc_attr($field_name) . ']" value="' . esc_attr($value) . '" class="regular-text">';
    echo '<p class="description">';
    echo __('Shortcode:', 'fancy-helpers') . ' <span class="copyable" data-copy="' . esc_attr($shortcode) . '">' . esc_html($shortcode) . '</span><br>';
    echo __('Tag:', 'fancy-helpers') . ' <span class="copyable" data-copy="' . esc_attr($tag) . '">' . esc_html($tag) . '</span>';
    echo '</p>';
    echo '</div>';
}

/**
 * Callback for addresses fields
 */
function fancy_helpers_addresses_field_callback() {
    $options = get_option('fancy_helpers_contacts_addresses_options');
    $addresses = isset($options['addresses']) ? $options['addresses'] : array();

    echo '<div id="fancy-helpers-addresses-container">';
    foreach ($addresses as $index => $address) {
        fancy_helpers_render_address_field($index, $address);
    }
    echo '</div>';
    echo '<button type="button" id="add-address" class="button add">' . esc_html__('Add Address', 'fancy-helpers') . '</button>';

    fancy_helpers_render_address_script();
}

/**
 * Render a single address field
 */
function fancy_helpers_render_address_field($index, $address) {
    $shortcode = '[contacts_address label="' . esc_attr($address['name']) . '"]';
    $tag = '{{contacts.address-' . esc_attr($address['name']) . '}}';
    
    echo '<div class="fancy-helpers-field fancy-helpers-address-field">';
    echo '<span class="dashicons dashicons-move drag-handle"></span>';
    echo '<div class="fancy-helpers-input-group">';
    echo '<div style="width: 192px; margin-right: 10px;"><label for="address_name_' . $index . '">' . esc_html__('Label', 'fancy-helpers') . '</label>';
    echo '<input type="text" id="address_name_' . $index . '" name="fancy_helpers_contacts_addresses_options[addresses][' . $index . '][name]" value="' . esc_attr($address['name']) . '" required></div>';
    echo '<div style="flex-grow: 1; display: flex; flex-direction: column"><label for="address_value_' . $index . '">' . esc_html__('Address', 'fancy-helpers') . '</label>';
    echo '<textarea rows="4" cols="32" id="address_value_' . $index . '" name="fancy_helpers_contacts_addresses_options[addresses][' . $index . '][address]" required>' . esc_textarea($address['address']) . '</textarea></div>';
    echo '</div>';
    echo '<p class="description">';
    echo esc_html__('Shortcode:', 'fancy-helpers') . ' <span class="copyable" data-copy="' . esc_attr($shortcode) . '">' . esc_html($shortcode) . '</span><br>';
    echo esc_html__('Tag:', 'fancy-helpers') . ' <span class="copyable" data-copy="' . esc_attr($tag) . '">' . esc_html($tag) . '</span>';
    echo '</p>';
    echo '<button type="button" class="button remove-field remove-address">' . esc_html__('Remove', 'fancy-helpers') . '</button>';
    echo '</div>';
}

/**
 * Render JavaScript for address fields
 */
function fancy_helpers_render_address_script() {
    ?>
    <script>
    jQuery(document).ready(function($) {
        var container = $('#fancy-helpers-addresses-container');
        var addButton = $('#add-address');

        addButton.on('click', function() {
            var newIndex = container.children().length;
            var newField = $('<div class="fancy-helpers-field fancy-helpers-address-field">' +
                '<div class="fancy-helpers-input-group">' +
                '<div style="width: 192px; margin-right: 10px;"><label for="address_name_' + newIndex + '"><?php echo esc_js(__('Label', 'fancy-helpers')); ?></label>' +
                '<input type="text" id="address_name_' + newIndex + '" name="fancy_helpers_contacts_addresses_options[addresses][' + newIndex + '][name]" required></div>' +
                '<div style="flex-grow: 1; display: flex; flex-direction: column""><label for="address_value_' + newIndex + '"><?php echo esc_js(__('Address', 'fancy-helpers')); ?></label>' +
                '<textarea rows="2" cols="32" id="address_value_' + newIndex + '" name="fancy_helpers_contacts_addresses_options[addresses][' + newIndex + '][address]" required></textarea></div>' +
                '</div>' +
                '<p class="description">' +
                '<?php echo esc_js(__('Shortcode:', 'fancy-helpers')); ?> <span class="copyable" data-copy="[contacts_address label=\'\']">[contacts_address label=""]</span><br>' +
                '<?php echo esc_js(__('Tag:', 'fancy-helpers')); ?> <span class="copyable" data-copy="{{contacts.address-}}">{{contacts.address-}}</span>' +
                '</p>' +
                '<button type="button" class="button remove-field remove-address"><?php echo esc_js(__('Remove', 'fancy-helpers')); ?></button>' +
                '</div>');
            container.append(newField);
            if(window.fancyHelpersRefresh){
                window.fancyHelpersRefresh(container);
            }

            newField.find('input[name$="[name]"]').on('input', updateShortcodeAndTag);
        });

        container.on('click', '.remove-address', function(event) {
            event.preventDefault();
            if (confirm('<?php echo esc_js(__('Are you sure that you want to delete this?', 'fancy-helpers')); ?>')) {
                $(this).closest('.fancy-helpers-address-field').remove();
            }
        });

        function updateShortcodeAndTag() {
            var nameInput = $(this);
            var addressField = nameInput.closest('.fancy-helpers-address-field');
            var descriptionP = addressField.find('.description');
            var name = nameInput.val();

            var shortcode = '[contacts_address label="' + name + '"]';
            var tag = '{{contacts.address-' + name + '}}';

            descriptionP.html(
                '<?php echo esc_js(__('Shortcode:', 'fancy-helpers')); ?> <span class="copyable" data-copy="' + shortcode + '">' + shortcode + '</span><br>' +
                '<?php echo esc_js(__('Tag:', 'fancy-helpers')); ?> <span class="copyable" data-copy="' + tag + '">' + tag + '</span>'
            );
        }

        container.find('input[name$="[name]"]').on('input', updateShortcodeAndTag);
    });
    </script>
    <?php
}

/**
 * Callback for phones fields
 */
function fancy_helpers_phones_field_callback() {
    $options = get_option('fancy_helpers_contacts_phones_options');
    $phones = isset($options['phones']) ? $options['phones'] : array();

    echo '<div id="fancy-helpers-phones-container">';
    foreach ($phones as $index => $phone) {
        fancy_helpers_render_phone_field($index, $phone);
    }
    echo '</div>';
    echo '<button type="button" id="add-phone" class="button add">' . esc_html__('Add Phone', 'fancy-helpers') . '</button>';

    fancy_helpers_render_phone_script();
}

/**
 * Render a single phone field
 */
function fancy_helpers_render_phone_field($index, $phone) {
    $shortcode = '[contacts_phone label="' . esc_attr($phone['name']) . '"]';
    $shortcode_with_format = '[contacts_phone label="' . esc_attr($phone['name']) . '" format="tel"]';
    $tag = '{{contacts.phone-' . esc_attr($phone['name']) . '}}';
    $tag_with_format = '{{contacts.phone-tel-' . esc_attr($phone['name']) . '}}';
    
    echo '<div class="fancy-helpers-field fancy-helpers-phone-field">';
    echo '<span class="dashicons dashicons-move drag-handle"></span>';
    echo '<div class="fancy-helpers-input-group">';
    echo '<div style="width: 192px; margin-right: 10px;"><label for="phone_name_' . $index . '">' . esc_html__('Label', 'fancy-helpers') . '</label>';
    echo '<input type="text" id="phone_name_' . $index . '" name="fancy_helpers_contacts_phones_options[phones][' . $index . '][name]" value="' . esc_attr($phone['name']) . '" required></div>';
    echo '<div style="flex-grow: 1; flex-direction:column"><label for="phone_value_' . $index . '">' . esc_html__('Phone', 'fancy-helpers') . '</label>';
    echo '<input type="tel" id="phone_value_' . $index . '" name="fancy_helpers_contacts_phones_options[phones][' . $index . '][number]" value="' . esc_attr($phone['number']) . '" required></div>';
    echo '</div>';
    echo '<p class="description">';
    echo esc_html__('Shortcode:', 'fancy-helpers') . ' <span class="copyable" data-copy="' . esc_attr($shortcode) . '">' . esc_html($shortcode) . '</span><br>';
    echo esc_html__('Shortcode with format:', 'fancy-helpers') . ' <span class="copyable" data-copy="' . esc_attr($shortcode_with_format) . '">' . esc_html($shortcode_with_format) . '</span><br>';
    echo esc_html__('Tag:', 'fancy-helpers') . ' <span class="copyable" data-copy="' . esc_attr($tag) . '">' . esc_html($tag) . '</span><br>';
    echo esc_html__('Tag with format:', 'fancy-helpers') . ' <span class="copyable" data-copy="' . esc_attr($tag_with_format) . '">' . esc_html($tag_with_format) . '</span>';
    echo '</p>';
    echo '<button type="button" class="button remove-field remove-phone">' . esc_html__('Remove', 'fancy-helpers') . '</button>';
    echo '</div>';
}

/**
 * Render JavaScript for phone fields
 */
function fancy_helpers_render_phone_script() {
    ?>
    <script>
    jQuery(document).ready(function($) {
        var container = $('#fancy-helpers-phones-container');
        var addButton = $('#add-phone');

        addButton.on('click', function() {
            var newIndex = container.children().length;
            var newField = $('<div class="fancy-helpers-field fancy-helpers-phone-field">' +
                '<div class="fancy-helpers-input-group">' +
                '<div style="width: 192px; margin-right: 10px;"><label for="phone_name_' + newIndex + '"><?php echo esc_js(__('Label', 'fancy-helpers')); ?></label>' +
                '<input type="text" id="phone_name_' + newIndex + '" name="fancy_helpers_contacts_phones_options[phones][' + newIndex + '][name]" required></div>' +
                '<div style="flex-grow: 1;"><label for="phone_value_' + newIndex + '"><?php echo esc_js(__('Phone', 'fancy-helpers')); ?></label>' +
                '<input type="tel" id="phone_value_' + newIndex + '" name="fancy_helpers_contacts_phones_options[phones][' + newIndex + '][number]" required></div>' +
                '</div>' +
                '<p class="description">' +
                '<?php echo esc_js(__('Shortcode:', 'fancy-helpers')); ?> <span class="copyable" data-copy="[contacts_phone label=\'\']">[contacts_phone label=""]</span><br>' +
                '<?php echo esc_js(__('Shortcode with format:', 'fancy-helpers')); ?> <span class="copyable" data-copy="[contacts_phone label=\'\' format=\'tel\']">[contacts_phone label="" format="tel"]</span><br>' +
                '<?php echo esc_js(__('Tag:', 'fancy-helpers')); ?> <span class="copyable" data-copy="{{contacts.phone-}}">{{contacts.phone-}}</span><br>' +
                '<?php echo esc_js(__('Tag with format:', 'fancy-helpers')); ?> <span class="copyable" data-copy="{{contacts.phone-tel-}}">{{contacts.phone-tel-}}</span>' +
                '</p>' +
                '<button type="button" class="button remove-field remove-phone"><?php echo esc_js(__('Remove', 'fancy-helpers')); ?></button>' +
                '</div>');
            container.append(newField);
            if(window.fancyHelpersRefresh){
                window.fancyHelpersRefresh(container);
            }

            newField.find('input[name$="[name]"]').on('input', updateShortcodeAndTag);
        });

        container.on('click', '.remove-phone', function(event) {
            event.preventDefault();
            if (confirm('<?php echo esc_js(__('Are you sure that you want to delete this?', 'fancy-helpers')); ?>')) {
                $(this).closest('.fancy-helpers-phone-field').remove();
            }
        });

        function updateShortcodeAndTag() {
            var nameInput = $(this);
            var phoneField = nameInput.closest('.fancy-helpers-phone-field');
            var descriptionP = phoneField.find('.description');
            var name = nameInput.val();

            var shortcode = '[contacts_phone label="' + name + '"]';
            var shortcodeWithFormat = '[contacts_phone label="' + name + '" format="tel"]';
            var tag = '{{contacts.phone-' + name + '}}';
            var tagWithFormat = '{{contacts.phone-tel-' + name + '}}';

            descriptionP.html(
                '<?php echo esc_js(__('Shortcode:', 'fancy-helpers')); ?> <span class="copyable" data-copy="' + shortcode + '">' + shortcode + '</span><br>' +
                '<?php echo esc_js(__('Shortcode with format:', 'fancy-helpers')); ?> <span class="copyable" data-copy="' + shortcodeWithFormat + '">' + shortcodeWithFormat + '</span><br>' +
                '<?php echo esc_js(__('Tag:', 'fancy-helpers')); ?> <span class="copyable" data-copy="' + tag + '">' + tag + '</span><br>' +
                '<?php echo esc_js(__('Tag with format:', 'fancy-helpers')); ?> <span class="copyable" data-copy="' + tagWithFormat + '">' + tagWithFormat + '</span>'
            );
        }

        container.find('input[name$="[name]"]').on('input', updateShortcodeAndTag);
    });
    </script>
    <?php
}

/**
 * Callback for emails fields
 */
function fancy_helpers_emails_field_callback() {
    $options = get_option('fancy_helpers_contacts_emails_options');
    $emails = isset($options['emails']) ? $options['emails'] : array();

    echo '<div id="fancy-helpers-emails-container">';
    foreach ($emails as $index => $email) {
        fancy_helpers_render_email_field($index, $email);
    }
    echo '</div>';
    echo '<button type="button" id="add-email" class="button add">' . esc_html__('Add Email', 'fancy-helpers') . '</button>';

    fancy_helpers_render_email_script();
}

/**
 * Render a single email field
 */
function fancy_helpers_render_email_field($index, $email) {
    $shortcode = '[contacts_email label="' . esc_attr($email['name']) . '"]';
    $shortcode_with_format = '[contacts_email label="' . esc_attr($email['name']) . '" format="mailto"]';
    $tag = '{{contacts.email-' . esc_attr($email['name']) . '}}';
    $tag_with_format = '{{contacts.email-mailto-' . esc_attr($email['name']) . '}}';
    
    echo '<div class="fancy-helpers-field fancy-helpers-email-field">';
    echo '<span class="dashicons dashicons-move drag-handle"></span>';
    echo '<div class="fancy-helpers-input-group">';
    echo '<div style="width: 192px; margin-right: 10px;"><label for="email_name_' . $index . '">' . esc_html__('Label', 'fancy-helpers') . '</label>';
    echo '<input type="text" id="email_name_' . $index . '" name="fancy_helpers_contacts_emails_options[emails][' . $index . '][name]" value="' . esc_attr($email['name']) . '" required></div>';
    echo '<div style="flex-grow: 1;"><label for="email_value_' . $index . '">' . esc_html__('E-mail', 'fancy-helpers') . '</label>';
    echo '<input type="email" id="email_value_' . $index . '" name="fancy_helpers_contacts_emails_options[emails][' . $index . '][address]" value="' . esc_attr($email['address']) . '" required></div>';
    echo '</div>';
    echo '<p class="description">';
    echo esc_html__('Shortcode:', 'fancy-helpers') . ' <span class="copyable" data-copy="' . esc_attr($shortcode) . '">' . esc_html($shortcode) . '</span><br>';
    echo esc_html__('Shortcode with format:', 'fancy-helpers') . ' <span class="copyable" data-copy="' . esc_attr($shortcode_with_format) . '">' . esc_html($shortcode_with_format) . '</span><br>';
    echo esc_html__('Tag:', 'fancy-helpers') . ' <span class="copyable" data-copy="' . esc_attr($tag) . '">' . esc_html($tag) . '</span><br>';
    echo esc_html__('Tag with format:', 'fancy-helpers') . ' <span class="copyable" data-copy="' . esc_attr($tag_with_format) . '">' . esc_html($tag_with_format) . '</span>';
    echo '</p>';
    echo '<button type="button" class="button remove-field remove-email">' . esc_html__('Remove', 'fancy-helpers') . '</button>';
    echo '</div>';
}

/**
 * Render JavaScript for email fields
 */
function fancy_helpers_render_email_script() {
    ?>
    <script>
    jQuery(document).ready(function($) {
        var container = $('#fancy-helpers-emails-container');
        var addButton = $('#add-email');

        addButton.on('click', function() {
            var newIndex = container.children().length;
            var newField = $('<div class="fancy-helpers-field fancy-helpers-email-field">' +
                '<div class="fancy-helpers-input-group">' +
                '<div style="width: 192px; margin-right: 10px;"><label for="email_name_' + newIndex + '"><?php echo esc_attr__('Label', 'fancy-helpers'); ?></label>' +
                '<input type="text" id="email_name_' + newIndex + '" name="fancy_helpers_contacts_emails_options[emails][' + newIndex + '][name]" required></div>' +
                '<div style="flex-grow: 1;"><label for="email_value_' + newIndex + '"><?php echo esc_attr__('E-mail', 'fancy-helpers'); ?></label>' +
                '<input type="email" id="email_value_' + newIndex + '" name="fancy_helpers_contacts_emails_options[emails][' + newIndex + '][address]" required></div>' +
                '</div>' +
                '<p class="description"></p>' +
                '<button type="button" class="button remove-field remove-email"><?php echo esc_attr__('Remove', 'fancy-helpers'); ?></button>' +
                '</div>');
            container.append(newField);
            if(window.fancyHelpersRefresh){
                window.fancyHelpersRefresh(container);
            }

            newField.find('input[name$="[name]"]').on('input', updateShortcodeAndTag).trigger('input');
        });

        container.on('click', '.remove-email', function(event) {
            event.preventDefault();
            if (confirm('<?php echo esc_js(__('Are you sure that you want to delete this?', 'fancy-helpers')); ?>')) {
                $(this).closest('.fancy-helpers-email-field').remove();
            }
        });

        function updateShortcodeAndTag() {
            var nameInput = $(this);
            var emailField = nameInput.closest('.fancy-helpers-email-field');
            var descriptionP = emailField.find('.description');
            var name = nameInput.val().trim();

            if (!name) {
                descriptionP.html('<?php echo esc_js(__('Please enter a name to generate shortcodes and tags.', 'fancy-helpers')); ?>');
                return;
            }

            var shortcode = '[contacts_email label="' + name + '"]';
            var shortcodeWithFormat = '[contacts_email label="' + name + '" format="mailto"]';
            var tag = '{{contacts.email-' + name + '}}';
            var tagWithFormat = '{{contacts.email-mailto-' + name + '}}';

            descriptionP.html(
                '<?php echo esc_js(__('Shortcode:', 'fancy-helpers')); ?> <span class="copyable" data-copy="' + shortcode + '">' + shortcode + '</span><br>' +
                '<?php echo esc_js(__('Shortcode with format:', 'fancy-helpers')); ?> <span class="copyable" data-copy="' + shortcodeWithFormat + '">' + shortcodeWithFormat + '</span><br>' +
                '<?php echo esc_js(__('Tag:', 'fancy-helpers')); ?> <span class="copyable" data-copy="' + tag + '">' + tag + '</span><br>' +
                '<?php echo esc_js(__('Tag with format:', 'fancy-helpers')); ?> <span class="copyable" data-copy="' + tagWithFormat + '">' + tagWithFormat + '</span>'
            );
        }

        $(document).on('click', '.copyable', function() {
            var copyText = $(this).data('copy');
            navigator.clipboard.writeText(copyText).then(function() {
                alert('<?php echo esc_js(__('Copied to clipboard!', 'fancy-helpers')); ?>');
            }, function() {
                alert('<?php echo esc_js(__('Failed to copy!', 'fancy-helpers')); ?>');
            });
        });

        // Inicjalizacja dla istniejących pól
        container.find('input[name$="[name]"]').on('input', updateShortcodeAndTag).trigger('input');
    });
    </script>
    <?php
}

/**
 * Callback for social media fields
 */
function fancy_helpers_social_media_field_callback() {
    $options = get_option('fancy_helpers_contacts_social_media_options');
    $social_media = isset($options['social_media']) ? $options['social_media'] : array();

    echo '<div id="fancy-helpers-social-media-container">';
    foreach ($social_media as $index => $platform) {
        fancy_helpers_render_social_media_field($index, $platform);
    }
    echo '</div>';
    echo '<button type="button" id="add-social-media" class="button add">' . esc_html__('Add Social Media', 'fancy-helpers') . '</button>';

    fancy_helpers_render_social_media_script();
}

/**
 * Render a single social media field
 */
function fancy_helpers_render_social_media_field($index, $platform) {
    $shortcode_url = '[contacts_social label="' . esc_attr($platform['name']) . '" field="url"]';
    $shortcode_domain = '[contacts_social label="' . esc_attr($platform['name']) . '" field="domain"]';
    $shortcode_name = '[contacts_social label="' . esc_attr($platform['name']) . '" field="name"]';

    $tag_url = '{{contacts.social-' . esc_attr($platform['name']) . '-url}}';
    $tag_domain = '{{contacts.social-' . esc_attr($platform['name']) . '-domain}}';
    $tag_name = '{{contacts.social-' . esc_attr($platform['name']) . '-name}}';

    echo '<div class="fancy-helpers-field fancy-helpers-social-media-field">';
    echo '<span class="dashicons dashicons-move drag-handle"></span>';
    echo '<div class="fancy-helpers-input-group">';
    echo '<div style="width: 192px; margin-right: 10px;"><label for="social_name_' . $index . '">' . esc_html__('Label', 'fancy-helpers') . '</label>';
    echo '<input type="text" id="social_name_' . $index . '" name="fancy_helpers_contacts_social_media_options[social_media][' . $index . '][name]" value="' . esc_attr($platform['name']) . '" required></div>';
    echo '<div style="flex-grow: 1; display: flex;">';
    echo '<div style="flex: 1; margin-right: 10px;"><label for="social_displayname_' . $index . '">' . esc_html__('Name', 'fancy-helpers') . '</label>';
    echo '<input type="text" id="social_displayname_' . $index . '" name="fancy_helpers_contacts_social_media_options[social_media][' . $index . '][displayname]" value="' . esc_attr(isset($platform['displayname']) ? $platform['displayname'] : '') . '"></div>';
    echo '<div style="flex: 1;"><label for="social_value_' . $index . '">' . esc_html__('URL', 'fancy-helpers') . '</label>';
    echo '<input type="text" id="social_value_' . $index . '" name="fancy_helpers_contacts_social_media_options[social_media][' . $index . '][url]" value="' . esc_attr($platform['url']) . '" required></div>';
    echo '</div>';
    echo '</div>';
    echo '<p class="description">';
    echo esc_html__('Shortcode for name:', 'fancy-helpers') . ' <span class="copyable" data-copy="' . esc_attr($shortcode_name) . '">' . esc_html($shortcode_name) . '</span><br>';
    echo esc_html__('Shortcode for URL:', 'fancy-helpers') . ' <span class="copyable" data-copy="' . esc_attr($shortcode_url) . '">' . esc_html($shortcode_url) . '</span><br>';
    echo esc_html__('Shortcode for domain:', 'fancy-helpers') . ' <span class="copyable" data-copy="' . esc_attr($shortcode_domain) . '">' . esc_html($shortcode_domain) . '</span><br>';
    echo esc_html__('Tag for name:', 'fancy-helpers') . ' <span class="copyable" data-copy="' . esc_attr($tag_name) . '">' . esc_html($tag_name) . '</span><br>';
    echo esc_html__('Tag for URL:', 'fancy-helpers') . ' <span class="copyable" data-copy="' . esc_attr($tag_url) . '">' . esc_html($tag_url) . '</span><br>';
    echo esc_html__('Tag for domain:', 'fancy-helpers') . ' <span class="copyable" data-copy="' . esc_attr($tag_domain) . '">' . esc_html($tag_domain) . '</span>';
    echo '</p>';
    echo '<button type="button" class="button remove-field remove-social-media">' . esc_html__('Remove', 'fancy-helpers') . '</button>';
    echo '</div>';
}

/**
 * Render JavaScript for social media fields
 */
function fancy_helpers_render_social_media_script() {
    ?>
    <script>
    jQuery(document).ready(function($) {
        var container = $('#fancy-helpers-social-media-container');
        var addButton = $('#add-social-media');

        addButton.on('click', function() {
            var newIndex = container.children().length;
            var newField = $('<div class="fancy-helpers-field fancy-helpers-social-media-field">' +
                '<div class="fancy-helpers-input-group">' +
                '<div style="width: 192px; margin-right: 10px;"><label for="social_name_' + newIndex + '"><?php echo esc_attr__('Label', 'fancy-helpers'); ?></label>' +
                '<input type="text" id="social_name_' + newIndex + '" name="fancy_helpers_contacts_social_media_options[social_media][' + newIndex + '][name]" required></div>' +
                '<div style="flex-grow: 1; display: flex;">' +
                '<div style="flex: 1; margin-right: 10px;"><label for="social_displayname_' + newIndex + '"><?php echo esc_attr__('Name', 'fancy-helpers'); ?></label>' +
                '<input type="text" id="social_displayname_' + newIndex + '" name="fancy_helpers_contacts_social_media_options[social_media][' + newIndex + '][displayname]"></div>' +
                '<div style="flex: 1;"><label for="social_value_' + newIndex + '"><?php echo esc_attr__('URL', 'fancy-helpers'); ?></label>' +
                '<input type="text" id="social_value_' + newIndex + '" name="fancy_helpers_contacts_social_media_options[social_media][' + newIndex + '][url]" required></div>' +
                '</div>' +
                '</div>' +
                '<p class="description"></p>' +
                '<button type="button" class="button remove-field remove-social-media"><?php echo esc_attr__('Remove', 'fancy-helpers'); ?></button>' +
                '</div>');
            container.append(newField);
            if(window.fancyHelpersRefresh){
                window.fancyHelpersRefresh(container);
            }

            newField.find('input[name$="[name]"]').on('input', updateShortcodeAndTag).trigger('input');
        });

        container.on('click', '.remove-social-media', function(event) {
            event.preventDefault();
            if (confirm('<?php echo esc_js(__('Are you sure that you want to delete this?', 'fancy-helpers')); ?>')) {
                $(this).closest('.fancy-helpers-social-media-field').remove();
            }
        });

        function updateShortcodeAndTag() {
            var nameInput = $(this);
            var socialMediaField = nameInput.closest('.fancy-helpers-social-media-field');
            var descriptionP = socialMediaField.find('.description');
            var name = nameInput.val().trim();

            if (!name) {
                descriptionP.html('<?php echo esc_js(__('Please enter a name to generate shortcodes and tags.', 'fancy-helpers')); ?>');
                return;
            }

            var shortcodeUrl = '[contacts_social label="' + name + '" field="url"]';
            var shortcodeDomain = '[contacts_social label="' + name + '" field="domain"]';
            var shortcodeName = '[contacts_social label="' + name + '" field="name"]';

            var tagUrl = '{{contacts.social-' + name + '-url}}';
            var tagDomain = '{{contacts.social-' + name + '-domain}}';
            var tagName = '{{contacts.social-' + name + '-name}}';

            descriptionP.html(
                '<?php echo esc_js(__('Shortcode for name:', 'fancy-helpers')); ?> <span class="copyable" data-copy="' + shortcodeName + '">' + shortcodeName + '</span><br>' +
                '<?php echo esc_js(__('Shortcode for URL:', 'fancy-helpers')); ?> <span class="copyable" data-copy="' + shortcodeUrl + '">' + shortcodeUrl + '</span><br>' +
                '<?php echo esc_js(__('Shortcode for Domain:', 'fancy-helpers')); ?> <span class="copyable" data-copy="' + shortcodeDomain + '">' + shortcodeDomain + '</span><br>' +
                '<?php echo esc_js(__('Tag for name:', 'fancy-helpers')); ?> <span class="copyable" data-copy="' + tagName + '">' + tagName + '</span><br>' +
                '<?php echo esc_js(__('Tag for URL:', 'fancy-helpers')); ?> <span class="copyable" data-copy="' + tagUrl + '">' + tagUrl + '</span><br>' +
                '<?php echo esc_js(__('Tag for Domain:', 'fancy-helpers')); ?> <span class="copyable" data-copy="' + tagDomain + '">' + tagDomain + '</span>'
            );
        }

        $(document).on('click', '.copyable', function() {
            var copyText = $(this).data('copy');
            navigator.clipboard.writeText(copyText).then(function() {
                alert('<?php echo esc_js(__('Copied to clipboard!', 'fancy-helpers')); ?>');
            }, function() {
                alert('<?php echo esc_js(__('Failed to copy!', 'fancy-helpers')); ?>');
            });
        });
    });
    </script>
    <?php
}

/**
 * Callback for custom fields
 */
function fancy_helpers_customs_field_callback() {
    $options = get_option('fancy_helpers_contacts_customs_options');
    $customs = isset($options['customs']) ? $options['customs'] : array();

    echo '<div id="fancy-helpers-customs-container">';
    foreach ($customs as $index => $custom) {
        fancy_helpers_render_custom_field($index, $custom);
    }
    echo '</div>';
    echo '<button type="button" id="add-custom" class="button add">' . esc_html__('Add Custom Field', 'fancy-helpers') . '</button>';

    fancy_helpers_render_custom_script();
}

/**
 * Render a single custom field
 */
function fancy_helpers_render_custom_field($index, $custom) {
    $shortcode = '[contacts_custom label="' . esc_attr($custom['name']) . '"]';
    $tag = '{{contacts.custom-' . esc_attr($custom['name']) . '}}';
    
    echo '<div class="fancy-helpers-field fancy-helpers-custom-field">';
    echo '<span class="dashicons dashicons-move drag-handle"></span>';
    echo '<div class="fancy-helpers-input-group">';
    echo '<div style="width: 192px; margin-right: 10px;"><label for="custom_name_' . $index . '">' . esc_html__('Label', 'fancy-helpers') . '</label>';
    echo '<input type="text" id="custom_name_' . $index . '" name="fancy_helpers_contacts_customs_options[customs][' . $index . '][name]" value="' . esc_attr($custom['name']) . '" required></div>';
    echo '<div style="flex-grow: 1; display: flex; flex-direction: column"><label for="custom_value_' . $index . '">' . esc_html__('Value', 'fancy-helpers') . '</label>';
    echo '<textarea rows="4" cols="32" id="custom_value_' . $index . '" name="fancy_helpers_contacts_customs_options[customs][' . $index . '][value]" required>' . esc_textarea($custom['value']) . '</textarea></div>';
    echo '</div>';
    echo '<p class="description">';
    echo esc_html__('Shortcode:', 'fancy-helpers') . ' <span class="copyable" data-copy="' . esc_attr($shortcode) . '">' . esc_html($shortcode) . '</span><br>';
    echo esc_html__('Tag:', 'fancy-helpers') . ' <span class="copyable" data-copy="' . esc_attr($tag) . '">' . esc_html($tag) . '</span>';
    echo '</p>';
    echo '<button type="button" class="button remove-field remove-custom">' . esc_html__('Remove', 'fancy-helpers') . '</button>';
    echo '</div>';
}

/**
 * Render JavaScript for custom fields
 */
function fancy_helpers_render_custom_script() {
    ?>
    <script>
    jQuery(document).ready(function($) {
        var container = $('#fancy-helpers-customs-container');
        var addButton = $('#add-custom');

        addButton.on('click', function() {
            var newIndex = container.children().length;
            var newField = $('<div class="fancy-helpers-field fancy-helpers-custom-field">' +
                '<div class="fancy-helpers-input-group">' +
                '<div style="width: 192px; margin-right: 10px;"><label for="custom_name_' + newIndex + '"><?php echo esc_js(__('Label', 'fancy-helpers')); ?></label>' +
                '<input type="text" id="custom_name_' + newIndex + '" name="fancy_helpers_contacts_customs_options[customs][' + newIndex + '][name]" required></div>' +
                '<div style="flex-grow: 1; display: flex; flex-direction: column"><label for="custom_value_' + newIndex + '"><?php echo esc_js(__('Value', 'fancy-helpers')); ?></label>' +
                '<textarea rows="4" cols="32" id="custom_value_' + newIndex + '" name="fancy_helpers_contacts_customs_options[customs][' + newIndex + '][value]" required></textarea></div>' +
                '</div>' +
                '<p class="description">' +
                '<?php echo esc_js(__('Shortcode:', 'fancy-helpers')); ?> <span class="copyable" data-copy="[contacts_custom label=\'\']">[contacts_custom label=""]</span><br>' +
                '<?php echo esc_js(__('Tag:', 'fancy-helpers')); ?> <span class="copyable" data-copy="{{contacts.custom-}}">{{contacts.custom-}}</span>' +
                '</p>' +
                '<button type="button" class="button remove-field remove-custom"><?php echo esc_js(__('Remove', 'fancy-helpers')); ?></button>' +
                '</div>');
            container.append(newField);
            if(window.fancyHelpersRefresh){
                window.fancyHelpersRefresh(container);
            }

            newField.find('input[name$="[name]"]').on('input', updateShortcodeAndTag);
        });

        container.on('click', '.remove-custom', function(event) {
            event.preventDefault();
            if (confirm('<?php echo esc_js(__('Are you sure that you want to delete this?', 'fancy-helpers')); ?>')) {
                $(this).closest('.fancy-helpers-custom-field').remove();
            }
        });

        function updateShortcodeAndTag() {
            var nameInput = $(this);
            var socialMediaField = nameInput.closest('.fancy-helpers-custom-field');
            var descriptionP = socialMediaField.find('.description');
            var name = nameInput.val();

            var shortcodeUrl = '[contacts_social label="' + name + '" field="url"]';
            var shortcodeName = '[contacts_social label="' + name + '" field="name"]';
            var tagUrl = '{{contacts.social-' + name + '-url}}';
            var tagName = '{{contacts.social-' + name + '-name}}';

            descriptionP.html(
                '<?php echo esc_js(__('Shortcode for name:', 'fancy-helpers')); ?> <span class="copyable" data-copy="' + shortcodeName + '">' + shortcodeName + '</span><br>' +
                '<?php echo esc_js(__('Shortcode for URL:', 'fancy-helpers')); ?> <span class="copyable" data-copy="' + shortcodeUrl + '">' + shortcodeUrl + '</span><br>' +
                '<?php echo esc_js(__('Tag for name:', 'fancy-helpers')); ?> <span class="copyable" data-copy="' + tagName + '">' + tagName + '</span><br>' +
                '<?php echo esc_js(__('Tag for URL:', 'fancy-helpers')); ?> <span class="copyable" data-copy="' + tagUrl + '">' + tagUrl + '</span>'
            );
        }

        container.find('input[name$="[name]"]').on('input', updateShortcodeAndTag);
        });
        </script>
    <?php
}

/**
 * Callback for contact groups
 */
function fancy_helpers_contacts_field_callback() {
    $options = get_option('fancy_helpers_contacts_contacts_options');
    $contacts = isset($options['contacts']) ? $options['contacts'] : array();

    echo '<div id="fancy-helpers-contacts-groups-container">';
    foreach ($contacts as $index => $contact) {
        fancy_helpers_render_contact_group_field($index, $contact);
    }
    echo '</div>';
    echo '<button type="button" id="add-contact-group" class="button add">' . esc_html__('Add Contact', 'fancy-helpers') . '</button>';

    fancy_helpers_render_contact_group_script();
}

/**
 * Render a single contact group field
 */
function fancy_helpers_render_contact_group_field($index, $contact) {
    echo '<div class="fancy-helpers-field fancy-helpers-contact-group">';
    echo '<span class="dashicons dashicons-move drag-handle"></span>';
    echo '<div class="fancy-helpers-input-group">';
    echo '<div style="width: 192px; margin-right: 10px;"><label for="contact_name_' . $index . '">' . esc_html__('Name', 'fancy-helpers') . '</label>';
    echo '<input type="text" id="contact_name_' . $index . '" name="fancy_helpers_contacts_contacts_options[contacts][' . $index . '][name]" value="' . esc_attr($contact['name'] ?? '') . '" required></div>';
    echo '<div style="flex-grow:1;"><label for="contact_phone_' . $index . '">' . esc_html__('Phone', 'fancy-helpers') . '</label>';
    echo '<input type="text" id="contact_phone_' . $index . '" name="fancy_helpers_contacts_contacts_options[contacts][' . $index . '][phone]" value="' . esc_attr($contact['phone'] ?? '') . '"></div>';
    echo '</div>';
    echo '<div class="fancy-helpers-input-group">';
    echo '<div style="flex-grow:1;"><label for="contact_email_' . $index . '">' . esc_html__('Email', 'fancy-helpers') . '</label>';
    echo '<input type="email" id="contact_email_' . $index . '" name="fancy_helpers_contacts_contacts_options[contacts][' . $index . '][email]" value="' . esc_attr($contact['email'] ?? '') . '"></div>';
    echo '</div>';
    echo '<button type="button" class="button remove-field remove-contact-group">' . esc_html__('Remove', 'fancy-helpers') . '</button>';
    echo '</div>';
}

/**
 * Render JavaScript for contact groups
 */
function fancy_helpers_render_contact_group_script() {
    ?>
    <script>
    jQuery(document).ready(function($){
        var container = $('#fancy-helpers-contacts-groups-container');
        $('#add-contact-group').on('click', function(){
            var newIndex = container.children().length;
            var newField = $('<div class="fancy-helpers-field fancy-helpers-contact-group">'+
                '<span class="dashicons dashicons-move drag-handle"></span>'+
                '<div class="fancy-helpers-input-group">'+
                '<div style="width: 192px; margin-right: 10px;"><label for="contact_name_'+newIndex+'"><?php echo esc_js(__('Name', 'fancy-helpers')); ?></label>'+
                '<input type="text" id="contact_name_'+newIndex+'" name="fancy_helpers_contacts_contacts_options[contacts]['+newIndex+'][name]" required></div>'+
                '<div style="flex-grow:1;"><label for="contact_phone_'+newIndex+'"><?php echo esc_js(__('Phone', 'fancy-helpers')); ?></label>'+
                '<input type="text" id="contact_phone_'+newIndex+'" name="fancy_helpers_contacts_contacts_options[contacts]['+newIndex+'][phone]"></div>'+
                '</div>'+
                '<div class="fancy-helpers-input-group">'+
                '<div style="flex-grow:1;"><label for="contact_email_'+newIndex+'"><?php echo esc_js(__('Email', 'fancy-helpers')); ?></label>'+
                '<input type="email" id="contact_email_'+newIndex+'" name="fancy_helpers_contacts_contacts_options[contacts]['+newIndex+'][email]"></div>'+
                '</div>'+
                '<button type="button" class="button remove-field remove-contact-group"><?php echo esc_js(__('Remove', 'fancy-helpers')); ?></button>'+
                '</div>');
            container.append(newField);
            if(window.fancyHelpersRefresh){
                window.fancyHelpersRefresh(container);
            }
        });
        container.on('click', '.remove-contact-group', function(e){
            e.preventDefault();
            if(confirm('<?php echo esc_js(__('Are you sure that you want to delete this?', 'fancy-helpers')); ?>')){
                $(this).closest('.fancy-helpers-contact-group').remove();
            }
        });
    });
    </script>
    <?php
}

/**
 * Enqueue styles for the Fancy Global Contacts admin page
 */
function fancy_global_contacts_assets() {
    wp_enqueue_style('fancy-global-contacs-styles', FANCY_HELPERS_URL . 'admin/css/fancy-global-contacts.min.css', array(), null);
    wp_enqueue_style('dashicons');
    wp_enqueue_script('fancy-global-contacts-sortable', FANCY_HELPERS_URL . 'admin/js/fancy-global-contacts-sortable.js', array('jquery', 'jquery-ui-sortable'), null, true);
}
add_action('admin_enqueue_scripts', 'fancy_global_contacts_assets');

/**
 * Sanitize hours options
 */
function fancy_helpers_sanitize_hours_options($input) {
    $sanitized_input = array();
    $days = array('mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun', 'mon_fri');
    foreach ($days as $day) {
        if (isset($input[$day])) {
            $sanitized_input[$day] = sanitize_text_field($input[$day]);
        }
    }
    return $sanitized_input;
}

/**
 * Sanitize addresses options
 */
function fancy_helpers_sanitize_addresses_options($input) {
    $sanitized_input = array();
    if (isset($input['addresses']) && is_array($input['addresses'])) {
        foreach ($input['addresses'] as $index => $address) {
            $sanitized_input['addresses'][$index]['name'] = sanitize_text_field($address['name']);
            $sanitized_input['addresses'][$index]['address'] = wp_kses($address['address'], array(
                'br' => array(),
                'p' => array(),
                'strong' => array(),
                'em' => array(),
            ));
        }
    }
    return $sanitized_input;
}

/**
 * Sanitize phones options
 */
function fancy_helpers_sanitize_phones_options($input) {
    $sanitized_input = array();
    if (isset($input['phones']) && is_array($input['phones'])) {
        foreach ($input['phones'] as $index => $phone) {
            $sanitized_input['phones'][$index]['name'] = sanitize_text_field($phone['name']);
            $sanitized_input['phones'][$index]['number'] = sanitize_text_field($phone['number']);
        }
    }
    return $sanitized_input;
}

/**
 * Sanitize emails options
 */
function fancy_helpers_sanitize_emails_options($input) {
    $sanitized_input = array();
    if (isset($input['emails']) && is_array($input['emails'])) {
        foreach ($input['emails'] as $index => $email) {
            $sanitized_input['emails'][$index]['name'] = sanitize_text_field($email['name']);
            $sanitized_input['emails'][$index]['address'] = sanitize_email($email['address']);
        }
    }
    return $sanitized_input;
}

/**
 * Sanitize social media options
 */
function fancy_helpers_sanitize_social_media_options($input) {
    $sanitized_input = array();
    if (isset($input['social_media']) && is_array($input['social_media'])) {
        foreach ($input['social_media'] as $index => $platform) {
            $sanitized_input['social_media'][$index]['name'] = sanitize_text_field($platform['name']);
            $sanitized_input['social_media'][$index]['displayname'] = sanitize_text_field($platform['displayname']);
            $sanitized_input['social_media'][$index]['url'] = esc_url_raw($platform['url']);
        }
    }
    return $sanitized_input;
}

/**
 * Sanitize contacts options
 */
function fancy_helpers_sanitize_contacts_options($input) {
    $sanitized_input = array();
    if (isset($input['contacts']) && is_array($input['contacts'])) {
        foreach ($input['contacts'] as $index => $contact) {
            $sanitized_input['contacts'][$index]['name'] = sanitize_text_field($contact['name']);
            $sanitized_input['contacts'][$index]['phone'] = sanitize_text_field($contact['phone']);
            $sanitized_input['contacts'][$index]['email'] = sanitize_email($contact['email']);
        }
    }
    return $sanitized_input;
}

/**
 * Sanitize custom options
 */
function fancy_helpers_sanitize_customs_options($input) {
    $sanitized_input = array();
    if (isset($input['customs']) && is_array($input['customs'])) {
        foreach ($input['customs'] as $index => $custom) {
            $sanitized_input['customs'][$index]['name']  = sanitize_text_field($custom['name'] ?? '');
            $sanitized_input['customs'][$index]['value'] = wp_kses_post($custom['value'] ?? '');
        }
    }
    return $sanitized_input;
}
register_setting('fancy_helpers_contacts_customs_options', 'fancy_helpers_contacts_customs_options', 'fancy_helpers_sanitize_customs_options');
register_setting('fancy_helpers_contacts_contacts_options', 'fancy_helpers_contacts_contacts_options', 'fancy_helpers_sanitize_contacts_options');
