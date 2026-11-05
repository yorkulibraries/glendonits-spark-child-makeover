<?php
/**
 * Responsive Bootstrap 5 site map page.
 *
 * @package WordPress
 * @subpackage Glendon ITS SPARK Child
 */

if ( isset( $_GET['l'] ) ) {
	$requested_language = sanitize_key( wp_unslash( $_GET['l'] ) );
	if ( in_array( $requested_language, array( 'en', 'fr' ), true ) ) {
		$_SESSION['slang'] = $requested_language;
	}
}

$spark_language = isset( $_SESSION['slang'] ) && is_string( $_SESSION['slang'] )
	? sanitize_key( $_SESSION['slang'] )
	: 'en';
$spark_language = in_array( $spark_language, array( 'en', 'fr' ), true ) ? $spark_language : 'en';
$is_french      = 'fr' === $spark_language;
$page_id        = get_queried_object_id();
$parent_uri     = get_template_directory_uri();
$switch_language = $is_french ? 'en' : 'fr';
$switch_label    = $is_french ? 'English' : 'Français';
$switch_url      = add_query_arg( 'l', $switch_language, get_permalink( $page_id ) );
$spark_home_url  = add_query_arg( 'l', $spark_language, home_url( '/' ) );
$content         = $is_french ? get_field( 'content_fr', $page_id ) : '';

if ( ! $content ) {
	$content = get_post_field( 'post_content', $page_id );
}

$content = apply_filters( 'the_content', $content );
$content = preg_replace( '/<h1(\b[^>]*)>/i', '<h2$1>', $content );
$content = preg_replace( '/<\/h1>/i', '</h2>', $content );

get_header();
?>

<main id="main-content" class="container py-4 spark-child-page spark-site-map" lang="<?php echo esc_attr( $spark_language ); ?>">
	<header class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-4">
		<a class="spark-site-map-logo" href="<?php echo esc_url( $spark_home_url ); ?>">
			<img
				src="<?php echo esc_url( $parent_uri . '/img/spark_logo' . ( $is_french ? '_fr' : '' ) . '.png' ); ?>"
				alt="<?php echo esc_attr( $is_french ? 'SPARK — Trousse académique pour étudiants' : 'SPARK — Student Papers and Academic Research Kit' ); ?>"
				width="735"
				height="111"
				class="img-fluid"
			>
		</a>
		<a class="btn btn-outline-secondary align-self-start align-self-sm-center" href="<?php echo esc_url( $switch_url ); ?>" lang="<?php echo esc_attr( $switch_language ); ?>">
			<?php echo esc_html( $switch_label ); ?>
		</a>
	</header>

	<p class="spark-module-home-link mb-4">
		<a href="<?php echo esc_url( $spark_home_url ); ?>">
			<span aria-hidden="true">←</span>
			<?php echo esc_html( $is_french ? 'Retour à l’accueil de SPARK' : 'Return to SPARK home' ); ?>
		</a>
	</p>

	<h1 class="h2 mb-4"><?php echo esc_html( $is_french ? 'Plan du site SPARK' : 'SPARK Site Map' ); ?></h1>

	<nav class="spark-sitemap-content" aria-label="<?php echo esc_attr( $is_french ? 'Sections et pages de SPARK' : 'SPARK sections and pages' ); ?>">
		<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</nav>
</main>

<?php get_footer(); ?>
