<?php
/**
 * Use the York footer on migrated SPARK pages.
 * Pages outside the Bootstrap 5 migration keep the parent footer.
 *
 * @package WordPress
 * @subpackage Glendon ITS SPARK Child
 */

if ( ! glendonits_spark_is_bootstrap5_page() ) {
	require get_template_directory() . '/footer.php';
	return;
}

$spark_language = isset( $_SESSION['slang'] ) && 'fr' === $_SESSION['slang'] ? 'fr' : 'en';
$is_french      = 'fr' === $spark_language;
$york_theme_uri = get_theme_root_uri() . '/york2020';
?>
<footer id="footer" class="mt-auto bg-primary spark-york-footer">
	<div class="container spark-york-footer-content pt-5">
		<div class="row pb-3">
			<div class="col-md-3 mb-3 mb-md-0">
				<a href="https://www.yorku.ca/">
					<img
						src="<?php echo esc_url( $york_theme_uri . '/images/yorkraptorsfooterlogo.png' ); ?>"
						alt="<?php echo esc_attr( $is_french ? 'Université York' : 'York University' ); ?>"
						class="spark-york-footer-logo"
					>
				</a>
			</div>
			<div class="col-md-3">
				<ul class="list-unstyled small">
					<li><span class="fw-bold text-white"><?php echo esc_html( $is_french ? 'Campus Keele, Glendon et Markham' : 'Keele, Glendon and Markham Campus' ); ?></span></li>
					<li><a href="https://www.yorku.ca/about/contact/" class="text-white">Contact</a></li>
					<li><a href="tel:4167362100" class="text-white">(416) 736-2100</a></li>
					<li><a href="https://map.concept3d.com/?id=1200#!s/?ct/29101,29093" class="text-white"><?php echo esc_html( $is_french ? 'Cartes des campus' : 'Campus Maps' ); ?></a></li>
				</ul>
			</div>
			<div class="col-md-6">
				<ul class="list-unstyled small">
					<li><a href="https://www.yorku.ca/safety/" class="text-white"><?php echo esc_html( $is_french ? 'Sécurité communautaire' : 'Community Safety' ); ?></a></li>
					<li><a href="https://www.yorku.ca/about/privacy-legal/" class="text-white"><?php echo esc_html( $is_french ? 'Confidentialité et mentions légales' : 'Privacy & Legal' ); ?></a></li>
					<li><a href="https://accessibility.students.yorku.ca/" class="text-white"><?php echo esc_html( $is_french ? 'Accessibilité' : 'Accessibility' ); ?></a></li>
					<li><a href="https://www.yorku.ca/about/careers/" class="text-white"><?php echo esc_html( $is_french ? 'Carrières' : 'Careers' ); ?></a></li>
				</ul>
			</div>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
