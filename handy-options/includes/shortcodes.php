<?php

// Post title
function ho_title_shortcode( $atts ) {
    // Pobierz bieżący post
    global $post;
    
    // Sprawdź, czy istnieje bieżący post i czy użytkownik ma odpowiednie uprawnienia
    if ( isset( $post ) && ! empty( $post ) && current_user_can( 'read_post', $post->ID ) ) {
        // Pobierz tytuł postu i zastosuj escaping
        $post_title = esc_html( get_the_title( $post ) );
        
        // Zwróć shortcode zawierający tytuł postu
        return $post_title;
    }
}
add_shortcode( 'ho_post_title', 'ho_title_shortcode' );

// Post date
function ho_post_date_shortcode( $atts ) {
    // Pobierz bieżący post
    global $post;
    
    // Sprawdź, czy istnieje bieżący post i czy użytkownik ma odpowiednie uprawnienia
    if ( isset( $post ) && ! empty( $post ) && current_user_can( 'read_post', $post->ID ) ) {
        // Pobierz datę publikacji postu i zastosuj escaping
        $post_date = esc_html( get_the_date( '', $post ) );
        
        // Zwróć shortcode zawierający datę publikacji postu
        return $post_date;
    }
}
add_shortcode( 'ho_post_date', 'ho_post_date_shortcode' );

function ho_custom_taxonomy_terms_shortcode( $atts ) {
    // Pobierz atrybuty i zweryfikuj ich poprawność
    $atts = shortcode_atts( array(
        'taxonomy' => '', // Nazwa taksonomii
        'separator' => ',', // Separator między termami
    ), $atts );

    // Sprawdź, czy podano taksonomię
    if ( ! empty( $atts['taxonomy'] ) && taxonomy_exists( $atts['taxonomy'] ) ) {
        // Pobierz nazwę taksonomii i zastosuj escaping
        $taxonomy = esc_attr( $atts['taxonomy'] );
        $separator = esc_attr( $atts['separator'] );

        // Dozwolone wartości separatora
        $allowed_separators = array(
            ',',
            ' ,',
            ' , ',
            ', ',
            '-',
            ' - ',
            ' -',
            '- ',
            '•',
            ' • ',
            ' •',
            '• ',
            '/',
            ' / ',
            ' /',
            '/ ',
            '|',
            ' | ',
            ' |',
            '| ',
            '#',
            ' # ',
            ' #',
            '# ',
            ' '
        );

        // Sprawdź, czy podany separator jest dozwolony
        if ( ! in_array( $separator, $allowed_separators ) ) {
            $separator = ', '; // Ustaw domyślny separator, jeśli podany separator nie jest dozwolony
        }

        // Pobierz ID bieżącego postu
        $post_id = get_the_ID();

        // Sprawdź, czy bieżący post ma przypisane termy dla podanej taksonomii
        if ( has_term( '', $taxonomy, $post_id ) ) {
            // Pobierz termy przypisane do bieżącego postu w podanej taksonomii
            $terms = get_the_terms( $post_id, $taxonomy );

            // Sprawdź, czy znaleziono termy
            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                // Zbierz nazwy termów w formie linków z zastosowaniem escaping
                $term_links = array();
                foreach ( $terms as $term ) {
                    $term_link = esc_url( get_term_link( $term, $taxonomy ) );
                    $term_links[] = '<a href="' . $term_link . '">' . esc_html( $term->name ) . '</a>';
                }

                // Zwróć shortcode z nazwami termów jako linki, używając odpowiedniego separatora
                return implode( $separator, $term_links );
            }
        }
    }
}
add_shortcode( 'ho_taxonomy_terms', 'ho_custom_taxonomy_terms_shortcode' );


// Post modification date
function post_modified_date_shortcode() {
    // Pobierz bieżący post
    global $post;
    
    // Sprawdź, czy istnieje bieżący post i czy użytkownik ma odpowiednie uprawnienia
    if ( isset( $post ) && ! empty( $post ) && current_user_can( 'read_post', $post->ID ) ) {
        // Pobierz datę modyfikacji postu i zastosuj escaping
        $modified_date = esc_html( get_the_modified_date() );
        
        // Pobierz datę publikacji postu i zastosuj escaping
        $published_date = esc_html( get_the_date() );
        
        // Sprawdź, czy post był modyfikowany
        if ( $modified_date !== $published_date ) {
            // Wyświetl ostatnią datę modyfikacji
            return $modified_date;
        } else {
            // Wyświetl datę publikacji
            return $published_date;
        }
    }
}
add_shortcode( 'ho_post_modified_date', 'post_modified_date_shortcode' );

// Custom post meta
function ho_custom_meta_shortcode( $atts ) {
    // Sprawdź, czy przekazano nazwę meta jako atrybut i zweryfikuj jej poprawność
    if ( isset( $atts['name'] ) && ! empty( $atts['name'] ) && is_string( $atts['name'] ) ) {
        // Pobierz nazwę meta z atrybutów i zastosuj escaping
        $meta_name = esc_attr( $atts['name'] );
        
        // Pobierz wartość meta na podstawie nazwy i zastosuj escaping
        $meta_value = esc_html( get_post_meta( get_the_ID(), $meta_name, true ) );
        
        // Sprawdź, czy wartość meta istnieje
        if ( ! empty( $meta_value ) ) {
            // Zwróć wartość meta jako shortcode
            return $meta_value;
        }
    }
}
add_shortcode( 'ho_custom_meta', 'ho_custom_meta_shortcode' );

// Current year
function ho_current_year_shortcode() {
    // Pobierz bieżący rok i zastosuj escaping
    $year = esc_html( date('Y') );
    return $year;
}
add_shortcode('ho_current_year', 'ho_current_year_shortcode');
