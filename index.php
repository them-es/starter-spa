<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<?php
		wp_head();

		$dir = trailingslashit( esc_url( get_template_directory_uri() ) );
	?>

	<link rel="icon" href="<?php echo $dir; ?>img/favicon.ico">

	<link rel="manifest" href="<?php echo $dir; ?>manifest.json">

	<meta name="theme-color" content="#3f51b5">

	<!-- Add to homescreen for Chrome on Android. Fallback for manifest.json -->
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="application-name" content="Starter App">

	<!-- Add to homescreen for Safari on iOS -->
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
	<meta name="apple-mobile-web-app-title" content="Starter App">

	<!-- Homescreen icons -->
	<link rel="apple-touch-icon" href="<?php echo $dir; ?>img/icon-48x48.png">
	<link rel="apple-touch-icon" sizes="72x72" href="<?php echo $dir; ?>img/icon-72x72.png">
	<link rel="apple-touch-icon" sizes="96x96" href="<?php echo $dir; ?>img/icon-96x96.png">
	<link rel="apple-touch-icon" sizes="144x144" href="<?php echo $dir; ?>img/icon-144x144.png">
	<link rel="apple-touch-icon" sizes="192x192" href="<?php echo $dir; ?>img/icon-192x192.png">

	<!-- Tile icon for Windows 8 (144x144 + tile color) -->
	<meta name="msapplication-TileImage" content="<?php echo $dir; ?>img/icon-144x144.png">
	<meta name="msapplication-TileColor" content="#3f51b5">
	<meta name="msapplication-tap-highlight" content="no">
	
	<!-- Load webcomponents-loader.js to check and load any polyfills your browser needs -->
	<script src="<?php echo $dir; ?>bower_components/webcomponentsjs/webcomponents-loader.js"></script>
	<link rel="import" href="<?php echo $dir; ?>elements/starter-app.php">
</head>

<body <?php body_class(); ?> unresolved>

	<starter-app></starter-app>

	<?php
		// Routing: Make sure that Admin bar links are working "onclick"
		if ( is_admin_bar_showing() ) :
	?>
		<script>
			// WP-admin links
			(function ($) {
				$(document).ready(function () {
					$('#wp-toolbar a').on('click', function () {
						location.href = $(this).attr('href');
					});
				});
			}(jQuery));
		</script>
	<?php
		endif;
	?>
	
	<?php wp_footer(); ?>

</body>
</html>