<?php

add_action('admin_notices', 'aql_output');
function aql_output() {

    if ( ! isset(get_nav_menu_locations()['admin_quick_links']) ) return;
    $aql_options = get_option( 'admin_quick_links_option_name' );
    if ( ! isset($aql_options['enable_disable_0']) ) return;

    ob_start();
    ?>
    <div class="aql-container <?php echo ( $aql_options['aql_select_size_2'] ) ?: 'aql-size-default'; ?>">
        <nav class="aql-bar">
            <ul class="aql-bar-items">
                <?php wp_nav_menu( array(
                    'theme_location' => 'admin_quick_links',
                    'container' => false,
                    'items_wrap' => '%3$s',
                ) ); ?>
            </ul>
        </nav>
    </div>
    <?php
    $output = ob_get_clean();
    echo $output;
}


// WP Bar for front-end quick links

function aql_quick_links_admin_bar($wp_admin_bar) {
    if ( ! isset(get_nav_menu_locations()['admin_quick_links']) ) return;
    if ( is_admin() ) return;
    $aql_options = get_option( 'admin_quick_links_option_name' );
    if ( ! isset($aql_options['enable_disable_0']) ) return;
    
    $aql_links = wp_get_nav_menu_items(get_nav_menu_locations()['admin_quick_links']);
    if ( ! $aql_links ) return;
    global $wp_admin_bar;
    $wp_admin_bar->add_menu( array(
        'id' => 'admin-quick-links',
        'title' => 'Quick Links',
        'icon' => 'dashicons-settings',
        'href' => false,
        'meta' => array(
            'class' => 'aql-menu-item'
        )
    ) );
    foreach ( $aql_links as $aql_key => $aql_link ) {
        $wp_admin_bar->add_menu(
            array(
                'id' => $aql_link->ID,
                'parent' => 'admin-quick-links',
                'title' => $aql_link->title,
                'href' => $aql_link->url,
                'meta' => array(
                    'target' =>  $aql_link->target,
                )
            )
        );
    }
}

add_action('admin_bar_menu', 'aql_quick_links_admin_bar', 100);