<?php

// widgetized sidebar

if ( function_exists('register_sidebar') )
register_sidebar(array(
'before_widget' => '',
'after_widget' => '',
'before_title' => '<h2>',
'after_title' => '</h2>',
));

  
// Get page by slug function

function get_ID_by_slug($page_slug) {
    $page = get_page_by_path($page_slug);
    if ($page) {
        return $page->ID;
    } else {
        return null;
    }
}

function my_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'my_excerpt_length');


    $lang = TEMPLATE_PATH . '/lang';
    load_theme_textdomain('carkod', $lang);




// Get first image

function catch_that_image() {
  global $post, $posts;
  $first_img = '';
  ob_start();
  ob_end_clean();
  $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
  $first_img = $matches [1] [0];

  if(empty($first_img)){ //Defines a default image
    $first_img = "/images/default.jpg";
  }
  return $first_img;
}


// hook the translation filters, changes the names
add_filter(  'gettext',  'change_post_to_article'  );
add_filter(  'ngettext',  'change_post_to_article'  );

function change_post_to_article( $translated ) {
     $translated = str_ireplace(  'Post',  'Article',  $translated );  // ireplace is PHP5 only
     return $translated;
}


// Branding

// change URL for logo

function my_custom_login_logo()
{
    echo '<style  type="text/css"> h1 a {  background-image:url(' . get_bloginfo('template_directory') . '/images/logo.png)  !important; width: 100%; }  </style>';
}
add_action('login_head',  'my_custom_login_logo');

add_action('wp_dashboard_setup', 'wpc_dashboard_widgets');
function wpc_dashboard_widgets() {
	global $wp_meta_boxes;
	// Today widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
	// Last comments
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
	// Incoming links
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
	// Plugins
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
}


function wptutsplus_remove_dashboard_widgets() {
    $user = wp_get_current_user();
    if ( ! $user->has_cap( 'manage_options' ) ) {
        remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
        remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
        remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' );
    }
}
add_action( 'wp_dashboard_setup', 'wptutsplus_remove_dashboard_widgets' );

function remove_menus () {
global $menu;
		$restricted = array(__('Links'));
		end ($menu);
		while (prev($menu)){
			$value = explode(' ',$menu[key($menu)][0]);
			if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}
		}
}
add_action('admin_menu', 'remove_menus');

// Disable support for comments and trackbacks in post types
function df_disable_comments_post_types_support() {
	$post_types = get_post_types();
	foreach ($post_types as $post_type) {
		if(post_type_supports($post_type, 'comments')) {
			remove_post_type_support($post_type, 'comments');
			remove_post_type_support($post_type, 'trackbacks');
		}
	}
}
add_action('admin_init', 'df_disable_comments_post_types_support');

// Close comments on the front-end
function df_disable_comments_status() {
	return false;
}
add_filter('comments_open', 'df_disable_comments_status', 20, 2);
add_filter('pings_open', 'df_disable_comments_status', 20, 2);function df_disable_comments_hide_existing_comments($comments) {
	$comments = array();
	return $comments;
}
add_filter('comments_array', 'df_disable_comments_hide_existing_comments', 10, 2);

// Remove comments page in menu
function df_disable_comments_admin_menu() {
	remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'df_disable_comments_admin_menu');

// Redirect any user trying to access comments page
function df_disable_comments_admin_menu_redirect() {
	global $pagenow;
	if ($pagenow === 'edit-comments.php') {
		wp_redirect(admin_url()); exit;
	}
}
add_action('admin_init', 'df_disable_comments_admin_menu_redirect');

// Remove comments metabox from dashboard
function df_disable_comments_dashboard() {
	remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
}
add_action('admin_init', 'df_disable_comments_dashboard');

// Remove comments links from admin bar
function df_disable_comments_admin_bar() {
	if (is_admin_bar_showing()) {
		remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
	}
}
add_action('init', 'df_disable_comments_admin_bar');


