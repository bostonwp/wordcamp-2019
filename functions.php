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
			'<div class="level-1">Celebrating<br />10&nbsp;years</div>' .
			'<div class="level-2">2010-2019</div>' .
			'<div class="level-4">Dates</div>' .
			'<div class="level-3">July 21-22nd 2019</div>' .
			'<div class="level-4">Location</div>' .
			'<div class="level-3">Boston University</div>' .
			'<div class="level-4">Price</div>' .
			'<div class="level-3">$50</div>',
		'filter' => false,
	);
	$buttons = array(
		'title' => '',
		'content' =>
			'<div class="button-row">' .
			'	<a href="#" class="cta-button">Register</a>' .
			'	<a href="#" class="cta-button">Volunteer</a>' .
			'</div>',
		'filter' => false,
	);
	$sponsors = array(
		'title' => 'Thank you to our Platinum Sponsors',
	);
	$subscribe = array();
	$nav_menu_1 = array( 'nav_menu' => 20 );
	$nav_menu_2 = array( 'nav_menu' => 21 );
	$news_header = array(
		'title' => '',
		'content' => '<h1 class="page-title">News</h1>',
		'filter' => false,
		'conditions' => array (
			'action' => 'show',
			'match_all' => '0',
			'rules' => array (
				array (
					'major' => 'page',
					'minor' => 'post_type-post',
					'has_children' => false,
				),
			),
		),
	);

	$social_links_content = <<<EOF
<div class="social-links-row">
<ul>
	<li><a class="wc-icon-twitter" href="https://twitter.com/wordcampboston">
		<span class="screen-reader-text">twitter: @wordcampboston</span>
	</a></li>
	<li><a class="wc-icon-facebook" href="https://facebook.com">
		<span class="screen-reader-text">WordCamp Boston facebook page</span>
	</a></li>
	<li><a class="wc-icon-youtube" href="https://youtube.com">
		<span class="screen-reader-text">WordCamp Boston youtube channel</span>
	</a></li>
	<li><a class="wc-icon-meetup" href="https://meetup.com">
		<span class="screen-reader-text">Boston WordPress meetup</span>
	</a></li>
	<li><a class="wc-icon-wp" href="https://bostonwp.org">
		<span class="screen-reader-text">BostonWP.org</span>
	</a></li>
</ul>
</div>
<p><a class="site-info-generator" href="https://wordpress.org/" rel="generator">
Proudly powered by WordPress</a><br /><a class="site-info-network" href="https://central.wordcamp.org/">Go to WordCamp Central</a></p>
EOF;
	$social_links = array(
		'title' => '',
		'content' => $social_links_content,
		'filter' => false,
	);

	$image = array (
		'attachment_id' => 332,
		'url' => 'http://boston.wordcamp.test/wp-content/uploads/sites/48/2019/03/cake.png',
		'title' => '',
		'size' => 'full',
		'width' => 260,
		'height' => 346,
		'caption' => '',
		'alt' => 'WordCamp Boston',
		'link_type' => 'custom',
		'link_url' => '',
		'image_classes' => '',
		'link_classes' => '',
		'link_rel' => '',
		'link_target_blank' => false,
		'image_title' => '',
	);

	$widget_text = array(
		1 => $banner,
		2 => $social_links,
		3 => $news_header,
		4 => $buttons,
		'_multiwidget' => 1
	);
	$widget_sponsors = array( 1 => $sponsors, '_multiwidget' => 1 );
	$widget_menus = array( 1 => $nav_menu_1, 2 => $nav_menu_2, '_multiwidget' => 1 );
	$widget_subscription = array( 1 => $subscribe, '_multiwidget' => 1 );
	$widget_media = array( 1 => $image, '_multiwidget' => 1 );

	update_option( 'widget_custom_html', $widget_text );
	update_option( 'widget_wcb_sponsors', $widget_sponsors );
	update_option( 'widget_nav_menu', $widget_menus );
	update_option( 'widget_mock_subscription_widget', $widget_subscription );
	update_option( 'widget_media_image', $widget_media );

	$sidebars['before-content-homepage-1'] = [ 'custom_html-1', 'custom_html-4' ];
	$sidebars['before-content-1'] = [ 'custom_html-3' ];
	$sidebars['sidebar-1'] = [ 'mock_subscription_widget-1', 'wcb_sponsors-1' ];
	$sidebars['footer-1'] = [ 'media_image-1', 'nav_menu-1', 'nav_menu-2', 'custom_html-2' ];

	update_option( 'sidebars_widgets', $sidebars );

	wp_die();
}
add_action( 'wp_ajax_b19-force-widgets', 'boston_2019_force_widgets' );
