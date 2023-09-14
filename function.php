<?php


function wpdocs_enqueue_custom_admin_style() 
  {
    global $post_type;
    if( 'contact-form-x' == $post_type )
    {
    wp_enqueue_style( 'font_awesome','https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style( 'bootstrap', 'https://bootswatch.com/4/cosmo/bootstrap.min.css');
    wp_enqueue_style( 'formio','https://cdn.form.io/formiojs/formio.full.min.css');
    wp_enqueue_style( 'style', plugins_url( 'CONTACT-FORM-X/admin/css/style.css',__DIR__ ));
    }
  }
  add_action( 'admin_enqueue_scripts', 'wpdocs_enqueue_custom_admin_style' );



function wpdocs_enqueue_custom_admin_script() 
  {
    global $post_type;
    if( 'contact-form-x' == $post_type )
    {
    wp_enqueue_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js');
    wp_enqueue_script( 'bootstrap_jquery', '//code.jquery.com/jquery-3.4.1.slim.min.js', array(), '3.3.1', true );
    wp_enqueue_script( 'bootstrap_popper', '//cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js', array(), '1.14.7', true );
    wp_enqueue_script( 'formio', 'https://cdn.form.io/formiojs/formio.full.min.js', array(), '1.0.0', true );
    }  
  }
  add_action( 'admin_enqueue_scripts', 'wpdocs_enqueue_custom_admin_script' );

?>
<!-- MATCH CLASSES OF BOOTSTRAP AND FORM.IO AND REPLACE WITH UNIQUE CLASSES -->
<script>
function changeMatchingClasses() 
{
  // Get the <link> elements for Bootstrap and Form.io stylesheets
  const bootstrapLink = document.querySelector('link[href="https://bootswatch.com/4/cosmo/bootstrap.min.css"]');
  const formioLink = document.querySelector('link[href="https://cdn.form.io/formiojs/formio.full.min.css"]');
  
  // Get the class names from the Bootstrap and Form.io stylesheets
  const bootstrapClasses = bootstrapLink.sheet.rules.map(rule => rule.selectorText);
  const formioClasses = formioLink.sheet.rules.map(rule => rule.selectorText);
  
  // Find the matching classes between Bootstrap and Form.io
  const matchingClasses = bootstrapClasses.filter(cls => formioClasses.includes(cls));
  
  // Change the matching classes in Bootstrap
  matchingClasses.forEach(cls => {
    bootstrapLink.sheet.rules.forEach(rule => {
      if (rule.selectorText === cls) {
        const changedCls = `changed_${cls.slice(1)}`;
        rule.selectorText = changedCls;
      }
    });
  });
  
  // Change the matching classes in Form.io
  matchingClasses.forEach(cls => {
    formioLink.sheet.rules.forEach(rule => {
      if (rule.selectorText === cls) {
        const changedCls = `changed_${cls.slice(1)}`;
        rule.selectorText = changedCls;
      }
    });
  });
}
</script>
<!-- MATCH CLASSES OF BOOTSTRAP AND FORM.IO AND REPLACE WITH UNIQUE CLASSES -->
<?php



// Disable Drag and drop metabox
function disable_drag_metabox() {
  if ( 'contact-form-x' === get_current_screen()->post_type ) {
    wp_deregister_script( 'postbox' );
  }
}

// Disable Drag and drop metabox on "Add New" page
function disable_drag_metabox_new() {
  if ( 'contact-form-x' === get_current_screen()->post_type ) {
    wp_deregister_script( 'postbox' );
  }
}
add_action( 'load-post.php', 'disable_drag_metabox' );
add_action( 'load-post-new.php', 'disable_drag_metabox_new' );


function wpdocs_enqueue_custom_style() 
  {
    global $post_type;
    if( 'contact-form-x' == $post_type )
    {
    wp_enqueue_style( 'font_awesome','https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style( 'bootstrap', 'https://bootswatch.com/4/cosmo/bootstrap.min.css');
    wp_enqueue_style( 'formio','https://cdn.form.io/formiojs/formio.full.min.css');
    wp_enqueue_style( 'style', plugins_url( 'CONTACT-FORM-X/admin/css/style.css',__DIR__ ));
    }
  }
  add_action( 'wp_enqueue_scripts', 'wpdocs_enqueue_custom_style' );



function wpdocs_enqueue_custom_script() 
  {
    global $post_type;
    if( 'contact-form-x' == $post_type )
    {
    wp_enqueue_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js');
    wp_enqueue_script( 'bootstrap_jquery', '//code.jquery.com/jquery-3.4.1.slim.min.js', array(), '3.3.1', true );
    wp_enqueue_script( 'bootstrap_popper', '//cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js', array(), '1.14.7', true );
    wp_enqueue_script( 'formio', 'https://cdn.form.io/formiojs/formio.full.min.js', array(), '1.0.0', true );
    }  
  }
  add_action( 'wp_enqueue_scripts', 'wpdocs_enqueue_custom_script' );



function wporg_json( $post ) 
{
    $json = get_post_meta($post->ID, 'components', true );
    echo $json;
}

// AJAX handler for form submission
add_action('wp_ajax_contact_form_x_submit_btn', 'contact_form_x_submit');
add_action('wp_ajax_nopriv_contact_form_x_submit_btn', 'contact_form_x_submit');

function contact_form_x_submit() {
    $form_data = $_POST['form_data'];
    // Process the form data and send the email

    // Decode JSON data
    $form_data = json_decode(stripslashes($form_data), true);

    // Prepare attachments array
    $attachments = array();

    // Prepare the email template
    $html = '
        <html>
        <head>
            <style>
                /* CSS styles for the email template */
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f1f1f1;
                    color: #333333;
                }
                .container {
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                }
                h2 {
                    color: #333333;
                }
                h3 {
                    margin-top: 20px;
                    color: #666666;
                }
                .field {
                    background-color: #ffffff;
                    padding: 10px;
                    border-radius: 5px;
                    margin-bottom: 15px;
                }
                .field label {
                    font-weight: bold;
                    display: block;
                    margin-bottom: 5px;
                }
                .field p {
                    margin: 0;
                    padding: 0;
                    font-size: 14px;
                    text-transform: capitalize;
                }
                .field 
                {
                    display: flex;
                    padding: 16px 20px;
                }
                .field .field
                {
                    padding: 0px;
                    margin: 0;
                    margin-left: 6px;
                }
                .field label 
                {
                    margin-bottom: 0px;
                    text-transform: capitalize;
                    font-size: 14px;
                    margin-left: 8px;
                    margin-right: 8px;
                    
                }
            </style>
        </head>
        <body style="background:#f3f2f0;">
            <div class="container">
                <h2>Form Submission</h2>';
    
    // Iterate over form fields
    foreach ($form_data as $field => $value) {
        if ($field === 'submit' || $field === 'metadata' || $field === 'data' || $field === 'survey') {
            // Skip fields that are not needed in the email body
            continue;
        }

        $attachment_data = null;
        $attachment_name = null;
        $attachment_path = null;
        

        if (is_array($value)) {
            // Handle file field attachments
            foreach ($value as $file) {
                if (isset($file['storage']) && isset($file['url']) && $file['storage'] === 'base64') {
                    $attachment_data = extract_image_data($file['url']);
                    if ($attachment_data) {
                        $attachment_name = generate_attachment_name($file['name']);
                        $attachment_path = save_attachment($attachment_data, $attachment_name);
                        if ($attachment_path) {
                            $attachments[] = $attachment_path;
                        } else {
                            error_log('Failed to save attachment: ' . $attachment_name);
                        }
                    }
                }
            }
        } elseif (strpos($value, 'data:image') === 0) {
            // Handle regular image fields as attachments
            $attachment_data = extract_image_data($value);
            if ($attachment_data) {
                $attachment_name = generate_attachment_name($field);
                $attachment_path = save_attachment($attachment_data, $attachment_name);
                if ($attachment_path) {
                    $attachments[] = $attachment_path;
                } else {
                    error_log('Failed to save attachment: ' . $attachment_name);
                }
            }
        } else {
            // Regular form field
            $html .= '
                <div class="field">
                    <label>' . $field . ':</label>
                    <p>' . $value . '</p>
                </div>';
        }
    }



// Include the Location fields in the email template
foreach ($form_data as $field_name => $field_value) {
    if (strpos($field_name, 'address') === 0 && is_array($field_value) && isset($field_value['display_name'])) {
        $html .= '
        <div class="field">
            <label>' . $field_name . ':</label>
            <p>' . $field_value['display_name'] . '</p>
        </div>';
    }
}




// Add survey fields
// Loop through dynamic survey fields
foreach ($form_data as $field_name => $field_value) {
    if (strpos($field_name, 'survey') === 0 && is_array($field_value)) {
        $html .= '<div class="field">';
        $html .= '<label>' . $field_name . ':</label>';

        foreach ($field_value as $survey_field => $survey_value) {
            $html .= '<div class="field">';
            $html .= '<label>' . $survey_field . ':</label>';
            $html .= '<p>' . $survey_value . '</p>';
            $html .= '</div>';
        }

        $html .= '</div>';
    }
}


// Define a constant for "</p></div>"
define('HTML_CLOSING_TAGS', '</p></div>');

// Append browser details
$html .= '<div class="field"><label>User Agent:</label><p> ' . $_SERVER['HTTP_USER_AGENT'] . HTML_CLOSING_TAGS;
$html .= '<div class="field"><label>Current Page:</label><p> ' . $_SERVER['HTTP_REFERER'] . HTML_CLOSING_TAGS;
$html .= '<div class="field"><label>Submission Date:</label><p> ' . date('Y-m-d H:i:s') . HTML_CLOSING_TAGS;



    $html .= '</div></body></html>';

    // Example: Send email using wp_mail function
    // $to = 'sharjeelkhanvmi@gmail.com';
    // $subject = 'Form Submission';

    // Make sure you have the correct form ID
    $form_id = $_POST['post_id']; // Replace 6 with the actual ID of your form

    // Retrieve the email_to and email_subject from post meta
    $to = get_post_meta($form_id, 'email_to', true);
    $subject = get_post_meta($form_id, 'email_subject', true);


    $message = $html;
    $headers = array('Content-Type: text/html; charset=UTF-8');

    // Send email with attachments
    $sent = wp_mail($to, $subject, $message, $headers, $attachments);

    // Cleanup temporary files
    foreach ($attachments as $attachment) {
        if (file_exists($attachment)) {
            unlink($attachment);
        }
    }

    if ($sent) {
        // Return a success JSON response
        wp_send_json_success('Form submitted successfully');
    } else {
        // Return an error JSON response
        error_log('Failed to submit the form');
        wp_send_json_error('Failed to submit the form');
    }
    wp_die();
}

function extract_image_data($value) {
    $image_data = substr($value, strpos($value, ',') + 1);
    return base64_decode($image_data);
}

function generate_attachment_name($field) {
    $filename = sanitize_file_name($field);
    $extension = 'png'; // Change the extension if needed
    $attachment_name = $filename . '.' . $extension;
    return $attachment_name;
}

function save_attachment($data, $name) {
    $upload_dir = wp_upload_dir();
    $attachment_path = $upload_dir['path'] . '/' . $name;

    if (file_put_contents($attachment_path, $data)) {
        return $attachment_path;
    } else {
        return false;
    }
}




//HIDE PUBLICSH BUTOTN ACTIONS LIKE DRAFT AND OTHERS IN SINGLE POST OF FORM
// function hide_publishing_actions(){
//     $my_post_type = 'contact-form-x';
//     global $post;
//     if($post->post_type == $my_post_type){
//         echo '
//             <style type="text/css">
//                 #misc-publishing-actions,
//                 #minor-publishing-actions{
//                     display:none;
//                 }
//             </style>
//         ';
//     }
// }
// add_action('admin_head-post.php', 'hide_publishing_actions');
// add_action('admin_head-post-new.php', 'hide_publishing_actions');
//HIDE PUBLICSH BUTOTN ACTIONS LIKE DRAFT AND OTHERS IN SINGLE POST OF FORM



//HIDE ADMIN BAR VIEW POST BUTTON ON HEADER OF ADMIN IN SINGLE POST OF FORM
// function hide_view_post_from_admin_bar() {
//     global $wp_admin_bar, $post;
    
//     // Replace 'your_custom_post_type' with the actual post type name
//     if (is_admin() && 'contact-form-x' === $post->post_type) {
//         $wp_admin_bar->remove_menu('view');
//     }
// }

// add_action('wp_before_admin_bar_render', 'hide_view_post_from_admin_bar');
//HIDE ADMIN BAR VIEW POST BUTTON ON HEADER OF ADMIN IN SINGLE POST OF FORM
