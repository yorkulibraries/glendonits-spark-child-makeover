<?php
require_once __DIR__ . '/includes/module-context.php';

/**
 * Load child styles after the parent theme and its accessibility styles.
 */
function glendonits_spark_is_bootstrap5_page() {
	if ( is_page_template( 'home-page.php' ) || is_page( 'site-map' ) ) {
		return true;
	}

	return is_page() && (int) get_post_meta( get_queried_object_id(), 'spark_topic', true ) > 0;
}

function glendonits_spark_child_enqueue_styles() {
    $bootstrap5_page = glendonits_spark_is_bootstrap5_page();
    $module_page     = is_page() && (int) get_post_meta( get_queried_object_id(), 'spark_topic', true ) > 0;

    wp_enqueue_style(
        'glendonits-spark-ibm-plex',
        'https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=IBM+Plex+Sans+Condensed:wght@400;500;600;700&display=swap',
        array(),
        null
    );

    if ( $bootstrap5_page ) {
        wp_dequeue_style( 'bootstrap' );
        wp_dequeue_style( 'smartmenus-style' );
        wp_dequeue_script( 'bootstrap-script' );
        wp_dequeue_script( 'bootstrap-tabcollapse' );
        wp_dequeue_script( 'bootstrap-smartmenus' );

        $bootstrap_path = get_stylesheet_directory() . '/assets/vendor/bootstrap-5.3.8';
        $bootstrap_uri  = get_stylesheet_directory_uri() . '/assets/vendor/bootstrap-5.3.8';

        wp_enqueue_style(
            'glendonits-spark-bootstrap5',
            $bootstrap_uri . '/bootstrap.min.css',
            array( 'spark-accessibility' ),
            filemtime( $bootstrap_path . '/bootstrap.min.css' )
        );

        wp_enqueue_script(
            'glendonits-spark-bootstrap5',
            $bootstrap_uri . '/bootstrap.bundle.min.js',
            array(),
            filemtime( $bootstrap_path . '/bootstrap.bundle.min.js' ),
            true
        );
    }

    if ( is_page_template( 'home-page.php' ) ) {
        wp_enqueue_script(
            'glendonits-spark-legacy-homepage-media',
            get_template_directory_uri() . '/spark_gui.js',
            array( 'jquery' ),
            filemtime( get_template_directory() . '/spark_gui.js' ),
            true
        );

        // Homepage module links remain visible; no collapse script is needed.
    } elseif ( $module_page ) {
        wp_enqueue_style(
            'glendonits-spark-module-dialog',
            get_template_directory_uri() . '/dialog.css',
            array( 'glendonits-spark-bootstrap5' ),
            filemtime( get_template_directory() . '/dialog.css' )
        );
        wp_enqueue_style(
            'glendonits-spark-module-gui',
            get_template_directory_uri() . '/spark_gui.css',
            array( 'glendonits-spark-bootstrap5' ),
            filemtime( get_template_directory() . '/spark_gui.css' )
        );
        wp_enqueue_script(
        'glendonits-spark-module-content',
        get_template_directory_uri() . '/spark_content.js',
        array( 'jquery' ),
        filemtime( get_template_directory() . '/spark_content.js' ),
        true
        );
        wp_add_inline_script(
        'glendonits-spark-module-content',
        'var baseUrl = ' . wp_json_encode( home_url() ) . ';',
        'before'
        );
        wp_enqueue_script(
            'glendonits-spark-module-navigation',
            get_stylesheet_directory_uri() . '/assets/js/module-navigation.js',
            array(),
            filemtime( get_stylesheet_directory() . '/assets/js/module-navigation.js' ),
            true
        );
        wp_add_inline_script(
            'accordion-shortcodes-script',
            'if ( Array.isArray( window.accordionShortcodesSettings ) ) {
                window.accordionShortcodesSettings.forEach( function ( settings ) {
                    settings.clickToClose = true;
                } );
            }',
            'after'
        );
    }

    $style_dependencies = array( 'spark-accessibility', 'glendonits-spark-ibm-plex' );
    if ( $bootstrap5_page ) {
        $style_dependencies[] = 'glendonits-spark-bootstrap5';
    }
    if ( $module_page ) {
        $style_dependencies[] = 'glendonits-spark-module-dialog';
        $style_dependencies[] = 'glendonits-spark-module-gui';
    }

    wp_enqueue_style(
        'glendonits-spark-child-style',
        get_stylesheet_uri(),
        $style_dependencies,
        filemtime( get_stylesheet_directory() . '/style.css' )
    );
}
add_action( 'wp_enqueue_scripts', 'glendonits_spark_child_enqueue_styles', 20 );

