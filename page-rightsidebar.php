<?php
/**
 * Template Name: SPARK Lesson — Right Resources (Test)
 * Template Post Type: page
 *
 * Experimental module layout with a Bootstrap 5 resources column.
 *
 * Pages without spark_topic retain the parent theme's existing template.
 *
 * @package WordPress
 * @subpackage Glendon ITS SPARK Child
 */

$page_id           = get_queried_object_id();
$current_module_id = (int) get_post_meta( $page_id, 'spark_topic', true );

if ( $current_module_id < 1 ) {
	require get_template_directory() . '/index.php';
	return;
}

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
$module_groups  = array(
	10 => array(
		'menu'  => 'getting-started',
		'title' => $is_french ? 'Commencer le travail' : 'Getting Started',
	),
	20 => array(
		'menu'  => 'exploring',
		'title' => $is_french ? 'Explorer' : 'Exploring',
	),
	30 => array(
		'menu'  => 'pulling-it-together',
		'title' => $is_french ? 'Rassembler les idées' : 'Putting It Together',
	),
	40 => array(
		'menu'  => 'faculty',
		'title' => $is_french ? 'Corps professoral' : 'Faculty',
	),
);
$module_group   = reset( $module_groups );
foreach ( $module_groups as $threshold => $group ) {
	if ( $current_module_id >= $threshold ) {
		$module_group = $group;
	}
}
$menu_location = $module_group['menu'] . ( $is_french ? '-fr' : '' );
$module_context = spark_makeover_module_context( $page_id, $menu_location );
$stage_icons = array( 'getting-started' => 'fa-compass', 'exploring' => 'fa-search', 'pulling-it-together' => 'fa-pencil', 'faculty' => 'fa-graduation-cap' );
$stage_icon = $stage_icons[ $module_group['menu'] ];
$parent_uri    = get_template_directory_uri();
$switch_language = $is_french ? 'en' : 'fr';
$switch_label    = $is_french ? 'English' : 'Français';
$switch_url      = add_query_arg( 'l', $switch_language, get_permalink( $page_id ) );
$spark_home_url  = add_query_arg( 'l', $spark_language, home_url( '/' ) );
$resource_lines  = preg_split( '/\r\n|\r|\n/', (string) get_field( 'resource_links_' . $spark_language, $page_id ) );
$resources       = array();

foreach ( $resource_lines as $resource_line ) {
	$resource_parts = explode( '|', trim( $resource_line ), 2 );
	if ( 2 !== count( $resource_parts ) || '' === trim( $resource_parts[0] ) || '' === trim( $resource_parts[1] ) ) {
		continue;
	}

	$resource_url = trim( $resource_parts[1] );
	if ( ! preg_match( '#^https?://#i', $resource_url ) ) {
		$resource_url = $parent_uri . '/' . ltrim( $resource_url, '/' );
	}

	$resources[] = array(
		'label' => trim( $resource_parts[0] ),
		'url'   => $resource_url,
	);
}

$video_file   = get_field( 'video_file_' . $spark_language, $page_id );
$caption_file = get_field( 'video_file_captions_' . $spark_language, $page_id );
$mp4_file     = get_field( 'video_file_mac_' . $spark_language, $page_id );
$transcript   = spark_get_transcript( $page_id, $spark_language );
$module_content = '';

if ( $is_french ) {
	$french_content = get_field( 'content_fr', $page_id );
	if ( $french_content ) {
		$module_content = apply_filters( 'the_content', $french_content );
	}
} else {
	while ( have_posts() ) {
		the_post();
		$module_content .= apply_filters( 'the_content', get_the_content() );
	}
}

$module_heading = '';
if ( preg_match( '/<h1\b[^>]*>.*?<\/h1>/is', $module_content, $heading_match ) ) {
	$module_heading = $heading_match[0];
	$module_content = preg_replace( '/<h1\b[^>]*>.*?<\/h1>/is', '', $module_content, 1 );
} else {
	$module_heading = '<h1>' . esc_html( spark_makeover_page_label( $page_id, $spark_language, $module_context['items'] ) ) . '</h1>';
}

get_header();
?>

