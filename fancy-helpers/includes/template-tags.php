<?php
/*
 * tct_template_tags
 *
 * @function Automatically calls template tags
 * @author Taylor Drayson
 * @since 26/03/2023
 * @updated 15/11/2023
 */
add_filter("render_block", "tct_template_tags", 10, 2);
function tct_template_tags($block_content, $block){
    $prefixes = ["fn", "query", "theme", "field", "user", "userm", "author", "hook"];
    $pattern = "/(?:http:\/\/)?{{(" . implode("|", $prefixes) . ")\.([\w-]+)}}/";

    $new_content = preg_replace_callback($pattern, function ($matches) {
        $dynamic_tag = trim($matches[2]);

        switch ($matches[1]) {
            case "fn":
                if (!function_exists($dynamic_tag)) {
                    return;
                }
                return call_user_func($dynamic_tag);
            case "query": // Query param
                return isset($_GET[$dynamic_tag])
                    ? sanitize_text_field($_GET[$dynamic_tag])
                    : "";
            case "theme": // Options page
                if (!function_exists("get_field")) break;
                return get_field($dynamic_tag, "options");
            case "field": // ACF Field
                if (!function_exists("get_field")) break;
                return get_field($dynamic_tag);
            case "user": // Supported user info https://tct.so/User-info
                $current_user = wp_get_current_user();
                return $current_user->$dynamic_tag;
            case "userm": // User meta
                $user_id = get_current_user_id();
                return get_user_meta($user_id, $dynamic_tag, true);
            case "author": // Supported author info https://tct.so/author-values
                return get_the_author_meta($dynamic_tag);
            case "hook":
                ob_start();
                do_action($dynamic_tag);
                return ob_get_clean();
        }

        // Otherwise, return the original text
        return $matches[0];
    }, $block_content);

    return $new_content;
}

/*
 * bhld_template_tags
 *
 * @function Automatically calls template tags for array in ACF fields 
 * @author Behold
 * @since 30.03.2024
 */
add_filter('the_content', 'bhld_acfarray_template_tag', 20);
function bhld_acfarray_template_tag($content) {
    preg_match_all('/{{(fieldarray|themearray)\.(.*?)\|(.*?)}}/', $content, $matches, PREG_SET_ORDER);

    foreach ($matches as $match) {
        $array_type = $match[1];
        $acf_field = sanitize_text_field($match[2]);
        $array_key = sanitize_text_field($match[3]);

        if ($array_type === 'fieldarray') {
            $acf_value = get_field($acf_field);
        } elseif ($array_type === 'themearray') {
            $acf_value = get_field($acf_field, 'option');
        }

        if (is_array($acf_value)) {
            if ($array_key === 'url-name' && array_key_exists('url', $acf_value)) {
                $url = str_replace(array('http://', 'https://'), '', $acf_value['url']);
                $content = str_replace($match[0], $url, $content);
            } else {
                if (array_key_exists($array_key, $acf_value)) {
                    $content = str_replace($match[0], sanitize_text_field($acf_value[$array_key]), $content);
                } else {
                    $content = str_replace($match[0], '', $content);
                }
            }
        } else {
            $content = str_replace($match[0], '', $content);
        }
    }

    return $content;
}
