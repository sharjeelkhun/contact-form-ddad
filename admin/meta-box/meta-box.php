<?php

/* Register a hook to fire only when the "my-cpt-slug" post type is saved */
add_action( 'save_post_contact-form-ddad', 'myplugin_save_postdata', 10, 3 );

/* When a specific post type's post is saved, saves our custom data
 * @param int     $post_ID Post ID.
 * @param WP_Post $post    Post object.
 * @param bool    $update  Whether this is an existing post being updated or not.
*/

function myplugin_save_postdata( $post_id, $post, $update ) 
{

  if ( isset( $_POST['components'] ) ) {
    $escaped_json = $_POST['components'];
    if( $escaped_json ) 
    {
      update_post_meta( $post_id, 'components', $escaped_json );
    }
  }
  
}

function cf_x_add_custom_box() 
{
  $screens = ['post', 'contact-form-ddad'];
  foreach ($screens as $screen) 
  {
      add_meta_box(
          'cf_x_box_id',                 // Unique ID
          'Contact Form X Builder',      // Box title
          'cf_x_custom_box_html',        // Content callback
          $screen,                       // Post type
          'normal',                      // Context
          'high'                         // Priority
      );
      
      if ($screen === 'post') {
          add_action('edit_form_after_title', 'cf_x_display_shortcode');
      }
  }
}

function cf_x_display_shortcode() 
{
  global $post;
  if ($post->post_type !== 'contact-form-ddad') 
  {
      return;
  }
  echo do_shortcode('[contact-form-ddad id="' . $post->ID . '"]');
}

function cf_x_settings_init() 
{
register_setting( 'cf_x_settings', 'cf_x_email_setting' );
add_settings_section( 'cf_x_email_section', __( 'Email Settings', 'cf-x' ), 'cf_x_email_section_callback', 'cf_x_settings' );
add_settings_field( 'cf_x_email_setting', __( 'Email Address', 'cf-x' ), 'cf_x_email_setting_callback', 'cf_x_settings', 'cf_x_email_section' );
}
add_action( 'admin_init', 'cf_x_settings_init' );

function cf_x_email_section_callback() 
{
  echo '<p>' . __( 'Enter the email address where you want to receive form submissions.', 'cf-x' ) . '</p>';
}

function cf_x_email_setting_callback() 
{
  $value = get_option( 'cf_x_email_setting' );  
  echo '<input type="email" name="cf_x_email_setting" value="' . esc_attr( $value ) . '">';
}


function wporg_add_custom_box() 
{
	$screens = [ 'contact-form-ddad' ];
	foreach ( $screens as $screen )
  {
		add_meta_box(
      'wporg_box_id',           // Unique ID
			'Form Builder',           // Box title
			'wporg_custom_box_html',  // Content callback, must be of type callable
			$screen                   // Post type
                );
	}
}
add_action( 'add_meta_boxes', 'wporg_add_custom_box' );


function wporg_custom_box_html( $post ) 
{
  $json = get_post_meta($post->ID, 'components', true );
  //echo $json;
?>

    <div id="builder"></div>
    <textarea id="components" name="components" style="display:none;" ></textarea>    
    <script src="https://cdn.form.io/formiojs/formio.full.min.js"></script>

<script>
Formio.builder(document.getElementById('builder'),
{
  components: <?php if($json){ echo $json; }else{ echo '[]'; }?>
}).then(function(builder) {
  builder.on('saveComponent', function()
  {
    let components = document.getElementById('components');
    components.value = JSON.stringify(builder.schema.components);
    console.log(components.value);
  });
});
   
</script>

<!-- Email to New Ant Design Code -->
<script>
// Get a reference to the Ant Design Select component
const { Select } = antd;

// Create an array of tag options
const tagOptions = [];
for (let i = 1; i < 1; i++) {
  tagOptions.push({
    value: i.toString + i,
    label: i.toString + i,
  });
}

// Create a function to handle tag changes
function handleChange(value) {
  console.log(`Selected: ${value}`);
  document.getElementById('emailtagselect_to_value').value = value.join(',');
}

// Retrieve the initial selected tags from the hidden input field
const initialTags = document.getElementById('emailtagselect_to_value').value.split(',');

// Render the Tags Select component
const selectComponent = React.createElement(
  Select,
  {
    mode: 'tags',
    style: { width: '100%' },
    placeholder: 'Enter Email',
    onChange: handleChange,
    options: tagOptions,
    defaultValue: initialTags,
  }
);

ReactDOM.render(
  selectComponent,
  document.getElementById('emailtagselect_to')
);

// Update the select component value when the publish or update button is clicked
document.getElementById('publish').addEventListener('click', function() {
  const selectedTags = document.getElementById('emailtagselect_to_value').value.split(',');
  const updatedSelectComponent = React.cloneElement(selectComponent, { defaultValue: selectedTags });
  ReactDOM.render(
    updatedSelectComponent,
    document.getElementById('emailtagselect_to')
  );
});

</script>
<!-- Email to New Ant Design Code -->



	<?php
}


