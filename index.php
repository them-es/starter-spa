<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta http-equiv="content-type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<?php
		wp_head();
		
		$page_for_posts = get_option( 'page_for_posts' );
		$search_enabled = get_theme_mod('search_enabled', '1'); // get custom meta-value
		
		$menu_items_pages = themes_starter_get_menu_items($menu_name); // see functions.php
		$menu_items_parents = themes_starter_get_parents_children($menu_name); // see functions.php
		
		$dir = trailingslashit( esc_url( get_template_directory_uri() ) );
	?>
	
	<!-- Initialize Web components -->
	<script src="<?php echo $dir; ?>bower_components/webcomponentsjs/webcomponents-lite.min.js"></script>
	<link rel="import" href="<?php echo $dir; ?>elements.html">
	
	<?php if ( wp_count_posts()->publish >= 1 ) : ?>
		<link rel="import" href="<?php echo $dir; ?>elements/post-list.php">
	<?php endif; ?>
	
	<?php if ( isset($search_enabled) && $search_enabled == "1" ) : ?>
		<link rel="import" href="<?php echo $dir; ?>elements/search.php">
	<?php endif; ?>
</head>

<body <?php body_class('fullbleed layout vertical'); ?> unresolved>
	
	<template id="app" is="dom-bind">
	
	<paper-drawer-panel id="wrapper">

		<div id="nav" drawer>
			<a class="navbar-brand flex" href="<?php echo home_url(); ?>" rel="home">
				<?php
					$header_logo = get_theme_mod('header_logo'); // get custom meta-value

					if ( isset($header_logo) && $header_logo != "" ):
				?>
					<img src="<?php echo esc_url( $header_logo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" />
				<?php 
					else:
						echo esc_attr( get_bloginfo( 'name', 'display' ) );
					endif;
				?>
			</a>
			<div class="layout vertical">
				<post-search></post-search><!-- Search component -->
				
				<paper-menu class="list" attr-for-selected="data-route" selected="{{route}}">
				<?php
					// Get menu items based on type: "$menu_items_parents" defined on top
					foreach ($menu_items_parents as $key => $value) {
						$title = $value["title"];
						$slug = $value["slug"];
						$url = $value["url"];
						
						$paper_items = '';
						
						if ( empty($value["child"]) ) {
							$paper_items .= '<a data-route="' . $slug . '" href="' . $url . '"><span>' . $title . '</span></a><!-- menu_item -->';
						} else {
							$paper_items .= '<paper-submenu>';
							$paper_items .= '<paper-item class="menu-trigger"><span>' . $title . '</span><iron-icon icon="expand-more"></iron-icon></paper-item>';
							$paper_items .= '<paper-menu class="menu-content sublist">';
							foreach ($value["child"] as $key => $value) {
								$paper_items .= '<a data-route="' . $value["slug"] . '" href="' . $value["url"] . '"><span>' . $value["title"] . '</span></a>';
							}
							$paper_items .= '</paper-menu>';
							$paper_items .= '</paper-submenu>';
						}
						
						echo $paper_items;
					}
				?>
				</paper-menu>
				
				<p>
					<small>&copy; <?php echo date('Y'); ?> <?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?></small>
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
					<span class="title">{{routetitle}}</span><span class="bottom-title">{{current_user}}</span>
				</div><!-- Sub title -->
			</paper-toolbar>
			
			<div class="container">
				
				<?php
					/**
					 * Check if menu has been setup. "$menu_name" defined in functions.php
					 */
					if ( !has_nav_menu( $menu_name ) ) :
						echo '<paper-material elevation="4"><iron-icon icon="info-outline" class="icon-danger"></iron-icon> Setup Menu <strong>' . $menu_name . '</strong></paper-material>';
						$exit = true;
					endif;
					
					/**
					 * Check if WP-API (http://wp-api.org) Plugin is installed
					 */
					if ( !class_exists( 'WP_JSON_Posts' ) ) :
						echo '<paper-material elevation="4"><iron-icon icon="info-outline" class="icon-danger"></iron-icon> Install the WP REST API Plugin <a href="https://wordpress.org/plugins/json-rest-api/">wp-api.org</a></paper-material>';
						$exit = true;
					endif;
					
					if (isset($exit)) {
						die();
					}
				?>
				
				<iron-pages attr-for-selected="data-route" selected="{{route}}">
					
					<!-- Content Pages -->
					<?php
						$section = '';

						// Get all menu items: "$menu_items_pages" defined on top
						foreach ($menu_items_pages as $key => $value) {
							$id = $value["pageid"];
							
							$section .= '<section data-route="' . $value["slug"] . '">' . PHP_EOL;
							//$section .= '<h1 class="title">' . apply_filters('the_title', $value["title"]) . '</h1>' . PHP_EOL;
							$section .= '<article>' . PHP_EOL;
							
								if ( $id == get_option('page_for_posts') ) {
									$section .= '<post-list show="all"></post-list>'; // Custom Web Component
								} else {
									$section .= apply_filters('the_content', get_post_field('post_content', $id) ) . PHP_EOL;
								}
							
							$section .= '</article>' . PHP_EOL;
							$section .= '</section>' . PHP_EOL;
						}
						
						echo $section;
					?>
					
					<!-- Not found -->
					<section data-route="404">
						<h1 class="title"><?php _e( '404 / Not found', 'my-theme' ); ?></h1>
					</section>
					
				</iron-pages>

			</div><!-- /.container -->
		</paper-scroll-header-panel>

	</paper-drawer-panel><!-- /#main -->
	
	</template>
	
	<script src="<?php echo $dir; ?>bower_components/page.js/page.js"></script>
	<script>
		window.addEventListener('WebComponentsReady', function() {
			// Using Page.js for routing
			var app = document.querySelector("#app");
			
			page.base('<?php echo themes_starter_site_base(); ?>');
			
			<?php
				$routes = '';
				
				// Get all menu items: "$menu_items_pages" defined on top
				foreach ($menu_items_pages as $key => $value) {
					$id = $value["pageid"];
					$slug = $value["slug"]; // = Page Slug
					$href = '/' . $slug;
					
					if ( $id == get_option('page_on_front') ) {
						$slug = 'index';
						$href = '/';
					}

					$routes .= 'page("' . $href . '", function() { app.route = "' . $slug . '"; app.routetitle = "' . apply_filters("the_title", $value["title"]) . '"; });' . PHP_EOL;
				}
				
				echo $routes;
			?>
			
			// Not found
			page('*', function() { app.route = "404"; });
			
			page({
				hashbang: false
			});
		});
	</script>
	
	<?php wp_footer(); ?>

</body>
</html>