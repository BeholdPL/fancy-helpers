<?php

function ho_unify_rank_math_icon() {
    wp_enqueue_style( 'rank-math-icon', plugins_url( 'admin/css/ho-rank-math-icon.css', __DIR__ ), array() );
}
add_action( 'enqueue_block_editor_assets', 'ho_unify_rank_math_icon' );