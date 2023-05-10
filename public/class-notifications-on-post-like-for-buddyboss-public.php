<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://acrosswp.com
 * @since      1.0.0
 *
 * @package    Notifications_On_Post_Like_For_BuddyBoss
 * @subpackage Notifications_On_Post_Like_For_BuddyBoss/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Notifications_On_Post_Like_For_BuddyBoss
 * @subpackage Notifications_On_Post_Like_For_BuddyBoss/public
 * @author     AcrossWP <contact@acrosswp.com>
 */
class Notifications_On_Post_Like_For_BuddyBoss_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The Custom ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The Custom ID of this plugin.
	 */
	private $plugin_name_action;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->plugin_name_action = $plugin_name . '_action';
		$this->version = $version;

	}

	/**
	 * Registered  a new component for user like notifications
	 * BuddyBoss Filter Notifications Get Registered Components
	 * 
	 * @since 1.0.0
	 */
	function registered_components( $component_names = array() ) {

		// Force $component_names to be an array
		if ( ! is_array( $component_names ) ) {
			$component_names = array();
		}

		// Add 'npplfb_user_like' component to registered components array
		array_push( $component_names, $this->plugin_name );

		// Return component's with 'npplfb_user_like' appended
		return $component_names;
	}

	/**
 	 * This gets the saved item id, compiles some data and then displays the notification
	 * 
	 * @since 1.0.0
	 */
	function format_notifications( $action, $item_id, $secondary_item_id, $total_items, $format = 'string' ) {

		// New custom notifications
		if ( $this->plugin_name_action === $action ) {
		
			$activity = new BP_Activity_Activity( $item_id );

			$author_id = $activity->user_id;
			$name = bp_core_get_username( $author_id );
		
			$custom_text = $custom_title = $name . ' liked on the post ' . get_the_title( $activity_id );
			$custom_link  = bp_activity_get_permalink( $item_id );

			// WordPress Toolbar
			if ( 'string' === $format ) {
				$return = apply_filters( 'npplfb_user_like_filter', '<a href="' . esc_url( $custom_link ) . '" title="' . esc_attr( $custom_title ) . '">' . esc_html( $custom_text ) . '</a>', $custom_text, $custom_link );

			// Deprecated BuddyBar
			} else {
				$return = apply_filters( 'npplfb_user_like_filter', array(
					'text' => $custom_text,
					'link' => $custom_link
				), $custom_link, (int) $total_items, $custom_text, $custom_title );
			}
			
			return $return;
		}	

		return $action;
	}

	/**
	 * This hooks to comment creation and saves the comment id
	 * 
	 * @since 1.0.0
	 */
	function custom_add_notification( $activity_id, $user_id ) {

		// Get the activity from the database.
		$activity = new BP_Activity_Activity( $activity_id );
		$author_id = $activity->user_id;

		bp_notifications_add_notification( array(
			'user_id'           => $author_id,
			'item_id'           => $activity_id,
			'component_name'    => $this->plugin_name,
			'component_action'  => $this->plugin_name_action,
			'date_notified'     => bp_core_current_time(),
			'is_new'            => 1,
		) );
	}

}