// Add custom meta box to the 'contact-form-ddad' post type
function add_custom_meta_box() 
{
  add_meta_box(
    'contact_form_ddad_email_meta_box', 
    'Email Settings', 
    'render_email_meta_box', 
    'contact-form-ddad', 
    'side', 
    'low'
  );
}
add_action('add_meta_boxes', 'add_custom_meta_box');

function render_email_meta_box($post) 
{
  // Retrieve existing values from the database
  $to = get_post_meta($post->ID, 'email_to', true);
  $subject = get_post_meta($post->ID, 'email_subject', true);


    // Add nonce field
    wp_nonce_field('contact_form_ddad_email_meta_box', 'contact_form_ddad_email_meta_box_nonce');
  
    // Render Ant Design fields for To and Subject
    echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/antd@4.16.13/dist/antd.min.css">
    <script src="https://cdn.jsdelivr.net/npm/react@17/umd/react.development.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/react-dom@17/umd/react-dom.development.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/antd@4.16.13/dist/antd.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" crossorigin="anonymous">';

    echo '<div class="ant-form-item m-0 mt-3 mb-3">';
    echo '  <div class="ant-row ant-form-item-row">';
    echo '    <div class="ant-col ant-col-6 ant-form-item-label text-left">';
    echo '      <label class="" title="To">To</label>';
    echo '    </div>';
    echo '    <div class="ant-col ant-col-18 ant-form-item-control">';
    echo '      <div class="ant-form-item-control-input">';
    echo '        <div class="ant-form-item-control-input-content">';
    echo '          <div id="emailtagselect_to" name="email_to_ant" ></div><input  id="emailtagselect_to_value" class="ant-input ant-input-lg" type="hidden" name="email_to" value="' . esc_attr($to) . '" required placeholder="Enter to Email" />';
    echo '        </div>';
    echo '      </div>';
    echo '    </div>';
    echo '  </div>';
    echo '</div>';

    echo '<div class="ant-form-item m-0">';
    echo '  <div class="ant-row ant-form-item-row">';
    echo '    <div class="ant-col ant-col-6 ant-form-item-label text-left">';
    echo '      <label class="" title="Subject">Subject</label>';
    echo '    </div>';
    echo '    <div class="ant-col ant-col-18 ant-form-item-control">';
    echo '      <div class="ant-form-item-control-input">';
    echo '        <div class="ant-form-item-control-input-content">';
    echo '          <input class="ant-input ant-input-lg" type="text" name="email_subject" value="' . esc_attr($subject) . '" required placeholder="Enter Subject" />';
    echo '        </div>';
    echo '      </div>';
    echo '    </div>';
    echo '  </div>';
    echo '</div>';
  
}

