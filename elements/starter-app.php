<?php
	include_once( '../../../../wp-config.php' ); // Load WP Config (relative to current file)
	
	$page_on_front = get_option( 'page_on_front' );
	$page_for_posts = get_option( 'page_for_posts' );
	$search_enabled = get_theme_mod( 'search_enabled', '1' ); // get custom meta-value
	$menu_name = 'main-menu';

	$header_logo = get_theme_mod( 'header_logo' ); // get custom meta-value

	$menu_items_pages = themes_starter_get_menu_items( $menu_name ); // see functions.php
?>

<!-- Polymer elements are imported via bower.json -->
<link rel="import" href="../bower_components/polymer/polymer-element.html">
<link rel="import" href="../bower_components/app-layout/app-drawer/app-drawer.html">
<link rel="import" href="../bower_components/app-layout/app-drawer-layout/app-drawer-layout.html">
<link rel="import" href="../bower_components/app-layout/app-header/app-header.html">
<link rel="import" href="../bower_components/app-layout/app-header-layout/app-header-layout.html">
<link rel="import" href="../bower_components/app-layout/app-scroll-effects/app-scroll-effects.html">
<link rel="import" href="../bower_components/app-layout/app-toolbar/app-toolbar.html">
<link rel="import" href="../bower_components/app-route/app-location.html">
<link rel="import" href="../bower_components/app-route/app-route.html">

<link rel="import" href="../bower_components/iron-ajax/iron-ajax.html">
<link rel="import" href="../bower_components/iron-pages/iron-pages.html">
<link rel="import" href="../bower_components/iron-selector/iron-selector.html">
<link rel="import" href="../bower_components/iron-icons/iron-icons.html">

<link rel="import" href="../bower_components/paper-button/paper-button.html">
<link rel="import" href="../bower_components/paper-input/paper-input.html">
<link rel="import" href="../bower_components/paper-dialog/paper-dialog.html">
<link rel="import" href="../bower_components/paper-icon-button/paper-icon-button.html">

<!-- Custom elements -->
<link rel="import" href="font-roboto.html">

<?php if ( wp_count_posts()->publish >= 1 ) : ?>
	<link rel="import" href="post-list.php">
<?php endif; ?>

