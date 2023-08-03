<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://acrosswp.com
 * @since      1.0.0
 *
 * @package    Notifications_On_Post_Like_For_BuddyBoss_Notification
 * @subpackage Notifications_On_Post_Like_For_BuddyBoss_Notification/public
 */


if ( class_exists( 'BP_Core_Notification_Abstract' ) ) {

    /**
     * The public-facing functionality of the plugin.
     *
     * Defines the plugin name, version, and two examples hooks for how to
     * enqueue the public-facing stylesheet and JavaScript.
     *
     * @package    Notifications_On_Post_Like_For_BuddyBoss_Notification
     * @subpackage Notifications_On_Post_Like_For_BuddyBoss_Notification/public
     * @author     AcrossWP <contact@acrosswp.com>
     */
    class Notifications_On_Post_Like_For_BuddyBoss_Notification extends BP_Core_Notification_Abstract {
 
        /**
         * Instance of this class.
         *
         * @var object
         */
        private static $instance = null;

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
         * Get the instance of this class.
         *
         * @return null|Notifications_On_Post_Like_For_BuddyBoss_Notification|Controller|object
         */
        public static function instance() {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self();
            }
     
            return self::$instance;
        }
     
        /**
         * Constructor method.
         */
        public function __construct( $plugin_name ) {

            
            $this->plugin_name = $plugin_name;
		    $this->plugin_name_action = $plugin_name . '_action';
		    $this->plugin_name_message = $plugin_name . '_message';

            $this->start();

        }
     
        /**
         * Initialize all methods inside it.
         *
         * @return mixed|void
         */
        public function load() {
     
            /**
             * Register Notification Group.
             *
             * @param string $group_key         Group key.
             * @param string $group_label       Group label.
             * @param string $group_admin_label Group admin label.
             * @param int    $priority          Priority of the group.
             */
            $this->register_notification_group(
                $this->plugin_name,
                esc_html__( 'Notification on Your Post', 'notifications-on-post-like-for-buddyboss' ), // For the frontend.
                esc_html__( 'Notification on Author Post', 'notifications-on-post-like-for-buddyboss' ) // For the backend.
            );
     
            $this->register_custom_notification();
        }
     
        /**
         * Register notification for user mention.
         */
        public function register_custom_notification() {

            $notification_read_only    = true;
            $notification_tooltip_text = __( 'Requires Likes to enable', 'buddyboss' );

            if ( function_exists( 'bp_is_activity_like_active' ) && true === bp_is_activity_like_active() ) {
                $notification_tooltip_text = __( 'Required by activity Likes', 'buddyboss' );
                $notification_read_only    = false;
            }

            /**
             * Register Notification Type.
             *
             * @param string $notification_type        Notification Type key.
             * @param string $notification_label       Notification label.
             * @param string $notification_admin_label Notification admin label.
             * @param string $notification_group       Notification group.
             * @param bool   $default                  Default status for enabled/disabled.
             */
            $this->register_notification_type(
                $this->plugin_name_action,
                esc_html__( 'A member like your post', 'notifications-on-post-like-for-buddyboss' ),
                esc_html__( 'Member like some author post', 'notifications-on-post-like-for-buddyboss' ),
                $this->plugin_name,
                function_exists( 'bp_is_activity_like_active' ) && true === bp_is_activity_like_active(),
                $notification_read_only,
                $notification_tooltip_text
            );
     
            /**
             * Add email schema.
             *
             * @param string $email_type        Type of email being sent.
             * @param array  $args              Email arguments.
             * @param string $notification_type Notification Type key.
             */
            $this->register_email_type(
                $this->plugin_name_message,
                array(
                    /* translators: do not remove {} brackets or translate its contents. */
                    'email_title'         => __( '[{{{site.name}}}] {{user_like.name}} like your post', 'notifications-on-post-like-for-buddyboss' ),
                    /* translators: do not remove {} brackets or translate its contents. */
                    'email_content'       => __( "<a href=\"{{{user_like.url}}}\">{{user_like.name}}</a> started following you.\n\n{{{member.card}}}", 'notifications-on-post-like-for-buddyboss' ),
                    /* translators: do not remove {} brackets or translate its contents. */
                    'email_plain_content' => __( "{{user_like.name}} started following you.\n\nTo learn more about them, visit their profile: {{{reaction_reactions.url}}}", 'notifications-on-post-like-for-buddyboss' ),
                    'situation_label'     => __( 'A posts author get like by members', 'notifications-on-post-like-for-buddyboss' ),
                    'unsubscribe_text'    => __( 'You will no longer receive emails when someone like your posts.', 'notifications-on-post-like-for-buddyboss' ),
                ),
                $this->plugin_name_action
            );
     
            /**
             * Register notification.
             *
             * @param string $component         Component name.
             * @param string $component_action  Component action.
             * @param string $notification_type Notification Type key.
             * @param string $icon_class        Notification Small Icon.
             */
            $this->register_notification(
                $this->plugin_name,
                $this->plugin_name_action,
                $this->plugin_name_action,
                ''
            );
     
            /**
             * Register Notification Filter.
             *
             * @param string $notification_label    Notification label.
             * @param array  $notification_types    Notification types.
             * @param int    $notification_position Notification position.
             */
            $this->register_notification_filter(
                __( 'Custom Notification Filter', 'notifications-on-post-like-for-buddyboss' ),
                array( $this->plugin_name_action ),
                5
            );
        }
     
        /**
         * Format the notifications.
         *
         * @param string $content               Notification content.
         * @param int    $item_id               Notification item ID.
         * @param int    $secondary_item_id     Notification secondary item ID.
         * @param int    $action_item_count     Number of notifications with the same action.
         * @param string $component_action_name Canonical notification action.
         * @param string $component_name        Notification component ID.
         * @param int    $notification_id       Notification ID.
         * @param string $screen                Notification Screen type.
         *
         * @return array
         */
        public function format_notification( $content, $item_id, $secondary_item_id, $action_item_count, $component_action_name, $component_name, $notification_id, $screen ) {
            return $content;
        }
    }
}
