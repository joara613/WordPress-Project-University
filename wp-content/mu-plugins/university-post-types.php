<?php 

function university_post_types() {
	// Event Post Type
	register_post_type('event', array(
		'supports' => array('title','editor','excerpt'),
		'rewrite' => array('slug' => 'events'),
		'has_archive' => true,
		'public'=> true,
		// New Editor
		'show_in_rest' => true,
		'labels' => array(
			'name' => 'Events',
			'add_new_item' => 'Add New Event',
			'edit_item' => 'Edit Event',
			'all_items' => 'All Events',
			'sigular_name' => 'Event'
		),
		'menu_icon' => 'dashicons-calendar'
	));
	// Program Post 
	register_post_type('program', array(
		'supports' => array('title','excerpt'),
		'rewrite' => array('slug' => 'programs'),
		'has_archive' => true,
		'public'=> true,
		// New Editor
		'show_in_rest' => true,
		'labels' => array(
			'name' => 'Programs',
			'add_new_item' => 'Add New Program',
			'edit_item' => 'Edit Program',
			'all_items' => 'All Programs',
			'sigular_name' => 'Program'
		),
		'menu_icon' => 'dashicons-awards'
	));
		// Professor Post 
	register_post_type('professor', array(
		'supports' => array('title','editor','thumbnail'),
		'rewrite' => array('slug' => 'professors'),
		'public'=> true,
		// New Editor
		'show_in_rest' => true,
		'labels' => array(
			'name' => 'Professors',
			'add_new_item' => 'Add New Professor',
			'edit_item' => 'Edit Professor',
			'all_items' => 'All Professors',
			'sigular_name' => 'Professor'
		),
		'menu_icon' => 'dashicons-welcome-learn-more'
	));
}

add_action('init', 'university_post_types');

?>