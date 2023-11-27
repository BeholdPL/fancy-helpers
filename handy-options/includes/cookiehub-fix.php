<?php

function ho_cookiehub_fix() {
    echo '<style>body .ch2 button{font-family:var(--theme-button-font-family)!important;font-size:var(--theme-button-font-size)!important;font-weight:var(--theme-button-font-weight)!important;font-style:var(--theme-button-font-style)!important;line-height:var(--theme-button-line-height)!important;letter-spacing:var(--theme-button-letter-spacing)!important;text-transform:var(--theme-button-text-transform)!important;text-decoration:var(--theme-button-text-decoration)!important;text-align:center!important}</style>';
}
add_action('wp_footer', 'ho_cookiehub_fix');