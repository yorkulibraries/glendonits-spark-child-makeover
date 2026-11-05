<?php
/**
 * Use a child-owned York-style header for migrated SPARK pages.
 * Pages outside the Bootstrap 5 migration keep the parent header.
 *
 * @package WordPress
 * @subpackage Glendon ITS SPARK Child
 */

if ( ! glendonits_spark_is_bootstrap5_page() ) {
	require get_template_directory() . '/header.php';
	return;
}

$is_module_page = is_page() && (int) get_post_meta( get_queried_object_id(), 'spark_topic', true ) > 0;
$spark_language = isset( $_SESSION['slang'] ) && 'fr' === $_SESSION['slang'] ? 'fr' : 'en';
$is_french      = 'fr' === $spark_language;
$is_homepage    = is_page_template( 'home-page.php' );
$document_title = is_page( 'site-map' )
	? ( $is_french ? 'Plan du site SPARK' : 'SPARK Site Map' )
	: 'York University';

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#ffffff">
	<title><?php echo esc_html( get_bloginfo( 'name' ) . ' | ' . ( $is_module_page ? get_the_title() : $document_title ) ); ?></title>
	<?php wp_head(); ?>
</head>
<body <?php body_class( $is_homepage ? 'spark-child-home' : ( $is_module_page ? 'spark-child-page spark-child-module' : 'spark-child-page' ) ); ?>>
<?php wp_body_open(); ?>
<a class="spark-skip-link" href="#main-content">
	<?php echo esc_html( $is_french ? 'Passer au contenu principal' : 'Skip to main content' ); ?>
</a>
<header class="spark-york-header spark-module-york-header">
	<div class="container d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 py-3">
		<a class="spark-york-logo" href="https://www.yorku.ca/" aria-label="<?php echo esc_attr( $is_french ? 'Université York' : 'York University' ); ?>">
			<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/yorku-logo.jpg' ); ?>" alt="<?php echo esc_attr( $is_french ? 'Université York' : 'York University' ); ?>" width="140">
		</a>

		<div class="spark-york-tools d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center gap-2">
			<div class="dropdown">
				<button class="btn btn-outline-dark dropdown-toggle w-100" type="button" id="spark-module-popular-links-toggle" data-bs-toggle="dropdown" aria-expanded="false">
					<?php echo esc_html( $is_french ? 'Liens populaires' : 'Popular Links' ); ?>
				</button>
				<ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="spark-module-popular-links-toggle">
					<li><a class="dropdown-item" href="https://futurestudents.yorku.ca/program-search/"><?php echo esc_html( $is_french ? 'Programmes d’études' : 'Programs of Study' ); ?></a></li>
					<li><a class="dropdown-item" href="https://eclass.yorku.ca/">eClass</a></li>
					<li><a class="dropdown-item" href="https://www.yorku.ca/about/email/"><?php echo esc_html( $is_french ? 'Courriel' : 'Email' ); ?></a></li>
					<li><a class="dropdown-item" href="https://www.yorku.ca/about/faculty-staff/"><?php echo esc_html( $is_french ? 'Corps professoral et personnel' : 'Faculty & Staff' ); ?></a></li>
					<li><a class="dropdown-item" href="https://sfs.yorku.ca/"><?php echo esc_html( $is_french ? 'Services financiers' : 'Financial Services' ); ?></a></li>
					<li><a class="dropdown-item" href="https://www.library.yorku.ca/web/"><?php echo esc_html( $is_french ? 'Bibliothèques' : 'Libraries' ); ?></a></li>
					<li><a class="dropdown-item" href="https://atlas.yorku.ca/"><?php echo esc_html( $is_french ? 'Répertoire' : 'Directory' ); ?></a></li>
				</ul>
			</div>

			<form class="spark-york-search d-flex" method="get" action="https://www.yorku.ca/search/">
				<label class="visually-hidden" for="spark-module-york-search-input"><?php echo esc_html( $is_french ? 'Rechercher sur York' : 'Search York' ); ?></label>
				<input class="form-control" id="spark-module-york-search-input" type="search" name="q" placeholder="<?php echo esc_attr( $is_french ? 'Rechercher sur yorku.ca' : 'Search yorku.ca' ); ?>">
				<button class="btn btn-primary" type="submit">
					<svg aria-hidden="true" focusable="false" width="20" height="20" viewBox="0 0 16 16" fill="none">
						<circle cx="6.5" cy="6.5" r="5.25" stroke="currentColor" stroke-width="1.5"></circle>
						<path d="m10.5 10.5 4 4" stroke="currentColor" stroke-width="1.5"></path>
					</svg>
					<span class="visually-hidden"><?php echo esc_html( $is_french ? 'Rechercher' : 'Search' ); ?></span>
				</button>
			</form>
		</div>
	</div>
</header>
