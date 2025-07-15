<?php

/**
 * Class Fancy_Helpers_Update_Checker
 * 
 * Handles the update checking and information retrieval for the Fancy Helpers plugin.
 */
class Fancy_Helpers_Update_Checker {
    private $slug;
    private $api_url;

    /**
     * Constructor.
     *
     * @param string $slug The plugin slug.
     * @param string $api_url The URL of the update API.
     */
    public function __construct($slug, $api_url) {
        $this->slug = $slug;
        $this->api_url = $api_url;

        // Add filters for update checking and plugin information
        add_filter('pre_set_site_transient_update_plugins', array($this, 'check_for_update'));
        add_filter('plugins_api', array($this, 'plugin_info'), 20, 3);
    }

    /**
     * Check for updates.
     *
     * @param object $transient The pre-set transient for plugin updates.
     * @return object The modified transient with update information.
     */
    public function check_for_update($transient) {
        // If the transient doesn't contain checked information, return it.
        if (empty($transient->checked)) {
            return $transient;
        }

        // Get the path to the main plugin file
        $plugin_path = $this->slug . '/' . $this->slug . '.php';
        
        // Get the current plugin data
        $plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin_path);
        $current_version = $plugin_data['Version'];

        // Send a request to the API to check for updates
        $response = wp_remote_post($this->api_url, array(
            'body' => array(
                'action' => 'check_update',
                'slug' => $this->slug,
                'version' => $current_version,
            )
        ));

        // If the request failed, return the original transient
        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
            return $transient;
        }

        // Decode the API response
        $result = json_decode(wp_remote_retrieve_body($response), true);

        // If a new version is available, add it to the transient
        if (isset($result['version']) && version_compare($result['version'], $current_version, '>')) {
            $transient->response[$plugin_path] = (object) array(
                'slug' => $this->slug,
                'new_version' => $result['version'],
                'package' => $result['download_url'],
                'tested' => isset($result['tested']) ? $result['tested'] : '',
                'requires' => isset($result['requires']) ? $result['requires'] : '',
                'requires_php' => isset($result['requires_php']) ? $result['requires_php'] : '',
            );
        }

        return $transient;
    }

    /**
     * Retrieve plugin information for the WordPress updates screen.
     *
     * @param false|object|array $res The result object or array.
     * @param string $action The API action being performed.
     * @param object $args Plugin API arguments.
     * @return false|object Plugin information.
     */
    public function plugin_info($res, $action, $args) {
        // If this is not a plugin information request, return the original result
        if ($action !== 'plugin_information') {
            return $res;
        }

        // If this is not for our plugin, return the original result
        if ($this->slug !== $args->slug) {
            return $res;
        }

        // Send a request to the API to get plugin information
        $response = wp_remote_post($this->api_url, array(
            'body' => array(
                'action' => 'plugin_information',
                'slug' => $this->slug,
            )
        ));

        // If the request failed, return the original result
        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
            return $res;
        }

        // Decode the API response
        $result = json_decode(wp_remote_retrieve_body($response), true);

        // If we have a result, format it for WordPress
        if ($result) {
            $res = (object) $result;
            $res->sections = (array) $res->sections;
            $res->banners = (array) $res->banners;
            $res->icons = (array) $res->icons;
        }

        return $res;
    }
}

/**
 * Initialize the update checker.
 */
function fancy_helpers_init_updater() {
    $updater = new Fancy_Helpers_Update_Checker(
        'fancy-helpers',
        'https://plugins.behold.pl/fancy-helpers/update-api.php'
    );
}
add_action('init', 'fancy_helpers_init_updater');