// Save the custom meta box data
function save_email_meta_box_11($post_id) 
{
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
      return;

  // Verify the nonce before proceeding
  if (!isset($_POST['contact_form_ddad_email_meta_box_nonce']) || !wp_verify_nonce($_POST['contact_form_ddad_email_meta_box_nonce'], 'contact_form_ddad_email_meta_box'))
      return;
      print_r($_POST);
  // Check if user has permission to save data
  if (!current_user_can('edit_post', $post_id))
      return;

  $email_to = isset($_POST['email_to']) ? sanitize_text_field($_POST['email_to']) : '';
  update_post_meta($post_id, 'email_to', $email_to);

  $email_subject = isset($_POST['email_subject']) ? sanitize_text_field($_POST['email_subject']) : '';
  update_post_meta($post_id, 'email_subject', $email_subject);

}
add_action('save_post', 'save_email_meta_box_11');


function add_shortcode_metabox() 
{
  add_meta_box( 
    'shortcode_metabox', 
    'Form Shortcode', 
    'shortcode_metabox_callback', 
    'contact-form-ddad', 
    'side', 
    'low' 
  );
}
add_action( 'add_meta_boxes', 'add_shortcode_metabox' );
error_log(print_r($_POST, true));

function shortcode_metabox_callback( $post ) {
  $shortcode = my_custom_shortcode_function( $post->ID );
  echo '<p>Copy this shortcode and paste it into your post, page, or text widget content:</p>';
  //echo '<input class="shortcode" type="text" value="' . $shortcode . '" readonly>';

  echo '<div class="ant-form-item m-0 mt-3">';
  echo '  <div class="ant-row ant-form-item-row">';
  echo '    <div class="ant-col ant-col-24 ant-form-item-control">';
  echo '      <div class="ant-form-item-control-input">';
  echo '        <div class="ant-form-item-control-input-content">';
  echo '          <input id="shortcodeInput" style="text-align:center;" class="ant-input ant-input-lg shortcode" type="text" name="shortcode" value="' . $shortcode . '" required placeholder="Enter to Email" readonly />';
  echo '        </div>';
  echo '      </div>';
  echo '    </div>';
  echo '  </div>';
  echo '</div>';
  ?>
<style>
.ant-message 
{
  top: auto;
  bottom: 0;
}
.ant-message .anticon
{
   top: -3px;
}
.ant-message span 
{
  font-size:18px;
}
</style>


<script type="text/babel">
  const { Input } = antd;
  const { CopyToClipboard } = ReactCopyToClipboard;

  const { TextArea } = Input;

  const MyComponent = () => 
  {
    const handleCopy = () => {
      message.success('Copied to clipboard');
    };

    return (
      <div>
        <CopyToClipboard text="<?php echo esc_attr($shortcode); ?>" onCopy={handleCopy}>
          <Input className="shortcode" type="text" value="<?php echo esc_attr($shortcode); ?>" readOnly />
        </CopyToClipboard>
      </div>
    );
  };

  ReactDOM.render(
    <MyComponent />,
    document.getElementById('root')
  );
</script>

<script>
  const { message } = antd;

// Function to copy the shortcode to the clipboard
function copyShortcodeToClipboard() 
  {
    const shortcodeInput = document.getElementById('shortcodeInput');

    // Select the text inside the input field
    shortcodeInput.select();
    shortcodeInput.setSelectionRange(0, 99999); // For mobile devices

    // Copy the selected text to the clipboard
    document.execCommand('copy');

    // Show a success message
    message.success('Shortcode copied to clipboard!', 5);
  }

  // Add an event listener to the input field to trigger the copy function on click
  document.getElementById('shortcodeInput').addEventListener('click', copyShortcodeToClipboard);
</script>


  <?php

}

function my_custom_shortcode_function( $post_id ) 
{
  // Retrieve the data entered in the custom metabox
  $my_custom_data = get_post_meta( $post_id, 'wporg_custom_box_html', true );

  // Sanitize the custom data
  $my_custom_data = sanitize_text_field( $my_custom_data );

  // Generate the shortcode based on the custom data
  $shortcode = "[contact_form_ddad id= '$post_id ']";
  
  return $shortcode;
}