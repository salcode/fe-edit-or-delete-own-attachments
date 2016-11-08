<?php
/**
 * Plugin Name: Edit or Delete Own Attachments
 * Plugin URI: http://salferrarello.com/edit-delete-own-attachments
 * Description: Add edit_posts and delete_posts capabilities for a user on any attachment they uploaded. This is useful if you don't want to grant all of the capability that goes with edit_posts and delete_posts.
 * Version: 1.0.1
 * Author: Sal Ferrarello
 * Author URI: http://salferrarello.com/
 *
 * @package IronCode/EditDeleteOwnAttachements
 */

add_filter( 'user_has_cap', 'fe_grant_edit_posts_delete_posts_on_own_attachments', 10, 3 );

/**
 * Add cap edit_posts and delete_posts cap_on_media()
 *
 * Filter on the current_user_can() function.
 *
 * @param array $allcaps All the capabilities of the user.
 * @param array $req_cap [0] Required capability.
 * @param array $args    [0] Requested capability.
 *                       [1] User ID.
 *                       [2] Associated object ID.
 */
function fe_grant_edit_posts_delete_posts_on_own_attachments( $allcaps, $req_cap, $args ) {

	// If no post is connected with capabilities, make no changes.
	if ( empty( $args[2] ) ) {
		return $allcaps;
	}

	$post = get_post( $args[2] );

	// If the post is not an attachment, make no changes.
	if ( 'attachment' !== get_post_type( $post ) ) {
		return $allcaps;
	}

	// If the requested capability is not edit_posts or delete_posts, make no changes.
	if ( 'edit_posts' !== $req_cap[0] && 'delete_posts' !== $req_cap[0] ) {
		return $allcaps;
	}

	// If the User is not also the post_author (i.e. they did not uploaded the image), make no changes.
	if ( intval( 0 !== $args[1] && intval( $post->post_author ) !== intval( $args[1] ) ) ) {
		return $allcaps;
	}

	// Add the capability to the user for this request.
	$allcaps[ $req_cap[0] ] = true;

	return $allcaps;
}