// Textfield

$prefix = 'mb_';

$meta_boxes = array();

// second meta box
$meta_boxes[] = array(
	'id' => 'my-meta-box-2',
	'title' => '&uarr; Fotos &darr; Texto',
	'pages' => array('post', 'film', 'album'), // custom post types, since WordPress 3.0
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name' => 'Descripción',
			'id' => $prefix . 'descripcion',
			'type' => 'wysiwyg', // WYSIWYG editor
		),


	)
);

foreach ($meta_boxes as $meta_box) {
	$my_box = new RW_Meta_Box($meta_box);
}

// Validate value of meta fields

// Define ALL validation methods inside this class
// and use the names of these methods in the definition of meta boxes (key 'validate_func' of each field)

class RW_Meta_Box_Validate {
	function check_text($text) {
		if ($text != 'hello') {
			return false;
		}
		return true;
	}
}


/**
 * AJAX delete images on the fly. This script is a slightly altered version of a function used by the Plugin "Verve Meta Boxes"
 * http://wordpress.org/extend/plugins/verve-meta-boxes/
 *
 */
add_action('wp_ajax_unlink_file', 'unlink_file_callback');
function unlink_file_callback() {
	global $wpdb;
	if ($_POST['data']) {
		$data = explode('-', $_POST['data']);
		$att_id = $data[0];
		$post_id = $data[1];
		wp_delete_attachment($att_id);
	}
}

/**
 * Create meta boxes
 */
class RW_Meta_Box {

	protected $_meta_box;

	// create meta box based on given data
	function __construct($meta_box) {
		if (!is_admin()) return;

		$this->_meta_box = $meta_box;

		// fix upload bug: http://www.hashbangcode.com/blog/add-enctype-wordpress-post-and-page-forms-471.html
		$upload = false;
		foreach ($meta_box['fields'] as $field) {
			if ($field['type'] == 'file' || $field['type'] == 'image') {
				$upload = true;
				break;
			}
		}
		$current_page = substr(strrchr($_SERVER['PHP_SELF'], '/'), 1, -4);
		if ($upload && ($current_page == 'page' || $current_page == 'page-new' || $current_page == 'post' || $current_page == 'post-new')) {
			add_action('admin_head', array(&$this, 'add_post_enctype'));
			add_action('admin_head', array(&$this, 'add_unlink_script'));
			add_action('admin_head', array(&$this, 'add_clone_script'));
		}

		add_action('admin_menu', array(&$this, 'add'));

		add_action('save_post', array(&$this, 'save'));
	}

