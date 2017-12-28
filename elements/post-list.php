<?php
	include_once( '../../../../wp-config.php' ); // Load WP Config (relative to current file)
?>

<link rel="import" href="../bower_components/polymer/polymer-element.html">
<link rel="import" href="../bower_components/paper-radio-group/paper-radio-group.html">
<link rel="import" href="../bower_components/paper-radio-button/paper-radio-button.html">
<link rel="import" href="../bower_components/iron-image/iron-image.html">
<link rel="import" href="../bower_components/iron-ajax/iron-ajax.html">


<dom-module id="post-list" attributes="show">
	<template>
		<style>
			:host {
				display: block;
				width: 100%;
			}

			h1 {
				margin-top: 0;
			}
			.post-list {
				margin-bottom: 15px;
			}
			iron-icon {
				float: right;
			}
			iron-image {
				width: 200px;
				height: 200px;
				background-color: lightgray;
			}
		</style>
		
		<iron-ajax id="wp_posts" auto url="<?php echo trailingslashit( esc_url_raw( rest_url( '/wp/v2' ) ) ) . 'posts?per_page=100&_embed'; ?>" params="{{ajaxParams}}" handle-as="json" last-response="{{data}}"></iron-ajax>
		
		<paper-radio-group id="change_order" selected="date|desc" on-paper-radio-group-changed="_orderChanged">
			<paper-radio-button name="date|desc"><?php _e( 'Order by Date', 'my-theme' ); ?></paper-radio-button>
			<paper-radio-button name="title|asc"><?php _e( 'Order by Title', 'my-theme' ); ?></paper-radio-button>
		</paper-radio-group>
		
		<br>
		
		<section class="flex layout vertical">
			<template is="dom-repeat" items="{{data}}">

				<div id="{{item.id}}">
					<iron-icon icon="star" hidden$="{{!item.sticky}}"></iron-icon>
					<iron-image hidden$="{{!item.featured_media}}" src="{{item._embedded.wp:featuredmedia.0.media_details.sizes.medium.source_url}}" sizing="cover" preload fade></iron-image>
					<h1 class="title">{{item.title.rendered}}</h1>
					<div id="content" hidden$="{{!item.content}}">{{stripHTML(item.content.rendered)}}</div>
					<footer>
						<small><?php _e( 'Posted on', 'my-theme' ); ?> <em>{{formatTimestamp(item.date)}}</em></small>
					</footer>
				</div>
			</template>
		</section>
	</template>
</dom-module>
<script>
	class PostList extends Polymer.Element {
		static get is() {
			return 'post-list';
		}

		stripHTML(html) {
			var tmp = document.createElement("div");
			tmp.innerHTML = html;
			return tmp.textContent || tmp.innerText || '';
		}

		formatTimestamp(value) {
			if (value) {
				var date = new Date(Date.parse(value)),
					months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
				return months[date.getMonth()] + ' ' + date.getDate() + ', ' + date.getFullYear();
			}
		}

		_orderChanged() {
			var order = this.$.change_order.selected.split('|');
			var params = {
				'orderby': order[0],
				'order': order[1]
			};
			this.$.wp_posts.params = params;
			this.$.wp_posts.generateRequest();
		}

	}
	window.customElements.define(PostList.is, PostList);
</script>