<?php

/**
 * Prevent orphan words in content by adding non-breaking spaces
 * 
 * This script adds non-breaking spaces after specific short words 
 * and conjunctions to improve typography and prevent single words 
 * from appearing at the end of lines.
 */

/**
 * Add non-breaking spaces to content to prevent orphan words
 * 
 * @param string $content The original page/post content
 * @return string Modified content with non-breaking spaces
 */
function fancy_helpers_add_nbsp_to_specific_chars($content) {
    // Skip modifications in the admin panel
    if (is_admin()) {
        return $content;
    }

    // Process shortcodes
    $content = do_shortcode($content);

    // Remove HTML tags, leaving only text
    $text = strip_tags($content);

    // Define pattern for words that should have non-breaking space after them
    $pattern = '/\b(i|o|w|z|a|i|z|w|o|u|na|np\.|nt\.|że|do|za|na|ku|po|ni|bo|dla|czy|lub|pod|ale|aby|ani|nad|zaś|prof\.|znad|przy|spod|oraz|albo|bądź|obok|choć|lecz|koło|więc|przy|spoza|przez|czyli|zatem|toteż|jeżeli|dokoła|jednak|przeto|tudzież|to jest|dlatego|ponieważ|natomiast|mianowicie|aczkolwiek|as|so|or|if|at|in|on|and|but|nor|e\.g\.|for|yet|now|til|who|why|lest|once|even|than|that|when|after|as if|since|until|which|where|while|before|though|unless|whoever|even if|because|whereas|just as|so that|if only|if when|if then|whether|where if|wherever|inasmuch|provided|although|now when|now that|whenever|as though|now since|supposing|as long as|as much as|as soon as|rather than|even though|in order that|provided that)(\s)/';
    $replacement = '$1&nbsp;';

    // Replace space with non-breaking space
    $text = preg_replace($pattern, $replacement, $text);

    // Restore original HTML tags
    $content = preg_replace_callback('/<[^>]+>/', function($matches) {
        return $matches[0];
    }, $content);

    // Replace text content with modified version
    $content = preg_replace_callback('/(>)([^<]+)(<)/', function($matches) use ($text) {
        return $matches[1] . preg_replace('/\b(i|o|w|z|a|i|z|w|o|u|na|np\.|nt\.|że|do|za|na|ku|po|ni|bo|dla|czy|lub|pod|ale|aby|ani|nad|zaś|prof\.|znad|przy|spod|oraz|albo|bądź|obok|choć|lecz|koło|więc|przy|spoza|przez|czyli|zatem|toteż|jeżeli|dokoła|jednak|przeto|tudzież|to jest|dlatego|ponieważ|natomiast|mianowicie|aczkolwiek|as|so|or|if|at|in|on|and|but|nor|e\.g\.|for|yet|now|til|who|why|lest|once|even|than|that|when|after|as if|since|until|which|where|while|before|though|unless|whoever|even if|because|whereas|just as|so that|if only|if when|if then|whether|where if|wherever|inasmuch|provided|although|now when|now that|whenever|as though|now since|supposing|as long as|as much as|as soon as|rather than|even though|in order that|provided that)(\s)/', '$1&nbsp;', $matches[2]) . $matches[3];
    }, $content);

    return $content;
}

function fancy_helpers_add_nbsp_to_title($title) {
    // Skip modifications in the admin panel
    if (is_admin()) {
        return $title;
    }

    // Process shortcodes in the title
    $title = do_shortcode($title);

    // Remove HTML tags, leaving only text
    $text = strip_tags($title);

    // Define pattern for words that should have non-breaking space after them
    $pattern = '/\b(i|o|w|z|a|i|z|w|o|u|na|np\.|nt\.|że|do|za|na|ku|po|ni|bo|dla|czy|lub|pod|ale|aby|ani|nad|zaś|prof\.|znad|przy|spod|oraz|albo|bądź|obok|choć|lecz|koło|więc|przy|spoza|przez|czyli|zatem|toteż|jeżeli|dokoła|jednak|przeto|tudzież|to jest|dlatego|ponieważ|natomiast|mianowicie|aczkolwiek|as|so|or|if|at|in|on|and|but|nor|e\.g\.|for|yet|now|til|who|why|lest|once|even|than|that|when|after|as if|since|until|which|where|while|before|though|unless|whoever|even if|because|whereas|just as|so that|if only|if when|if then|whether|where if|wherever|inasmuch|provided|although|now when|now that|whenever|as though|now since|supposing|as long as|as much as|as soon as|rather than|even though|in order that|provided that)(\s)/';
    $replacement = '$1&nbsp;';

    // Replace space with non-breaking space
    $text = preg_replace($pattern, $replacement, $text);

    // Restore original HTML tags
    $title = preg_replace_callback('/<[^>]+>/', function($matches) {
        return $matches[0];
    }, $title);

    // Replace text content with modified version
    $title = preg_replace_callback('/(>)([^<]+)(<)/', function($matches) use ($text) {
        return $matches[1] . preg_replace('/\b(i|o|w|z|a|i|z|w|o|u|na|np\.|nt\.|że|do|za|na|ku|po|ni|bo|dla|czy|lub|pod|ale|aby|ani|nad|zaś|prof\.|znad|przy|spod|oraz|albo|bądź|obok|choć|lecz|koło|więc|przy|spoza|przez|czyli|zatem|toteż|jeżeli|dokoła|jednak|przeto|tudzież|to jest|dlatego|ponieważ|natomiast|mianowicie|aczkolwiek|as|so|or|if|at|in|on|and|but|nor|e\.g\.|for|yet|now|til|who|why|lest|once|even|than|that|when|after|as if|since|until|which|where|while|before|though|unless|whoever|even if|because|whereas|just as|so that|if only|if when|if then|whether|where if|wherever|inasmuch|provided|although|now when|now that|whenever|as though|now since|supposing|as long as|as much as|as soon as|rather than|even though|in order that|provided that)(\s)/', '$1&nbsp;', $matches[2]) . $matches[3];
    }, $title);

    return $title;
}

// Filter for content
add_filter('the_content', 'fancy_helpers_add_nbsp_to_specific_chars');
// Filter for titles
add_filter('the_title', 'fancy_helpers_add_nbsp_to_title');