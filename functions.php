<?php
/**
 * Enqueue the parent theme's styles.
 * You can leave this out if you're replacing the parent theme's CSS.
 */
function boston_2019_styles() {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css', false, time() );
}
add_action( 'wp_enqueue_scripts', 'boston_2019_styles' );

/**
 * Turn on "new" features for this local site. New features are enabled based on time of site
 * creation, approximated based on ID number in the wordcamp.org network. That doesn't map to
 * local sites, so there is difference in markup for some features if we don't whitelist the ID.
 *
 * Change this to the Site ID of your local development site (though it will likely be 47 if
 * this is the first additional site you've created)
 */
function boston_2019_enable_features( $sites ) {
	$sites[] = 47;
	$sites[] = 48;
	return $sites;
}
add_filter( 'wcpt_speaker_post_avatar_enabled_site_ids',       'boston_2019_enable_features' );
add_filter( 'wcpt_session_post_speaker_info_enabled_site_ids', 'boston_2019_enable_features' );
add_filter( 'wcpt_session_post_slides_info_enabled_site_ids',  'boston_2019_enable_features' );
add_filter( 'wcpt_session_post_video_info_enabled_site_ids',   'boston_2019_enable_features' );
add_filter( 'wcpt_speaker_post_session_info_enabled_site_ids', 'boston_2019_enable_features' );

require_once __DIR__ . '/mock-widget-subscriptions.php';

/**
 * Force-set some widgets by visiting
 *	[URL]/wp-admin/admin-ajax.php?action=b19-force-widgets
 */
function boston_2019_force_widgets() {
	$sidebars = []; //get_option( 'sidebars_widgets' );

	$banner = array(
		'title' => '',
		'content' =>
			'<div class="level-1">Celebrating 10 years</div>' .
			'<div class="level-2">2010-2019</div>' .
			'<div class="level-4">Dates</div>' .
			'<div class="level-3">July 21-22nd 2019</div>' .
			'<div class="level-4">Location</div>' .
			'<div class="level-3">Boston University</div>' .
			'<div class="level-4">Price</div>' .
			'<div class="level-3">$50</div>' .
			'<div class="button-row">' .
			'	<a href="#" class="button">Register</a>' .
			'	<a href="#" class="button">Volunteer</a>' .
			'</div>',
		'filter' => false,
	);
	$sponsors = array(
		'title' => 'Thank you to our Platinum Sponsors',
	);
	$subscribe = array();
	$nav_menu_1 = array( 'nav_menu' => 20 );
	$nav_menu_2 = array( 'nav_menu' => 21 );
	$social_links = array(
		'title' => '',
		'content' =>
			'<div class="social-links-row">' .
			'	<ul>' .
			'		<li><a href="https://twitter.com/wordcampboston">twitter: @wordcampboston</a></li>' .
			'		<li><a href="https://facebook.com">WordCamp Boston facebook page</a></li>' .
			'		<li><a href="https://youtube.com">WordCamp Boston youtube channel</a></li>' .
			'		<li><a href="https://meetup.com">Boston WordPress meetup</a></li>' .
			'		<li><a href="https://bostonwp.org">BostonWP.org</a></li>' .
			'	</ul>' .
			'</div>' .
			'<p>Proudly Powered by WordPress</p>' .
			'<p>Go to WordCamp Central</p>',
		'filter' => false,
	);

	$widget_text = array( 1 => $banner, 2 => $social_links, '_multiwidget' => 1 );
	$widget_sponsors = array( 1 => $sponsors, '_multiwidget' => 1 );
	$widget_menus = array( 1 => $nav_menu_1, 2 => $nav_menu_2, '_multiwidget' => 1 );
	$widget_subscription = array( 1 => $subscribe, '_multiwidget' => 1 );

	update_option( 'widget_custom_html', $widget_text );
	update_option( 'widget_wcb_sponsors', $widget_sponsors );
	update_option( 'widget_nav_menu', $widget_menus );
	update_option( 'widget_mock_subscription_widget', $widget_subscription );

	$sidebars['before-content-homepage-1'] = [ 'custom_html-1' ];
	$sidebars['sidebar-1'] = [ 'mock_subscription_widget-1', 'wcb_sponsors-1' ];
	$sidebars['footer-1'] = [ 'nav_menu-1', 'nav_menu-2', 'custom_html-2' ];

	update_option( 'sidebars_widgets', $sidebars );

	wp_die();
}
add_action( 'wp_ajax_b19-force-widgets', 'boston_2019_force_widgets' );
