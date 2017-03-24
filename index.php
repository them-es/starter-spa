<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<?php
		wp_head();

		$page_on_front = get_option( 'page_on_front' );
		$page_for_posts = get_option( 'page_for_posts' );
		$search_enabled = get_theme_mod( 'search_enabled', '1' ); // get custom meta-value

		$menu_items_pages = themes_starter_get_menu_items( $menu_name ); // see functions.php
		$menu_items_parents = themes_starter_get_parents_children( $menu_name ); // see functions.php

		$dir = trailingslashit( esc_url( get_template_directory_uri() ) );
	?>

	<link rel="icon" href="<?php echo $dir; ?>img/favicon.ico">

	<link rel="manifest" href="<?php echo $dir; ?>manifest.json">

	<meta name="theme-color" content="#3f51b5">

	<!-- Add to homescreen for Chrome on Android. Fallback for manifest.json -->
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="application-name" content="My App">

	<!-- Add to homescreen for Safari on iOS -->
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
	<meta name="apple-mobile-web-app-title" content="My App">

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
	
	<!-- Initialize Web components -->
	<script>
		// Polymer options
		window.Polymer = {
			dom: 'shadow',
			lazyRegister: true,
		};

		// Web Components polyfill
		(function() {
			'use strict';
			var onload = function() {
				// For native Imports, manually fire WebComponentsReady so user code
				// can use the same code path for native and polyfill'd imports.
				if ( ! window.HTMLImports ) {
					document.dispatchEvent(
						new CustomEvent('WebComponentsReady', { bubbles: true })
					);
				}
			};
			var webComponentsSupported = (
				'registerElement' in document
				&& 'import' in document.createElement('link')
				&& 'content' in document.createElement('template')
			);
			if ( ! webComponentsSupported ) {
				var script = document.createElement('script');
				script.async = true;
				script.src = '<?php echo $dir; ?>bower_components/webcomponentsjs/webcomponents-lite.min.js';
				script.onload = onload;
				document.head.appendChild(script);
			} else {
				onload();
			}
		})();
	</script>
	<link rel="import" href="<?php echo $dir; ?>elements.html">
	
	<?php if ( wp_count_posts()->publish >= 1 ) : ?>
		<link rel="import" href="<?php echo $dir; ?>elements/post-list.php">
	<?php endif; ?>
	
	<?php if ( isset( $search_enabled ) && '1' === $search_enabled ) : ?>
		<link rel="import" href="<?php echo $dir; ?>elements/search.php">
	<?php endif; ?>
</head>