<dom-module id="starter-app">
	<template>
		<style>
			:host {
				--app-primary-color: #1E88E5;
				--app-secondary-color: black;
				display: block;
			}

			a {
				color: var(--app-primary-color);
			}
			
			paper-button {
				background-color: var(--paper-pink-a200);
				color: #FFF;
				--paper-button-raised-keyboard-focus: {
					background-color: var(--paper-pink-100);
					color: var(--paper-pink-a200);
				};
			}

			app-drawer-layout:not([narrow]) [drawer-toggle] {
				display: none;
			}

			app-header {
				color: #FFF;
				background-color: var(--app-primary-color);
			}
			app-header paper-icon-button {
				--paper-icon-button-ink-color: #FFF;
			}

			iron-pages {
				padding: 30px;
			}

			.logo img {
				max-height: 50px;
				max-width: 100%;
				display: block;
			}

			.footer {
				margin-top: 20px !important;
			}
			.footer, .drawer-list {
				margin: 0 20px;
			}
			.drawer-list a {
				display: block;
				padding: 0 16px;
				text-decoration: none;
				color: var(--app-secondary-color);
				line-height: 40px;
			}
			.drawer-list a.iron-selected {
				color: #000;
				font-weight: bold;
			}

			paper-input, #searchlist {
				margin: 10px 20px;
			}

			#searchcontainer {
				position: relative;
			}
			#searchlist {
				position: absolute;
				top: 100%;
				left: 0;
				background-color: #FFF;
				border: 1px solid var(--app-secondary-color);
			}

			.searchitem {
				font-size: 14px;
				display: block;
				margin: 0;
				padding: 5px 10px;
				color: var(--app-primary-color);
				text-decoration: none;
			}
			.searchitem:hover {
				color: var(--app-secondary-color);
			}
		</style>

		<app-location route="{{route}}"></app-location>
		<app-route route="{{route}}" pattern="<?php echo untrailingslashit( get_blog_details()->path ); ?>/:page" data="{{routeData}}" tail="{{subroute}}"></app-route>
		
		<app-drawer-layout fullbleed>
			<!-- Drawer content -->
			<app-drawer id="drawer" slot="drawer">
				<app-toolbar class="logo">
					<?php
						if ( isset( $header_logo ) && ! empty( $header_logo ) ) :
					?>
						<img src="<?php echo esc_url( $header_logo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" />
					<?php
						else :
							echo esc_attr( get_bloginfo( 'name', 'display' ) );
						endif;
					?>
				</app-toolbar>

				<?php if ( isset( $search_enabled ) && '1' === $search_enabled ) : ?>
					<iron-ajax id="wp_posts" auto url="<?php echo trailingslashit( esc_url_raw( rest_url( '/wp/v2' ) ) ) . 'pages?search'; ?>" params="{{ajaxParams}}" handle-as="json" last-response="{{data}}"></iron-ajax>

					<div id="searchcontainer">
						<paper-input label="<?php _e( 'Search', 'my-theme' ); ?>" on-value-changed="_onSearchValueChanged" value="{{searchValue}}">
							<iron-icon icon="search" slot="prefix"></iron-icon>
							<input>
						</paper-input>

						<div id="searchlist" style="display: none;">
							<iron-selector id="search" selected="{{routeData.page}}" attr-for-selected="data-page" role="navigation">
								<template is="dom-repeat" items="{{data}}">
									<a class="searchitem" href="{{item.link}}" data-page="{{item.slug}}" on-tap="_onSearchItemSelect">{{item.title.rendered}}</a>
								</template>
							</iron-selector>
						</div>
					</div>
				<?php endif; ?>

				<iron-selector id="menu" class="drawer-list" selected="{{routeData.page}}" fallback-selection="index" attr-for-selected="data-page" role="navigation">
					<?php
						// Get menu items based on type: "$menu_items_pages" defined on top
						foreach ( $menu_items_pages as $key => $value ) {
							$title = $value['title'];
							$slug = $value['slug'];
							$url = $value['url'];

							echo '<a href="' . $url . '" data-page="' . $slug . '">' . $title . '</a>';

							/*$menu_items = '';

							if ( empty( $child ) ) :
								$menu_items .= '<a data-page="' . $slug . '" href="' . $url . '"><span>' . $title . '</span></a>';
							else :
								$menu_items .= '<div>';
									$menu_items .= '<p><span>' . $title . '</span><iron-icon icon="expand-more"></iron-icon></p>';
									$menu_items .= '<iron-selector class="menu-content" selected="{{routeData.page}}" attr-for-selected="data-page">';
										foreach ( $child as $key => $value ) {
											$menu_items .= '<a data-page="' . $value['slug'] . '" href="' . $value['url'] . '"><span>' . $value['title'] . '</span></a>';
										}
									$menu_items .= '</iron-selector>';
								$menu_items .= '</div>';
							endif;

							echo $menu_items;*/
						}
					?>
				</iron-selector>

				<p class="footer">
					<small>&copy; <?php echo date( 'Y' ); ?> <?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?></small>
				</p>
			</app-drawer>

			<!-- Main content -->
			<app-header-layout has-scrolling-region>
				<app-header slot="header" condenses reveals effects="waterfall">
					<app-toolbar>
						<paper-icon-button icon="menu" drawer-toggle></paper-icon-button>
						<div main-title><?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?></div>
					</app-toolbar>
				</app-header>

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
						exit();
					endif;
				?>

				<iron-pages selected="[[routeData.page]]" attr-for-selected="data-page" fallback-selection="index" role="main">
					<!-- Content Pages -->
					<?php
						$section = '';
						
						// Get content of all menu items
						foreach ( $menu_items_pages as $key => $value ) {
							$id = $value['pageid'];
							$slug = $value['slug'];
							
							$section .= '<div data-page="' . $slug . '">' . PHP_EOL;
								$section .= '<article>' . PHP_EOL;
									if ( $id === $page_for_posts ) :
										$section .= '<post-list show="all"></post-list>'; // Custom Web Component
									elseif ( ! empty( $id ) ) :
										$section .= apply_filters( 'the_content', get_post_field( 'post_content', $id ) ) . PHP_EOL;
									endif;
								$section .= '</article>' . PHP_EOL;
							$section .= '</div>' . PHP_EOL;
						}
						
						// 404 - Not found
						$section .= '<div data-page="404">' . PHP_EOL;
							$section .= '<article>' . PHP_EOL;
								$section .= '<p>' . __( 'Page not found', 'my-theme' ) . '</p>' . PHP_EOL;
							$section .= '</article>' . PHP_EOL;
						$section .= '</div>' . PHP_EOL;
						
						echo $section;
					?>
				</iron-pages>
			</app-header-layout>
		</app-drawer-layout>
	</template>

	<script>
		class MyApp extends Polymer.Element {

			static get is() {
				return 'starter-app';
			}

			static get properties() {
				return {
					page: {
						type: String,
						reflectToAttribute: true,
						observer: '_routePageChanged',
					},
				};
			}

			static get observers() {
				return [
					'_routePageChanged(routeData.page)',
				];
			}

			_routePageChanged(page) {
				if (page === undefined) {
					return;
				}

				this.page = page || 'index';

				// Close a non-persistent drawer when the page & route are changed.
				if (!this.$.drawer.persistent) {
					this.$.drawer.close();
				}
			}

			_onSearchValueChanged(e) {
				var query = e.detail.value;

				if ( query.length > 2 ) {
					this.$.searchlist.style.display = 'block';

					// Init Typeahead
					var params = {
						'search': query
					};
					this.$.wp_posts.params = params;
					this.$.wp_posts.generateRequest();
				} else {
					this.$.searchlist.style.display = 'none';
				}
			}

			_onSearchItemSelect(e) {
				var slug = e.model.get('item.slug');

				// Close Searchlist
				this.$.searchlist.style.display = 'none';
			}
			
		}

		window.customElements.define(MyApp.is, MyApp);
	</script>
</dom-module>
