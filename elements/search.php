<?php
	include_once( '../../../../wp-config.php' ); // Load WP Config (relative to current file)
?>

<link rel="import" href="../bower_components/polymer/polymer.html">
<link rel="import" href="../bower_components/paper-input/paper-input.html">
<script src="../bower_components/typeahead.js/dist/typeahead.bundle.min.js"></script>

<dom-module id="post-search" attributes="show">
	<template>
		<style>
			paper-input-container {
				display: block;
				margin: 5px 10px;
			}
			paper-input-container label {
				font-weight: 300 !important;
			}
			.twitter-typeahead {
				width: 100%;
			}
			.tt-menu {
				width: 100%;
				margin: 2px 0;
				background-color: #FFF;
				box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.12), 0 1px 5px 0 rgba(0, 0, 0, 0.12), 0 3px 1px -2px rgba(0, 0, 0, 0.12);
			}
			.tt-suggestion {
				padding: 7px 10px;
				font-weight: 300;
			}
			.tt-suggestion:hover {
				background-color: #f8f8f8;
				cursor: pointer;
			}
		</style>
		
		<paper-input-container title="<?php _e( 'Search', 'my-theme' ); ?>">
			<label><iron-icon icon="search"></iron-icon></label>
			<input type="text" is="iron-input" class="typeahead" />
		</paper-input-container>
	</template>
</dom-module>
<script>
	Polymer({ is: 'post-search' });
	
	window.addEventListener('WebComponentsReady', function() {
		
		// Init Typeahead
		var wp_searchposts = new Bloodhound({
			datumTokenizer: function (title) {
				return Bloodhound.tokenizers.obj.whitespace(title.rendered);
			},
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			remote: {
				url: "<?php echo trailingslashit( esc_url_raw( rest_url( '/wp/v2' ) ) ) . 'pages/?filter[s]=%QUERY'; ?>", // Nice to have => allow multiple post types a once: https://github.com/WP-API/WP-API/issues/1506
				wildcard: '%QUERY'
			}
		});
		jQuery('.typeahead').typeahead(null, {
			name: 'search',
			display: function (i) {
				return i.title.rendered;
			},
			source: wp_searchposts
		}).on('typeahead:select', function($e, d) {
			if ( d.type == 'page' ) {
				// Pages
				if ( d.ID == "<?php echo get_option( 'page_on_front' ); ?>" ) {
					d.slug = ''; // Frontpage: "/"
				}
			} else {
				// Blog posts
				<?php
					$blog_page = get_option( 'page_for_posts' );
					$post = get_post( $blog_page );
				?>
				d.slug = '<?php echo $post->post_name; ?>';
			}
			// Update Browser URL, Click Menu link
			window.history.pushState({}, null, '<?php echo trailingslashit( home_url() ); ?>' + d.slug); // https://github.com/PolymerElements/app-route#integrating-with-other-routing-code
			window.dispatchEvent(new CustomEvent('location-changed'));
			document.querySelector('paper-menu [data-page="' + d.slug + '"]').click();

			$e.preventDefault();
		});
		
	});
</script>