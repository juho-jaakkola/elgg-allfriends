<?php
/**
 * AllFriends
 * 
 * @package AllFriends
 */

function allfriends_init () {
	// Register handler for adding friends
	elgg_register_plugin_hook_handler('register', 'user', 'allfriends_friendall');
	
	// Prevent users from getting flooded with "new friend" notifications
	elgg_unregister_event_handler('create', 'friend', 'relationship_notification_hook');
}

/**
 * Add user as friend of all other users at registration
 */
function allfriends_friendall ($hook, $type, $value, $params) {
	$new_user = $params['user'];

	// Get all existing users
	$users = new ElggBatch('elgg_get_entities', array(
		'type' => 'user',
		'limit' => 0,
	));

	// Add friendship between users
	foreach ($users as $user) {
		$new_user->addFriend($user->getGUID());
		$user->addFriend($new_user->getGUID());
	}
}

elgg_register_event_handler('init', 'system', 'allfriends_init');