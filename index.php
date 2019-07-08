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

	<link rel="icon" href="<?php echo $dir; ?>assets/img/favicon.ico">

	<link rel="manifest" href="<?php echo $dir; ?>manifest.json">

	<meta name="theme-color" content="#3f51b5">

	<!-- Add to homescreen for Chrome on Android. Fallback for manifest.json -->
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="application-name" content="Starter App">

	<!-- Add to homescreen for Safari on iOS -->
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
	<meta name="apple-mobile-web-app-title" content="Starter App">
	
	<!-- Load webcomponents-loader.js to check and load any polyfills your browser needs -->
	<script src="<?php echo $dir; ?>bower_components/webcomponentsjs/webcomponents-loader.js"></script>
	<link rel="import" href="<?php echo $dir; ?>elements/starter-app.php">
</head>

<body <?php body_class(); ?> unresolved>

	<?php wp_body_open(); ?>

	<starter-app></starter-app>
	
	<?php wp_footer(); ?>

	<?php
		if ( is_admin_bar_showing() ) :
	?>
		<script>
			document.addEventListener('DOMContentLoaded', function () {
				// Open wp-toolbar links
				[].forEach.call( document.querySelectorAll( '#wp-toolbar a' ), function ( a ) {
					a.addEventListener( 'click', function () {
						location.href = this.getAttribute('href');
					}, false );
				});
			});
		</script>
	<?php
		endif;
	?>

</body>
</html>