<main id="main-content" class="container py-4 spark-module-page spark-module-rightsidebar-page" lang="<?php echo esc_attr( $spark_language ); ?>">
			<header class="spark-module-branding d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-4">
				<a class="spark-module-logo" href="<?php echo esc_url( $spark_home_url ); ?>">
					<img
						src="<?php echo esc_url( $parent_uri . '/img/spark_logo' . ( $is_french ? '_fr' : '' ) . '.png' ); ?>"
						alt="<?php echo esc_attr( $is_french ? 'SPARK — Trousse académique pour étudiants' : 'SPARK — Student Papers and Academic Research Kit' ); ?>"
						width="735"
						height="111"
						class="img-fluid"
					>
				</a>
				<a class="btn btn-outline-secondary align-self-start align-self-sm-center spark-module-language-switch" href="<?php echo esc_url( $switch_url ); ?>" lang="<?php echo esc_attr( $switch_language ); ?>">
					<?php echo esc_html( $switch_label ); ?>
				</a>
			</header>

	<div class="row g-4 align-items-start">
		<aside class="col-12 spark-module-sidebar">
			<nav aria-label="<?php echo esc_attr( $is_french ? 'Sommaire des modules' : 'Module contents' ); ?>">
				<button
					class="btn btn-outline-secondary w-100 spark-module-menu-mobile-toggle d-lg-none"
					type="button"
					data-bs-toggle="collapse"
					data-bs-target="#spark-module-menu-collapse"
					aria-controls="spark-module-menu-collapse"
					aria-expanded="false"
				>
					<?php echo esc_html( $is_french ? 'Sommaire des modules' : 'Module contents' ); ?>
				</button>
				<div class="spark-module-navigation collapse" id="spark-module-menu-collapse">
					<div class="spark-module-stage-heading">
						<i class="fa <?php echo esc_attr( $stage_icon ); ?>" aria-hidden="true"></i>
						<h2><?php echo esc_html( $module_group['title'] ); ?></h2>
					</div>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => $menu_location,
							'container'      => false,
							'menu_class'     => 'spark-module-menu',
							'depth'          => 0,
							'fallback_cb'    => false,
							'spark_makeover_module_menu' => true,
							'spark_current_menu_item' => $module_context['current_item_id'],
						)
					);
					?>
				</div>
			</nav>
		</aside>

		<section class="col-12 spark-module-main">
			<nav class="spark-module-breadcrumbs" aria-label="<?php echo esc_attr( $is_french ? 'Fil d’Ariane' : 'Breadcrumb' ); ?>">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo esc_url( $spark_home_url ); ?>"><?php echo esc_html( $is_french ? 'Accueil SPARK' : 'SPARK Home' ); ?></a></li>
					<li class="breadcrumb-item"><?php echo esc_html( $module_group['title'] ); ?></li>
					<?php if ( $module_context['module'] ) : ?>
						<li class="breadcrumb-item"><a href="<?php echo esc_url( add_query_arg( 'l', $spark_language, $module_context['module']->url ) ); ?>"><?php echo esc_html( spark_makeover_menu_label( $module_context['module']->title ) ); ?></a></li>
					<?php endif; ?>
				</ol>
			</nav>

			<article class="spark-module-content">
				<?php if ( $module_context['position'] ) : ?>
					<p class="spark-module-position"><?php echo esc_html( sprintf( $is_french ? 'Page %1$d sur %2$d' : 'Page %1$d of %2$d', $module_context['position'], $module_context['total'] ) ); ?></p>
				<?php endif; ?>
				<?php echo $module_heading; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>


				<div class="row g-4 spark-lesson-resources-layout">
					<div class="col-12 <?php echo $resources ? 'col-xl-8' : ''; ?>">
						<div class="spark-module-lesson-body">
							<?php echo $module_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>

						<?php if ( $video_file ) : ?>
						<div class="spark-module-video my-4">
							<?php
							echo spark_video_widget( $page_id, $parent_uri, $video_file, $caption_file, $mp4_file, ! is_null( $transcript ), 'lr', $spark_language ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?>
						</div>
						<?php endif; ?>
						<?php if ( $transcript ) : ?>
						<div class="spark-module-transcript">
							<?php echo $transcript; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
						<?php endif; ?>
					</div>
					<?php if ( $resources ) : ?>
						<div class="col-12 col-xl-4 spark-lesson-resources-column">
							<aside class="card spark-module-resources" aria-labelledby="spark-module-resources-title">
								<h2 class="card-header h5 mb-0" id="spark-module-resources-title">
									<?php echo esc_html( $is_french ? 'Ressources pour cette leçon' : 'Resources for this lesson' ); ?>
								</h2>
								<ul class="list-group list-group-flush">
									<?php foreach ( $resources as $resource ) : ?>
										<li class="list-group-item">
											<a href="<?php echo esc_url( $resource['url'] ); ?>" target="_blank" rel="noopener noreferrer">
												<?php echo esc_html( $resource['label'] ); ?>
												<span class="visually-hidden"><?php echo esc_html( $is_french ? '(ouvre dans un nouvel onglet)' : '(opens in a new tab)' ); ?></span>
											</a>
										</li>
									<?php endforeach; ?>
								</ul>
							</aside>
						</div>
					<?php endif; ?>
				</div>

				<div class="spark-module-page-controls">
					<?php get_template_part( 'includes/module-page-navigation', null, array( 'page_id' => $page_id, 'language' => $spark_language, 'menu_items' => $module_context['items'] ) ); ?>
				</div>
			</article>
		</section>
	</div>
</main>

<div class="dark-blanket" aria-hidden="true"></div>
<?php echo spark_get_objectives( '300', $spark_language ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

<?php get_footer(); ?>
