<?php
include_once('salt_map_setting.php');

class salt_map_locations {
	public function __construct() {
		if ( is_admin() ){
			add_action('save_post', array( $this, 'save_data' ));
			add_action( 'init', array( $this, 'create_post_type' ));

		}
	}

	public function add_box() {
		global $meta_box;
		foreach($meta_box as $post_type => $value) {
			add_meta_box($value['id'], $value['title'], array( $this, 'format_box' ), $post_type, $value['context'], $value['priority']);
		}
	}

	public function create_post_type(){
		global $meta_box;
		$salt_map_setting = new salt_map_setting(null);
		register_post_type( 'salt_map_location',
		array(
			'labels' => array(
				'name' => __( 'Locations', "salt-map" ),
				'singular_name' => __( 'Location', "salt-map")
		),
		'public' => true,
		'has_archive' => true,
		)
		);
		add_action('admin_menu', array( $this, 'add_box' ));


		 
		$meta_box['salt_map_location'] = array(
  'id' => 'salt_map_location-meta',  
  'title' => 'Location information',    
  'context' => 'normal',    
  'priority' => 'high',
  'fields' => array_merge(array( array('name' => __('Latitude', "salt-map"),
                     'description' => __('Latitude for the location.', "salt-map"),
                     'id' => 'salt_map_lat',
                     'type' => 'text',
                     'default' => ''
                     ),
                     array(
                     'name' => __('Longitude', "salt-map"),
                     'description' => __('Longitude for the location.', "salt-map"),
                     'id' => 'salt_map_lng',
                     'type' => 'text',
                     'default' => '' )), $salt_map_setting->fieldsSettings)
                     );
	}

	public function save_data($post_id) {
		global $meta_box,  $post;
			
		if (!wp_verify_nonce($_POST['plib_meta_box_nonce'], basename(__FILE__))) {
			return $post_id;
		}

		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}

		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id)) {
				return $post_id;
			}
		} else if (!current_user_can('edit_post', $post_id)) {
			return $post_id;
		}
			
		foreach ($meta_box[$post->post_type]['fields'] as $field) {
			$old = get_post_meta($post_id, $field['id'], true);
			$new = $_POST[$field['id']];

			if ($new && $new != $old) {
				update_post_meta($post_id, $field['id'], $new);
			} elseif ('' == $new && $old) {
				delete_post_meta($post_id, $field['id'], $old);
			}
		}
	}

	function format_box() {
		global $meta_box, $post;

		echo '<input type="hidden" name="plib_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';

		echo '<table class="form-table">';
		foreach ($meta_box[$post->post_type]['fields'] as $field) {
			$meta = get_post_meta($post->ID, $field['id'], true);

			echo '<tr>'.
              '<th style="width:20%"><label for="'. $field['id'] .'">'. $field['name']. '</label></th>'.
              '<td>';
			switch ($field['type']) {
				case 'text':
					echo '<input type="text" name="'. $field['id']. '" id="'. $field['id'] .'" value="'. ($meta ? $meta : $field['default']) . '" size="30" style="width:97%" />'. '<br />'. $field['description'];
					break;
				case 'textarea':
					echo '<textarea name="'. $field['id']. '" id="'. $field['id']. '" cols="60" rows="4" style="width:97%">'. ($meta ? $meta : $field['default']) . '</textarea>'. '<br />'. $field['description'];
					break;
				case 'select':
					echo '<select name="'. $field['id'] . '" id="'. $field['id'] . '">';
					foreach ($field['options'] as $option) {
						echo '<option '. ( $meta == $option ? ' selected="selected"' : '' ) . '>'. $option . '</option>';
					}
					echo '</select>'.'<br />'. $field['description'];
					break;
				case 'radio':
					foreach ($field['options'] as $option) {
						echo '<input type="radio" name="' . $field['id'] . '" value="' . $option['value'] . '"' . ( $meta == $option['value'] ? ' checked="checked"' : '' ) . ' />' . $option['name'];
					}
					break;
				case 'checkbox':
					echo '<input type="checkbox" name="' . $field['id'] . '" id="' . $field['id'] . '"' . ( $meta ? ' checked="checked"' : '' ) . ' />';
					break;
			}
			echo     '<td>'.'</tr>';
		}
		echo '</table>';
	}
}
?>