	function add_post_enctype() {
		echo '
		<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery("#post").attr("enctype", "multipart/form-data");
			jQuery("#post").attr("encoding", "multipart/form-data");
		});
		</script>';
	}

	function add_unlink_script(){
		echo '
		<script type="text/javascript">
		jQuery(document).ready(function($){
			$("a.deletefile").click(function () {
				var parent = jQuery(this).parent(),
					data = jQuery(this).attr("rel"),
					_wpnonce = $("input[name=\'_wpnonce\']").val();

				$.post(
					ajaxurl,
					{action: \'unlink_file\', _wpnonce: _wpnonce, data: data},
					function(response){
						//$("#info").html(response).fadeOut(3000);
						//alert(data.post);
					},
					"json"
				);
				parent.fadeOut("slow");
				return false;
			});
		});
		</script>';
	}

	function add_clone_script() {
		echo '
		<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery(".add").click(function() {
				jQuery("#newimages p:first-child").clone().insertAfter("#newimages p:last").show();
				return false;
			});
			jQuery(".remove").click(function() {
				jQuery(this).parent().remove();
			});
		});
		</script>';
	}


	/// Add meta box for multiple post types
	function add() {
		$this->_meta_box['context'] = empty($this->_meta_box['context']) ? 'normal' : $this->_meta_box['context'];
		$this->_meta_box['priority'] = empty($this->_meta_box['priority']) ? 'high' : $this->_meta_box['priority'];
		foreach ($this->_meta_box['pages'] as $page) {
			add_meta_box($this->_meta_box['id'], $this->_meta_box['title'], array(&$this, 'show'), $page, $this->_meta_box['context'], $this->_meta_box['priority']);
		}
	}

	// Callback function to show fields in meta box
	function show() {
		global $post;

		// Use nonce for verification
		echo '<input type="hidden" name="wp_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';

		echo '<table class="form-table">';

		foreach ($this->_meta_box['fields'] as $field) {
			// get current post meta data
			$meta = get_post_meta($post->ID, $field['id'], true);

			echo '<tr>',
					'<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
					'<td>';
			switch ($field['type']) {
				case 'text':
					echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" />',
						'<br />', $field['desc'];
					break;
				case 'textarea':
					echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="15" style="width:97%">', $meta ? $meta : $field['std'], '</textarea>',
						'<br />', $field['desc'];
					break;
				case 'select':
					echo '<select name="', $field['id'], '" id="', $field['id'], '">';
					foreach ($field['options'] as $option) {
						echo '<option value="', $option['value'], '"', $meta == $option['value'] ? ' selected="selected"' : '', '>', $option['name'], '</option>';
					}
					echo '</select>';
					break;
				case 'radio':
					foreach ($field['options'] as $option) {
						echo '<input type="radio" name="', $field['id'], '" value="', $option['value'], '"', $meta == $option['value'] ? ' checked="checked"' : '', ' />', $option['name'];
					}
					break;
				case 'checkbox':
					echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta ? ' checked="checked"' : '', ' />';
					break;
				case 'file':
					echo $meta ? "$meta<br />" : '', '<input type="file" name="', $field['id'], '" id="', $field['id'], '" />',
						'<br />', $field['desc'];
					break;
				case 'wysiwyg':
					echo '<textarea name="', $field['id'], '" id="', $field['id'], '" class="theEditor" cols="60" rows="4" style="width:97%">', $meta ? $meta : $field['std'], '</textarea>',
						'<br />', $field['desc'];
					break;
				case 'image':
					?>
					<h2>Images attached to this post</h2>
					<div id="uploaded">
						<?php
						$args = array(
							'post_type' => 'attachment',
							'post_parent' => $post->ID,
							'numberposts' => -1,
							'post_status' => NULL
						);
						$attachs = get_posts($args);
						if (!empty($attachs)) {
							foreach ($attachs as $att) {
							?>
								<div class="single-att" style="margin: 0 10px 10px 0; float: left;">
									<?php echo wp_get_attachment_image($att->ID, 'thumbnail'); ?>
									<br />
									<a class="deletefile" href="#" rel="<?php echo $att->ID ?>-<?php echo $post_id ?> "title="Delete this file">Delete Image</a>
								</div>
							<?php
							}
						} else {
							echo 'No Images uploaded yet';
						}
						?>
					</div>

					<h2>Upload new Images</h2>
                    <div id="newimages">
						<p><input type="file" name="<?php echo $field['id'] ?>[]" id="" /></p>
						<a class="add" href="#">Add More Images</a>
					</div>
					<?php
					break;
			}
			echo 	'<td>',
				'</tr>';
		}

		echo '</table>';
	}

	// Save data from meta box
	function save($post_id) {
		// verify nonce
		if (!wp_verify_nonce($_POST['wp_meta_box_nonce'], basename(__FILE__))) {
			return $post_id;
		}

		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}

		// check permissions
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id)) {
				return $post_id;
			}
		} elseif (!current_user_can('edit_post', $post_id)) {
			return $post_id;
		}

		foreach ($this->_meta_box['fields'] as $field) {
			$name = $field['id'];

			$old = get_post_meta($post_id, $name, true);
			$new = $_POST[$field['id']];

			/*
			// changed to using WP gallery
			if ($field['type'] == 'file' || $field['type'] == 'image') {
				$file = wp_handle_upload($_FILES[$name], array('test_form' => false));
				$new = $file['url'];
			}
			*/

			if ($field['type'] == 'file' || $field['type'] == 'image') {
				if (!empty($_FILES[$name])) {
					$this->fix_file_array($_FILES[$name]);
					foreach ($_FILES[$name] as $position => $fileitem) {
						$file = wp_handle_upload($fileitem, array('test_form' => false));
						$filename = $file['url'];
						if (!empty($filename)) {
							$wp_filetype = wp_check_filetype(basename($filename), null);
							$attachment = array(
								'post_mime_type' => $wp_filetype['type'],
								'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
								'post_status' => 'inherit'
							);
							$attach_id = wp_insert_attachment($attachment, $filename, $post_id);
							// you must first include the image.php file
							// for the function wp_generate_attachment_metadata() to work
							require_once(ABSPATH . 'wp-admin/includes/image.php');
							$attach_data = wp_generate_attachment_metadata($attach_id, $filename);
							wp_update_attachment_metadata($attach_id, $attach_data);
						}
					}
				}
			}

			if ($field['type'] == 'wysiwyg') {
				$new = wpautop($new);
			}

			if ($field['type'] == 'textarea') {
				$new = htmlspecialchars($new);
			}

			// validate meta value
			if (isset($field['validate_func'])) {
				$ok = call_user_func(array('RW_Meta_Box_Validate', $field['validate_func']), $new);
				if ($ok === false) { // pass away when meta value is invalid
					continue;
				}
			}

			if ($new && $new != $old) {
				update_post_meta($post_id, $name, $new);
			} elseif ('' == $new && $old && $field['type'] != 'file' && $field['type'] != 'image') {
				delete_post_meta($post_id, $name, $old);
			}
		}
	}

	/**
	 * Fixes the odd indexing of multiple file uploads from the format:
	 *
	 * $_FILES['field']['key']['index']
	 *
	 * To the more standard and appropriate:
	 *
	 * $_FILES['field']['index']['key']
	 *
	 * @param array $files
	 * @author Corey Ballou
	 * @link http://www.jqueryin.com
	 */
	function fix_file_array(&$files) {
		$names = array(
			'name' => 1,
			'type' => 1,
			'tmp_name' => 1,
			'error' => 1,
			'size' => 1
		);

		foreach ($files as $key => $part) {
			// only deal with valid keys and multiple files
			$key = (string) $key;
			if (isset($names[$key]) && is_array($part)) {
				foreach ($part as $position => $value) {
					$files[$position][$key] = $value;
				}
				// remove old key reference
				unset($files[$key]);
			}
		}
	}
}



// Customization of Tiny MCE

function myformatTinyMCE($in)
{
 $in['remove_linebreaks']=false;
 $in['gecko_spellcheck']=false;
 $in['keep_styles']=false;
 $in['accessibility_focus']=false;
 $in['tabfocus_elements']='major-publishing-actions';
 $in['media_strict']=false;
 $in['paste_remove_styles']=false;
 $in['paste_remove_spans']=false;
 $in['paste_strip_class_attributes']='none';
 $in['paste_text_use_dialog']=true;
 $in['wpeditimage_disable_captions']=true;
 $in['plugins']='inlinepopups,tabfocus,paste,media,fullscreen,wordpress,wpeditimage,wpgallery,wplink,wpdialogs,wpfullscreen';
 $in['content_css']=get_template_directory_uri() . "/editor-style.css";
 $in['wpautop']=true;
 $in['apply_source_formatting']=false;
 $in['theme_advanced_buttons1']=false;
 $in['theme_advanced_buttons2']='';
 $in['theme_advanced_buttons3']='';
 $in['theme_advanced_buttons4']='';
 return $in;
}
add_filter('tiny_mce_before_init', 'myformatTinyMCE' );

?>