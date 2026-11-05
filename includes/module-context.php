<?php
/** Read module structure from the existing language-specific WordPress menu. */
function spark_makeover_menu_label( $title ) {
    $title = wp_strip_all_tags( preg_replace( '/<br\s*\/?\s*>/i', ' ', $title ) );
    return trim( preg_replace( '/^\s*(?:\d+(?:\.\d+)*[.)]?\s+|[•–—-]\s*)/u', '', html_entity_decode( $title, ENT_QUOTES, 'UTF-8' ) ) );
}

function spark_makeover_module_context( $page_id, $location ) {
    static $menus = array();
    if ( ! isset( $menus[ $location ] ) ) {
        $locations = get_nav_menu_locations();
        $menus[ $location ] = isset( $locations[ $location ] ) ? ( wp_get_nav_menu_items( $locations[ $location ] ) ?: array() ) : array();
    }
    $items = $menus[ $location ];
    $by_id = array();
    foreach ( $items as $item ) {
        if ( ! in_array( 'menu-nav-section-head', (array) $item->classes, true ) ) {
            $by_id[ $item->ID ] = $item;
        }
    }
    $context = array( 'items' => $items, 'path' => array(), 'current_item_id' => 0, 'module' => null, 'position' => 0, 'total' => 0 );
    foreach ( $by_id as $item ) {
        if ( 'post_type' !== $item->type || (int) $item->object_id !== (int) $page_id ) {
            continue;
        }
        $path = array( $item );
        $parent = (int) $item->menu_item_parent;
        $seen = array( $item->ID => true );
        while ( isset( $by_id[ $parent ] ) && ! isset( $seen[ $parent ] ) ) {
            $seen[ $parent ] = true;
            array_unshift( $path, $by_id[ $parent ] );
            $parent = (int) $by_id[ $parent ]->menu_item_parent;
        }
        // Prefer the lesson entry over the module's duplicate first-page link.
        if ( count( $path ) > count( $context['path'] ) ) {
            $context['path'] = $path;
            $context['current_item_id'] = (int) $item->ID;
            $context['module'] = $path[0];
        }
    }
    if ( ! $context['module'] ) {
        return $context;
    }
    $pages = array();
    foreach ( $by_id as $item ) {
        if ( 'post_type' !== $item->type || ! $item->menu_item_parent ) {
            continue;
        }
        $parent = (int) $item->menu_item_parent;
        $seen = array( $item->ID => true );
        while ( isset( $by_id[ $parent ] ) && ! isset( $seen[ $parent ] ) ) {
            if ( $parent === (int) $context['module']->ID ) {
                $slug = (string) get_post_field( 'post_name', $item->object_id );
                if ( '-module-objectives' !== substr( $slug, -18 ) && ! in_array( 'objectives-module-item', (array) $item->classes, true ) ) {
                    $pages[ (int) $item->object_id ] = true;
                }
                break;
            }
            $seen[ $parent ] = true;
            $parent = (int) $by_id[ $parent ]->menu_item_parent;
        }
    }
    $index = array_search( (int) $page_id, array_keys( $pages ), true );
    if ( false !== $index ) {
        $context['position'] = $index + 1;
        $context['total'] = count( $pages );
    }
    return $context;
}

/** Prefer short localized menu labels, with an existing ACF/page-title fallback. */
function spark_makeover_page_label( $page_id, $language, $items = array() ) {
    $title = '';
    foreach ( $items as $item ) {
        if ( 'post_type' === $item->type && (int) $item->object_id === (int) $page_id && $item->menu_item_parent ) {
            $title = spark_makeover_menu_label( $item->title );
        }
    }
    if ( '' === $title && 'fr' === $language ) {
        $title = (string) get_field( 'title_fr', $page_id );
    }
    return '' !== trim( $title ) ? $title : wp_strip_all_tags( get_the_title( $page_id ) );
}

/** Remove the redundant stage entry and identify a single current lesson. */
function spark_makeover_module_menu_items( $items, $args ) {
    if ( empty( $args->spark_makeover_module_menu ) ) {
        return $items;
    }
    $result = array();
    foreach ( $items as $item ) {
        if ( in_array( 'menu-nav-section-head', (array) $item->classes, true ) ) {
            continue;
        }
        $item->current = (int) $item->ID === (int) $args->spark_current_menu_item;
        if ( ! $item->current ) {
            $item->classes = array_diff( $item->classes, array( 'current-menu-item', 'current_page_item' ) );
        } else {
            $item->classes[] = 'spark-current-lesson';
        }
        $result[] = $item;
    }
    return $result;
}
add_filter( 'wp_nav_menu_objects', 'spark_makeover_module_menu_items', 10, 2 );
