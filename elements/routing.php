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
			
			// "$menu_items_allpages" defined in functions.php
			foreach ($menu_items_allpages as $key => $value) {
				$slug = $value["slug"];
				$href = '/' . $slug;

				if ( $slug == 'index' ) {
					$href = '/';
				}
				
				$routes .= 'page("' . $href . '", function() { app.route = "' . $slug . '"; app.routetitle = "' . $value["title"] . '"; });' . PHP_EOL;
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