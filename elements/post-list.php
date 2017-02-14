<?php
	include_once('../../../../wp-config.php'); // Load WP Config (relative to current file)
?>

<link rel="import" href="../bower_components/polymer/polymer.html">
<!--link rel="import" href="../bower_components/iron-image/iron-image.html"--><!-- Already loaded in "elements.html" -->
<link rel="import" href="../bower_components/iron-ajax/iron-ajax.html">
<link rel="import" href="./post-card.html">

<dom-module id="post-list" attributes="show">
	<style>
		:host {
		  display: block;
		  width: 100%;
		}
		post-card {
		  margin-bottom: 15px;
		}
	</style>
	<template>
		<iron-ajax id="wp_posts" auto url="<?php echo trailingslashit( esc_url_raw( rest_url( '/wp/v2' ) ) ) . 'posts'; ?>" params="{{ajaxParams}}" handle-as="json" last-response="{{data}}"></iron-ajax>
		
		<paper-material elevation="4" class="container narrow">
			<!--paper-checkbox id="load_users" checked disabled on-change="usersChanged"> <?php _e( 'All Users', 'my-theme' ); ?></paper-checkbox-->
			
			<paper-radio-group id="change_order" selected="date" on-paper-radio-group-changed="orderChanged">
				<paper-radio-button name="date"><?php _e( 'Order by Date', 'my-theme' ); ?></paper-radio-button>
				<paper-radio-button name="title"><?php _e( 'Order by Title', 'my-theme' ); ?></paper-radio-button>
			</paper-radio-group>
		</paper-material>
		
		<br>
		
		<section class="flex layout vertical">
			<template is="dom-repeat" items="{{data}}">
				<post-card id="{{item.ID}}">
					<div class="card-header">
						<iron-icon icon="star" hidden$="{{!item.sticky}}" style="float: right;"></iron-icon>
						<iron-image hidden$="{{!item.featured_image}}" src="{{item.featured_image.source}}" style="width: 250px; height: 250px;" sizing="contain" preload fade></iron-image>
						<h1 class="title">{{item.title.rendered}}</h1>
						<div id="content" hidden$="{{!item.content}}">{{stripHTML(item.content.rendered)}}</div>
						<footer>
							<small>Posted on <em>{{formatTimestamp(item.date)}}</em></small>
						</footer>
					</div>
				</post-card>
			</template>
		</section>
	</template>
</dom-module>
<script>
	var app = document.querySelector("#app");
	
	Polymer({
		is: "post-list",
		stripHTML: function(html) {
			var tmp = document.createElement("div");
			tmp.innerHTML = html;
			return tmp.textContent || tmp.innerText || '';
		},
		formatTimestamp: function(value) {
			if (value) {
				var date = new Date(Date.parse(value)),
					months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
				return months[date.getMonth()] + ' ' + date.getDate() + ', ' + date.getFullYear();
			}
		},
		getUser: function(e) {
			var userid = e.model.item.author.ID,
				username = e.model.item.author.username;
			//alert("Get userid " + userid);
			this.loadUser(userid, username);
			this.$.wp_posts.generateRequest();
		},
		loadUser: function(userid, username) {
			//alert("Load userid " + userid);
			var params = {
				"filter[author]": userid
			};
			this.$.wp_posts.params = params; // <iron-ajax> Get Posts from Author: params = '{"filter[author]": "1"}'
			app.current_user = " / " + username; // Add Username to Page title
			this.$.load_users.checked = false;
			this.$.load_users.disabled = false;
		},
		usersChanged: function(e) {
			if (!this.checked) {
				//alert("Load all users");
				e.target.disabled = true;
				this.$.wp_posts.params = '';
				this.$.wp_posts.generateRequest();
				app.current_user = '';
			}
		},
		orderChanged: function() {
			var filter = this.$.change_order.selected;
			//alert("Order list by " + filter);
			var params = {
				"order": "asc",
				"orderby": filter
			};
			this.$.wp_posts.params = params;
			this.$.wp_posts.generateRequest();
		}
	});
</script>