<body <?php body_class( 'fullbleed layout vertical' ); ?> unresolved>
	
	<template id="app" is="dom-bind">
	
	<paper-drawer-panel id="wrapper">
		
		<div id="nav" drawer>
			<a class="navbar-brand flex" href="<?php echo home_url(); ?>" rel="home">
				<?php
					$header_logo = get_theme_mod( 'header_logo' ); // get custom meta-value

					if ( isset( $header_logo ) && ! empty( $header_logo ) ) :
				?>
					<img src="<?php echo esc_url( $header_logo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" />
				<?php
					else :
						echo esc_attr( get_bloginfo( 'name', 'display' ) );
					endif;
				?>
			</a>
			<div class="layout vertical">
				<post-search></post-search><!-- Search component -->
				
				<app-location route="{{route}}"></app-location>
				<app-route route="{{route}}" pattern="./:page" data="{{pageData}}"></app-route>

				<paper-menu class="list" selected="{{pageData.page}}" attr-for-selected="data-page" role="navigation">
				<?php
					// Get menu items based on type: "$menu_items_parents" defined on top
					foreach ( $menu_items_parents as $key => $value ) {
						$title = $value['title'];
						$slug = $value['slug'];
						$url = $value['url'];
						$child = $value['child'];

						$paper_item = '';

						if ( empty( $child ) ) :
							$paper_item .= '<a data-page="' . $slug . '" href="' . $url . '"><span>' . $title . '</span></a><!-- menu_item -->';
						else :
							$paper_item .= '<paper-submenu>';
								$paper_item .= '<paper-item class="menu-trigger"><span>' . $title . '</span><iron-icon icon="expand-more"></iron-icon></paper-item>';
								$paper_item .= '<paper-menu class="menu-content" selected="{{pageData.page}}" attr-for-selected="data-page">';
									foreach ( $child as $key => $value ) {
										$paper_item .= '<a data-page="' . $value['slug'] . '" href="' . $value['url'] . '"><span>' . $value['title'] . '</span></a>';
									}
								$paper_item .= '</paper-menu>';
							$paper_item .= '</paper-submenu>';
						endif;

						echo $paper_item;
					}
				?>
				</paper-menu>
				
				<p>
					<small>&copy; <?php echo date( 'Y' ); ?> <?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?></small>
				</p>
			</div>
		</div>
		
		<paper-scroll-header-panel id="main" main condenses keep-condensed-header>
			<paper-toolbar id="mainToolbar" class="tall">
				<paper-icon-button icon="menu" tabindex="1" paper-drawer-toggle></paper-icon-button>
				
				<div class="flex"></div>
				
				<div class="middle middle-container center horizontal layout">
					
				</div>
				<div class="bottom bottom-container center horizontal layout">
					<span class="title">{{pagetitle}}</span>
				</div><!-- Sub title -->
			</paper-toolbar>
			
			<div class="container">
				
				<?php
					/**
					 * Check if menu has been setup. "$menu_name" defined in functions.php
					 */
					if ( ! has_nav_menu( $menu_name ) ) :
						echo '<paper-material elevation="4"><iron-icon icon="info-outline" class="icon-danger"></iron-icon> Setup Menu <strong>' . $menu_name . '</strong></paper-material>';
						$exit = true;
					endif;

					/**
					 * Check if WordPress >= 4.7
					 */
					global $wp_version;
					if ( $wp_version <= '4.7' ) :
						echo '<paper-material elevation="4"><iron-icon icon="info-outline" class="icon-danger"></iron-icon> Please install WordPress >= 4.7 <a href="https://wordpress.org">wordpress.org</a></paper-material>';
						$exit = true;
					endif;

					if ( isset( $exit ) ) :
						die();
					endif;
				?>

				<neon-animated-pages id="pages" selected="{{pageData.page}}" attr-for-selected="data-page" fallback-selection="index" entry-animation="slide-from-left-animation" exit-animation="slide-right-animation" role="main">
					<!-- Content Pages -->
					<?php
						$section = '';
						
						// Get all menu items: "$menu_items_pages" defined on top
						foreach ( $menu_items_pages as $key => $value ) {
							$section .= '<neon-animatable data-page="' . $value['slug'] . '">' . PHP_EOL;
								$section .= '<article>' . PHP_EOL;
									$id = $value['pageid'];
									
									if ( $id === $page_for_posts ) : // Blog Posts page: /wp-admin/options-reading.php
										$section .= '<post-list show="all"></post-list>'; // Custom Web Component
									else :
										$section .= apply_filters( 'the_content', get_post_field( 'post_content', $id ) ) . PHP_EOL;
									endif;
									
								$section .= '</article>' . PHP_EOL;
							$section .= '</neon-animatable>' . PHP_EOL;
						}
						
						// 404 - Not found
						$section .= '<neon-animatable data-page="404">' . PHP_EOL;
							$section .= '<article>' . PHP_EOL;
								$section .= '<p>' . __( 'Page not found', 'my-theme' ) . '</p>' . PHP_EOL;
							$section .= '</article>' . PHP_EOL;
						$section .= '</neon-animatable>' . PHP_EOL;
						
						echo $section;
					?>
				</neon-animated-pages>
				
			</div><!-- /.container -->
		</paper-scroll-header-panel>
		
	</paper-drawer-panel><!-- /#main -->
	
	</template>

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