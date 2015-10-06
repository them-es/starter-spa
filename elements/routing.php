<?php
	include_once($_SERVER['DOCUMENT_ROOT'] . '/wp-config.php'); // Load WP Config
?>

<script src="../bower_components/page.js/page.js"></script>

<script>
	window.addEventListener('WebComponentsReady', function() {
		// Using Page.js for routing
		var app = document.querySelector("#app");
		
		page.base('<?php echo themes_starter_site_base(); ?>');
		
		<?php
			$routes = '';
			
			// Get all "published" Pages
			$pages = get_pages( array('post_status' => 'publish') );
			foreach ( (array) $pages as $page ) {
				$id = $page->ID; // = Page ID
				$post_data = get_post($id, ARRAY_A);
				$slug = $post_data['post_name']; // = Page Slug
				$href = '/' . $slug;
				$title = $page->post_title; // = Page Title

				if ( $id == get_option('page_on_front') ) {
					$slug = 'index';
					$href = '/';
				}

				$routes .= 'page("' . $href . '", function() { app.route = "' . $slug . '"; app.routetitle = "' . apply_filters("the_title", $title) . '"; });' . PHP_EOL;
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