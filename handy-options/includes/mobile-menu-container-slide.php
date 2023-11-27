<?php

function ho_enable_blocksy_mobile_menu_slide() {
    ?>
    <style>
        :root {
            --negative-side-panel-width: calc(-1 * var(--main-container-translate));
        }

        #main-container {
            transition: transform .3s;
        }

        [data-panel="in:right"] #main-container {
            transform: translateX(var(--negative-side-panel-width));
        }

        [data-panel="in:left"] #main-container {
            transform: translateX(var(--main-container-translate));
        }

        [data-panel="in:right"] #main-container .ct-toggle {
            opacity: 0;
        }
    </style>
    <script type="text/javascript">
        // Pobieramy element o klasie .ct-panel-inner
        const panelInner = document.querySelector('.ct-panel-inner');

        // Pobieramy szerokość elementu
        const width = panelInner.clientWidth;

        // Ustawiamy wartość szerokości jako zmienną CSS
        document.documentElement.style.setProperty('--main-container-translate', `${width}px`);
    </script>
    <?php
}
add_action('wp_footer', 'ho_enable_blocksy_mobile_menu_slide');