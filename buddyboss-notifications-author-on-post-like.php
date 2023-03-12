<?php
/**
 * Plugin Name: BuddyBoss Notifications to Author on Post Like
 * Plugin URI:  https://acrosswp.com/
 * Description: Adds premium/custom features to BuddyBoss Platform.
 * Author:      AcrossWP
 * Author URI:  https://acrosswp.com/
 * Version:     0.0.1
 * License:     GPLv2 or later (license.txt)
 */

/**
 * This file should always remain compatible with the minimum version of
 * PHP supported by WordPress.
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;


// Registered  a new component for user like notifications
function bb_filter_notifications_get_registered_components( $component_names = array() ) {

	// Force $component_names to be an array
	if ( ! is_array( $component_names ) ) {
		$component_names = array();
	}

	// Add 'bb_user_like' component to registered components array
	array_push( $component_names, 'bb_user_like' );

	// Return component's with 'bb_user_like' appended
	return $component_names;
}
add_filter( 'bp_notifications_get_registered_components', 'bb_filter_notifications_get_registered_components' );


// this gets the saved item id, compiles some data and then displays the notification
function bb_format_buddypress_notifications( $action, $item_id, $secondary_item_id, $total_items, $format = 'string' ) {

	// New custom notifications
	if ( 'bb_user_like_action' === $action ) {
	
        $activity = new BP_Activity_Activity( $item_id );

        $author_id = $activity->user_id;
        $name = bp_core_get_username( $author_id );
	
		$custom_text = $custom_title = $name . ' liked on the post ' . get_the_title( $activity_id );
		$custom_link  = bp_activity_get_permalink( $item_id );

		// WordPress Toolbar
		if ( 'string' === $format ) {
			$return = apply_filters( 'bb_user_like_filter', '<a href="' . esc_url( $custom_link ) . '" title="' . esc_attr( $custom_title ) . '">' . esc_html( $custom_text ) . '</a>', $custom_text, $custom_link );

		// Deprecated BuddyBar
		} else {
			$return = apply_filters( 'bb_user_like_filter', array(
				'text' => $custom_text,
				'link' => $custom_link
			), $custom_link, (int) $total_items, $custom_text, $custom_title );
		}
		
		return $return;
		
	}
	
}
add_filter( 'bp_notifications_get_notifications_for_user', 'bb_format_buddypress_notifications', 10, 5 );


// this hooks to comment creation and saves the comment id
function bp_custom_add_notification( $activity_id, $user_id ) {

    // Get the activity from the database.
	$activity = new BP_Activity_Activity( $activity_id );
	$author_id = $activity->user_id;

	bp_notifications_add_notification( array(
		'user_id'           => $author_id,
		'item_id'           => $activity_id,
		'component_name'    => 'bb_user_like',
		'component_action'  => 'bb_user_like_action',
		'date_notified'     => bp_core_current_time(),
		'is_new'            => 1,
	) );
	
}
add_action( 'bp_activity_add_user_favorite', 'bp_custom_add_notification', 99, 2 );