<?php

function wporg_custom_post_type() 
{
	register_post_type('contact-form-x',
		array(
			'labels'      => array(
				'name'          => __( 'Contact Forms', 'contact-form-x-text-domain' ),
				'singular_name' => __( 'Contact', 'contact-form-x-text-domain' ),
                'menu_name'             => __( 'Contact', 'contact-form-x-text-domain' ),
                'name_admin_bar'        => __( 'Contact Form', 'contact-form-x-text-domain' ),
                'all_items'             => __( 'Contact Forms', 'contact-form-x-text-domain' ),
                'add_new_item'          => __( 'Add New Contact Form', 'contact-form-x-text-domain' ),
                'add_new'               => __( 'Add New', 'contact-form-x-text-domain' ),
                'new_item'              => __( 'New Form', 'contact-form-x-text-domain' ),
                'edit_item'             => __( 'Edit Form', 'contact-form-x-text-domain' ),
			),
			'public'      => true,
			'has_archive' => false,
			'rewrite'     => array( 'slug' => 'contact-form-x' ), // my custom slug
			'publicly_queryable' => true,  // you should be able to query it
			'show_ui' => true,  // you should be able to edit it in wp-admin
			'exclude_from_search' => true,  // you should exclude it from search results
			'show_in_nav_menus' => false,  // you shouldn't be able to add it to menus
			'has_archive' => false,  // it shouldn't have archive page
            'menu_icon' => 'dashicons-feedback', // Set the icon to dashicons-email
		)
	);
}
add_action('init', 'wporg_custom_post_type');


add_action('init', 'my_remove_editor_from_post_type');
function my_remove_editor_from_post_type() {
remove_post_type_support( 'contact-form-x', 'editor' );
}


add_action('add_meta_boxes', 'remove_contact_form_x_meta_boxes');
function remove_contact_form_x_meta_boxes() {
    remove_meta_box('slugdiv', 'contact-form-x', 'normal');
}

add_filter('post_type_link', 'remove_contact_form_x_permalink', 10, 2);
function remove_contact_form_x_permalink($permalink, $post) {
    if ($post->post_type == 'contact-form-x') {
        return false;
    }
    return $permalink;
}

add_filter('get_sample_permalink_html', 'remove_contact_form_x_permalink_html', 10, 5);
function remove_contact_form_x_permalink_html($return, $id, $new_title, $new_slug, $post) {
    if ($post->post_type == 'contact-form-x') {
        return false;
    }
    return $return;
}

function custom_post_type_updated_messages( $messages ) {
    $post_type = 'contact-form-x';
    $messages[$post_type] = array(
        0 => '', // Unused. Messages start at index 1.
        1 => __( 'Contact Form updated successfully.', 'contact-form-x-text-domain' ),
        2 => __( 'Form updated successfully.', 'contact-form-x-text-domain' ),
        3 => __( 'Form moved to Trash.', 'contact-form-x-text-domain' ),
        4 => __( 'Form updated successfully.', 'contact-form-x-text-domain' ),
        5 => __( 'Form restored from the Trash.', 'contact-form-x-text-domain' ),
        6 => __( 'Contact Form published successfully.', 'contact-form-x-text-domain' ),
        7 => __( 'Contact Form saved.', 'contact-form-x-text-domain' ),
        8 => __( 'Contact Form submitted.', 'contact-form-x-text-domain' ),
        9 => __( 'Contact Form draft updated.', 'contact-form-x-text-domain' )
        /* Add more messages here as needed */
    );
    return $messages;
}
add_filter( 'post_updated_messages', 'custom_post_type_updated_messages' );


//DELETE MESSAGE TO TRASH
add_filter( 'bulk_post_updated_messages', function ( $bulk_messages, $bulk_counts )
{
    $bulk_messages['contact-form-x']['trashed'] = _n( '%s form successfully deleted.', '%s forms successfully deleted.', $bulk_counts['trashed'] );
    return $bulk_messages;
}, 10, 2 );


//RESTORE MESSAGE TO TRASH
add_filter( 'bulk_post_updated_messages', function ( $bulk_messages, $bulk_counts )
{
    $bulk_messages['contact-form-x']['untrashed'] = _n( '%s form successfully restored.', '%s forms successfully restored.', $bulk_counts['untrashed'] );
    return $bulk_messages;
}, 10, 2 );