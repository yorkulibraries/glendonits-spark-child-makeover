<?php
/** Native links retain CMS destinations and name the page that comes next. */
$navigation_args = isset( $args ) && is_array( $args ) ? $args : array();
$navigation_page_id = isset( $navigation_args['page_id'] ) ? (int) $navigation_args['page_id'] : get_the_ID();
$navigation_language = isset( $navigation_args['language'] ) ? $navigation_args['language'] : ( isset( $_SESSION['slang'] ) && 'fr' === $_SESSION['slang'] ? 'fr' : 'en' );
$navigation_french = 'fr' === $navigation_language;
$navigation_items = isset( $navigation_args['menu_items'] ) ? $navigation_args['menu_items'] : array();
$previous_page = get_field( 'previous_page', $navigation_page_id );
$next_page = get_field( 'next_page', $navigation_page_id );
$controls = array();

foreach ( array( 'prev' => $previous_page, 'next' => $next_page ) as $direction => $url ) {
    if ( ! is_string( $url ) || '' === trim( $url ) ) {
        continue;
    }
    $target_id = url_to_postid( $url );
    $controls[] = array(
        'direction' => $direction,
        'url' => add_query_arg( 'l', $navigation_language, $url ),
        'label' => 'prev' === $direction ? ( $navigation_french ? 'Précédent' : 'Previous' ) : ( $navigation_french ? 'Suivant' : 'Next' ),
        'title' => $target_id ? spark_makeover_page_label( $target_id, $navigation_language, $navigation_items ) : ( $navigation_french ? 'Page du module' : 'Module page' ),
    );
}
if ( ! $next_page ) {
    $controls[] = array(
        'direction' => 'home',
        'url' => add_query_arg( 'l', $navigation_language, home_url( '/' ) ),
        'label' => $navigation_french ? 'Explorer' : 'Explore',
        'title' => $navigation_french ? 'Tous les modules SPARK' : 'All SPARK modules',
    );
}
?>
<nav class="content-bottom-navigation-page-controls" aria-label="<?php echo esc_attr( $navigation_french ? 'Navigation entre les pages du module' : 'Module page navigation' ); ?>">
    <?php foreach ( $controls as $control ) : ?>
        <a href="<?php echo esc_url( $control['url'] ); ?>" class="<?php echo esc_attr( 'content-bottom-' . ( 'prev' === $control['direction'] ? 'back' : $control['direction'] ) . '-button' ); ?>"<?php if ( 'home' !== $control['direction'] ) : ?> rel="<?php echo esc_attr( $control['direction'] ); ?>"<?php endif; ?> aria-label="<?php echo esc_attr( $control['label'] . ': ' . $control['title'] ); ?>">
            <svg class="spark-page-control-icon" aria-hidden="true" focusable="false" viewBox="0 0 24 24">
                <?php if ( 'prev' === $control['direction'] ) : ?>
                    <path d="M19 12H5m7 7-7-7 7-7" />
                <?php elseif ( 'next' === $control['direction'] ) : ?>
                    <path d="M5 12h14m-7-7 7 7-7 7" />
                <?php else : ?>
                    <path d="m3 10 9-7 9 7v10H15v-7H9v7H3z" />
                <?php endif; ?>
            </svg>
            <span class="spark-nav-copy"><span class="spark-nav-direction"><?php echo esc_html( $control['label'] ); ?></span><span class="spark-nav-title"><?php echo esc_html( $control['title'] ); ?></span></span>
        </a>
    <?php endforeach; ?>
</nav>
