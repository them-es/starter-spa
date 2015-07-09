<?php
	include_once($_SERVER['DOCUMENT_ROOT'] . '/wp-config.php'); // Load WP Config
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
		
		<paper-input-container>
			<label><iron-icon icon="search"></iron-icon><?php _e('Search', 'my-theme'); ?></label>
			<input type="text" is="iron-input" class="typeahead" />
		</paper-input-container>
	</template>
</dom-module>
<script>
	Polymer({ is: "post-search" });
	
	window.addEventListener('WebComponentsReady', function() {
		
		// Init Typeahead
		var wp_searchposts = new Bloodhound({
			datumTokenizer: Bloodhound.tokenizers.obj.whitespace('title'),
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			remote: {
				url: "<?php echo get_site_url() . '/wp-json/posts?filter[s]=%QUERY&type[]=page&type[]=post'; ?>",
				wildcard: '%QUERY'
			}
		});
		jQuery('.typeahead').typeahead(null, {
			name: 'search',
			display: 'title',
			source: wp_searchposts
		}).on('typeahead:select', function($e, d) {
			// Navigate to route using page.js
			if ( d.type == 'page' ) {
				if ( d.ID == "<?php echo get_option('page_on_front'); ?>" ) {
					d.slug = ''; // Frontpage: "/"
				}
				page('/' + d.slug);
			} else {
				page("/<?php $blog_page = get_option('page_for_posts'); $post = get_post($blog_page); echo $post->post_name; ?>"); // Blog
			}
			$e.preventDefault();
		});
		
	});
</script>