/** Present the existing homepage menu as three always-open learning pillars. */
function spark_makeover_homepage_pillar_heading( $output, $item, $depth, $args ) {
    if ( empty( $args->spark_makeover_pillars ) || 0 !== $depth ) {
        return $output;
    }

    $key = sanitize_title( wp_strip_all_tags( $item->title ) );
    $descriptions = array(
        'getting-started' => 'Understand your assignment, plan your time and choose a focused topic.',
        'exploring' => 'Find reliable sources, read actively and organize your ideas.',
        'pulling-it-together' => 'Develop your draft, cite your sources and revise with confidence.',
        'putting-it-together' => 'Develop your draft, cite your sources and revise with confidence.',
        'commencer-le-travail' => 'Comprenez les exigences du travail, planifiez votre temps et choisissez un sujet.',
        'approfondir-un-sujet' => 'Trouvez des sources fiables, lisez activement et organisez vos idées.',
        'organiser-et-executer-le-travail' => 'Rédigez votre travail, citez vos sources et révisez avec confiance.',
    );
    $description = trim( (string) $item->description );
    if ( '' === $description ) {
        $description = isset( $descriptions[ $key ] ) ? $descriptions[ $key ] : '';
    }
    $title = 'pulling-it-together' === $key ? 'Putting It Together' : $item->title;
    $icons = array(
        'getting-started' => 'fa-compass',
        'commencer-le-travail' => 'fa-compass',
        'exploring' => 'fa-search',
        'approfondir-un-sujet' => 'fa-search',
        'pulling-it-together' => 'fa-pencil',
        'putting-it-together' => 'fa-pencil',
        'organiser-et-executer-le-travail' => 'fa-pencil',
    );
    $icon = isset( $icons[ $key ] ) ? $icons[ $key ] : 'fa-book';

    return '<div class="spark-pillar-heading"><h2><i class="fa ' . esc_attr( $icon ) . ' spark-pillar-icon" aria-hidden="true"></i><span>' . esc_html( wp_strip_all_tags( $title ) ) . '</span></h2>'
        . ( '' !== $description ? '<p>' . esc_html( $description ) . '</p>' : '' ) . '</div>';
}
add_filter( 'walker_nav_menu_start_el', 'spark_makeover_homepage_pillar_heading', 10, 4 );

/** Give homepage module links a decorative forward cue. */
function spark_makeover_homepage_module_link_title( $title, $item, $args, $depth ) {
    if ( empty( $args->spark_makeover_pillars ) || $depth < 1 ) {
        return $title;
    }

    return '<span class="spark-pillar-module-label">' . $title . '</span><i class="fa fa-arrow-right spark-pillar-module-arrow" aria-hidden="true"></i>';
}
add_filter( 'nav_menu_item_title', 'spark_makeover_homepage_module_link_title', 10, 4 );

/** Add decorative icons while preserving the CMS-managed support links. */
function spark_makeover_support_link_title( $title, $item, $args, $depth ) {
    if ( empty( $args->spark_makeover_support ) ) {
        return $title;
    }

    $url = strtolower( (string) $item->url );
    $icon = 'fa-life-ring';
    $services = array(
        'learningcommons.yorku.ca' => 'fa-graduation-cap',
        'lss.info.yorku.ca' => 'fa-compass',
        'career-skills-centre' => 'fa-compass',
        'writing-centre' => 'fa-pencil',
        'redaction' => 'fa-pencil',
        'ask-services' => 'fa-book',
        'questions-et-services' => 'fa-book',
        'eslolc' => 'fa-comments',
        'faculty-teaching-with-spark' => 'fa-graduation-cap',
    );
    foreach ( $services as $match => $service_icon ) {
        if ( false !== strpos( $url, $match ) ) {
            $icon = $service_icon;
            break;
        }
    }

    // Remove legacy forced line breaks; let each card wrap naturally.
    $label = wp_strip_all_tags( preg_replace( '/<br\s*\/?\s*>/i', ' ', $title ) );
    $external_indicator = '';
    if ( in_array( 'spark-external-link', (array) $item->classes, true ) ) {
        $external_label = isset( $_SESSION['slang'] ) && 'fr' === $_SESSION['slang'] ? '(site externe)' : '(external site)';
        $external_indicator = '<i class="fa fa-external-link spark-support-external-arrow" aria-hidden="true"></i>'
            . '<span class="visually-hidden"> ' . esc_html( $external_label ) . '</span>';
    }
    return '<span class="spark-support-icon" aria-hidden="true"><i class="fa ' . esc_attr( $icon ) . '"></i></span>'
        . '<span class="spark-support-label">' . esc_html( $label ) . '</span>' . $external_indicator;
}
add_filter( 'nav_menu_item_title', 'spark_makeover_support_link_title', 10, 4 );
