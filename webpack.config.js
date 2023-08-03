/**
 * External dependencies
 */
const wpPot = require('wp-pot');

//// POT file.
wpPot( {
	package: 'Notifications On Post Like For BuddyBoss',
	domain: 'notifications-on-post-like-for-buddyboss',
	destFile: 'languages/notifications-on-post-like-for-buddyboss.pot',
	relativeTo: './',
	src: [ './**/*.php' ],
	bugReport: 'https://github.com/acrosswp/notifications-on-post-like-for-buddyboss/issues'
} );

// Return Array of Configurations
module.exports = [];