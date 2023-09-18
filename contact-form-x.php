 <?php 
/*
 * Plugin Name:       Contact Form X
 * Plugin URI:        https://example.com/plugins/contact-form-x/
 * Description:       Just another contact form plugin. Simple but flexible.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            VMI
 * Author URI:        https://vibrantmediainc.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       contact-form-x
 */

if ( ! defined( 'ABSPATH' ) ) 
{
    exit; // Exit if accessed directly
}


function generate_form_contact_form_x($post_id) 
{
    $json =  get_post_meta($post_id, 'components', true );
    $array = json_decode($json, true);
?>

    <div ng-app="myApp" ng-controller="formController" >

    <!-- React Liberary -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/antd@4.16.13/dist/antd.min.css">
    <script src="https://cdn.jsdelivr.net/npm/react@17/umd/react.development.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/react-dom@17/umd/react-dom.development.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/antd@4.16.13/dist/antd.min.js"></script>
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
          font-size:16px;
      }
    </style>
    <script type="text/babel">
    const { message } = antd;

    const App = () => {
      const [messageApi, contextHolder] = message.useMessage();
      const key = 'updatable';
    };
    ReactDOM.render(<App />, document.getElementById('success-message'));
    </script>

    <!-- React Liberary -->


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.form.io/formiojs/formio.form.min.css" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.form.io/formiojs/formio.form.min.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" crossorigin="anonymous">
    <script>Formio.createForm(document.getElementById('formio'), {
  components: [
  	<?php //echo $json; ?>
  ]
});
</script>

        <div id="formio" style="padding: 50px;"></div>
        <input type="hidden" id="contact-form-x-id" value="<?php echo $post_id; ?>"/>
        <div id="success-message"></div>

<script>
  // Add this line to import the Ant Design message component
  const { message } = antd;

  Formio.icons = 'fontawesome';
  Formio.createForm(document.getElementById('formio'), {
    components: <?php print_r($json); ?>
  }).then(function(form) {
    var submitButton = form.getComponent('submit');
    var originalButtonText = submitButton.data.buttonContent;

    form.on('submit', function(submission) {
   
      console.log(submission);
      submitButton.loading = true; // Show the loader on the submit button
      submitButton.data.buttonContent = 'Submitting...';
      message.loading('Sending Form...', 5);


      // Send the form data to the server using AJAX
      jQuery.ajax({
        url: '<?php echo admin_url('admin-ajax.php'); ?>',
        type: 'POST',
        dataType: 'html',
        data: {
          action: 'contact_form_x_submit_btn',
          form_data: JSON.stringify(submission.data),
          post_id: jQuery('#contact-form-x-id').val(),
        },
        success: function(response) {
          // Handle success
          console.log('Form submitted successfully');
          message.success('Form Submitted Successfully', 2);
          form.emit('formio.submissionSuccess', submission); // Emit the success event
        },
        error: function(xhr, status, error) {
          // Handle error
          message.error('Error Sending Form', 2);
          console.error('Form Submission Failed');
          form.emit('formio.submissionError', submission); // Emit the error event
        },
        complete: function() {
          submitButton.loading = false; // Hide the loader on the submit button
          submitButton.data.buttonContent = originalButtonText;
        },
      });
    });
  });
</script>


    </div>


<script>
    var formData = <?php print_r($json);?>;

createForm(formData);
function createForm(arr)
{
    var $formTmp = $('<form></form>');
    arr.forEach( function(obj, idx) 
{
    var $fieldSet,
    $selctOpts = $('<select name=""></select>'),
    inputType = obj.type;

        switch (inputType)
      {
            case 'text':
                $fieldSet = $('<label for="">'+obj.label+'</label>');

                if ( obj.req === 1) {
                    $fieldSet.append('<input type="text" required>');
                } else {
                    $fieldSet.append('<input type="text">');
                }
                $formTmp.append($fieldSet, '<br />');
                break;
            case 'textarea':
                $fieldSet = $('<label for="">'+obj.label+'</label><br /><textarea rows="4" cols="50"></textarea>');
                $formTmp.append($fieldSet, '<br />');
                break;
            case 'select':
                $fieldSet = $('<label for=""></label>').text(obj.label);
            addOptions($selctOpts, obj.choices);
                $formTmp.append($fieldSet, $selctOpts, '<br />');
                break;
            default:
                //alert('There was no input type found.');
                break;
        }
});

    // render to body.
    $('body').append($formTmp);

    // Loop for the select options.
    function addOptions(elem, arr)
    {
        arr.forEach(function(obj){elem.append('<option value="'+obj.sec+'">'+obj.label+'</option>');});
    }
}
    </script>


    <?php
    $html = '<form><div class="fields">';
    
    foreach ($array as $field) 
    {
      $type = $field['type'];
      $label = $field['label'];
      $key = $field['key'];
      $input = '';
  
      switch ($type) 
      {
        case 'textfield':
          $input = '<input type="text" name="' . $key . '">';
          break;
          
        case 'textarea':
          $input = '<textarea name="' . $key . '"></textarea>';
          break;
          
        case 'number':
          $input = '<input type="number" name="' . $key . '">';
          break;
          
        case 'password':
          $input = '<input type="password" name="' . $key . '">';
          break;
          
        case 'day':
          $input = '<input type="date" name="' . $key . '">';
          break;
          
        case 'time':
          $input = '<input type="time" name="' . $key . '">';
          break;
          
        case 'checkbox':
          $input = '<input type="checkbox" name="' . $key . '">';
          break;
          
        case 'selectboxes':
          $values = $field['values'];
          $input = '';
          foreach ($values as $value) 
          {
            $input .= '<input type="checkbox" name="' . $key . '[]" value="' . $value['value'] . '">' . $value['label'] . '<br>';
          }
          break;
          
        case 'datetime':
          $input = '<input type="datetime-local" name="' . $key . '">';
          break;
          
        case 'phoneNumber':
          $input = '<input type="tel" name="' . $key . '">';
          break;
          
        case 'select':
          $values = $field['data']['values'];
          $input = '<select name="' . $key . '">';
          foreach ($values as $value) 
          {
            $input .= '<option value="' . $value['value'] . '">' . $value['label'] . '</option>';
          }
          $input .= '</select>';
          break;
          
        case 'url':
          $input = '<input type="url" name="' . $key . '">';
          break;
          
        case 'radio':
          $values = $field['values'];
          $input = '';
          foreach ($values as $value) 
          {
            $input .= '<input type="radio" name="' . $key . '" value="' . $value['value'] . '">' . $value['label'] . '<br>';
          }
          break;
          
        case 'email':
          $input = '<input type="email" name="' . $key . '">';
          break;
          
        case 'button':
          $input = '<input type="submit" name="' . $key . '" value="' . $label . '">';
          break;
          
        default:
          $input = '';
          break;
      }
      $html .= '<div class="field">';
      if($type != 'button')
      {
        $html .= '<label>' . $label . '</label>';
      }
      
      $html .= $input . '</div>';
    }
    
    $html .= '</div></form>';
    
    return $html;
  }
function contact_form_x($atts) 
{
    $default = array(
        'id' => 1,
    );
    $a = shortcode_atts($default, $atts);
    $post_id =  $a['id'];
    $form_html = generate_form_contact_form_x($post_id);
    return $form_html;
}
add_shortcode('contact_form_x', 'contact_form_x');

define('PLUGINS_URL',plugin_dir_path( __FILE__ ));
include( PLUGINS_URL.'admin/custom-post-type/post-type.php');
include( PLUGINS_URL.'admin/meta-box/meta-box.php');
include( PLUGINS_URL.'function.php');