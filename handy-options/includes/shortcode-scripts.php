<?php

function ho_contacts_shortcode_script() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
    const shortcodeElements = document.querySelectorAll('.shortcode');

        // Dodajemy nasłuchiwacz na kliknięcie wszystkich elementów .shortcode
        shortcodeElements.forEach(function (shortcodeElement) {
            shortcodeElement.addEventListener('click', function () {
                // Tworzymy ukryty element textarea
                const textarea = document.createElement('textarea');
                textarea.value = shortcodeElement.textContent; // Kopiujemy zawartość do textarea
                document.body.appendChild(textarea);

                // Zaznaczamy zawartość textarea
                textarea.select();

                try {
                    // Kopiujemy zaznaczony tekst do schowka
                    document.execCommand('copy');
                    alert('<?php echo esc_html__( 'Shortcode copied.', 'handy-options' ) ?>');
                } catch (err) {
                    alert('<?php echo esc_html__("Can't copy shortcode, your browser doesn't support this option.", 'handy-options' ) ?>');
                }

                // Usuwamy tymczasowy textarea
                document.body.removeChild(textarea);
            });
        });
    });
    </script>
    <?php
}
add_action('admin_footer', 'ho_contacts_shortcode_script');