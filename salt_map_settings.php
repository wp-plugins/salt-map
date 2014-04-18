<?php
class salt_map_settings {
	public function __construct() {
		if ( is_admin() ) {
			add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
			add_action( 'admin_init', array( $this, 'page_init' ) );
		}
	}

	public function add_plugin_page() {
		$page_hook_suffix = add_options_page( 'Settings Admin', __('Map Settings', "salt-map"), 'manage_options', 'test-setting-admin', array( $this, 'create_admin_page' ) );
		add_action('admin_print_scripts-' . $page_hook_suffix, array( $this, 'admin_scripts' ));
	}
	
	public function admin_scripts() {
		wp_enqueue_script( array('jquery', 'salt_map_admin_mustache', 'salt_map_admin_script') );
		wp_enqueue_media();
		wp_enqueue_style( array('salt_map_admin_field_style') );
	}
	
	public function create_admin_page() {
		echo '<div class="wrap">';
		screen_icon();
		echo '<h2>'. __('Map Settings', "salt-map") . '</h2>';
		echo '<p>' . __('Global settings for the SALT locations plugin.', "salt-map") . '</p>';
		echo '<form method="post" action="options.php">';
		settings_fields( 'salt_map_settings_group' );
		do_settings_sections( 'test-setting-admin' );
		submit_button();
		echo '</form>';
		echo '</div>';
	}

	public function page_init() {
		register_setting( 'salt_map_settings_group', 'salt_map_settings', array( $this, 'check_ID' ) );
		add_settings_section('setting_section_id', __('General', "salt-map"), array( $this, 'print_section_info' ), 'test-setting-admin' );
		add_settings_field('api_key', __('Google API key:', "salt-map"), array( $this, 'create_field' ), 'test-setting-admin','setting_section_id', array('id'=>'api_key'));
		add_settings_field('gridSize', __('Grid size:', "salt-map"), array( $this, 'create_field' ), 'test-setting-admin','setting_section_id', array('id'=>'gridSize'));
		add_settings_field('maxWidth', __('Info window max width:', "salt-map"), array( $this, 'create_field' ), 'test-setting-admin','setting_section_id', array('id'=>'maxWidth'));
		add_settings_field('zoom', __('Initial Zoom:', "salt-map"), array( $this, 'create_field' ), 'test-setting-admin','setting_section_id', array('id'=>'zoom'));
		add_settings_field('largeScreenLimit', __('Info window display type limit:', "salt-map"), array( $this, 'create_field' ), 'test-setting-admin','setting_section_id', array('id'=>'largeScreenLimit'));
		add_settings_field('lat', __('Initial Center Latitude:', "salt-map"), array( $this, 'create_field' ), 'test-setting-admin','setting_section_id', array('id'=>'lat'));
		add_settings_field('lng', __('Initial Center Longitude:', "salt-map"), array( $this, 'create_field' ), 'test-setting-admin','setting_section_id', array('id'=>'lng'));

		add_settings_field('locationIcon', __('Location Icon:', "salt-map"), array( $this, 'create_image_select' ), 'test-setting-admin','setting_section_id', array('id'=>'locationIcon'));
		add_settings_field('locationGroup1Icon', __('Location Group 1 Icon:', "salt-map"), array( $this, 'create_image_select' ), 'test-setting-admin','setting_section_id', array('id'=>'locationGroup1Icon'));
		add_settings_field('locationGroup2Icon', __('Location Group 2 Icon:', "salt-map"), array( $this, 'create_image_select' ), 'test-setting-admin','setting_section_id', array('id'=>'locationGroup2Icon'));
		add_settings_field('locationGroup3Icon', __('Location Group 3 Icon:', "salt-map"), array( $this, 'create_image_select' ), 'test-setting-admin','setting_section_id', array('id'=>'locationGroup3Icon'));

		add_settings_field('infoTemplate', __('Information Template:', "salt-map"), array( $this, 'create_textarea' ), 'test-setting-admin','setting_section_id', array('id'=>'infoTemplate'));
		add_settings_field('fieldsSettings', __('Location Fields:', "salt-map"), array( $this, 'create_fields_settings' ), 'test-setting-admin','setting_section_id', array('id'=>'fieldsSettings'));
		

		wp_register_script( 'salt_map_admin_script', plugins_url( '/scripts/salt_fields.js', __FILE__ ) );
		wp_register_script( 'salt_map_admin_mustache', plugins_url( '/scripts/mustache.js', __FILE__ ) );

		wp_register_style( 'salt_map_admin_field_style', plugins_url( '/styles/salt_fields.css', __FILE__ ) );
	}

	public function check_ID( $input ) {
		return $input;
	}

	public function print_section_info(){
		print __('Enter your setting below:', "salt-map");
	}

	public function create_field(array $args){
		$salt_map_settings = get_option( "salt_map_settings", null );
		echo '<input type="text" id="salt_map_settings_'.$args['id'].'" name="salt_map_settings['.$args['id'].']" value="'. $salt_map_settings[$args['id']] . '" />';
	}
	public function create_textarea(array $args){
		$salt_map_settings = get_option( "salt_map_settings", null );
		echo '<textarea rows="15" cols="70" id="salt_map_settings_'.$args['id'].'" name="salt_map_settings['.$args['id'].']">'. $salt_map_settings[$args['id']] . '</textarea>';
	}

	public function create_image_select(array $args){
		$salt_map_settings = get_option( "salt_map_settings", null );
		echo '<input type="text"  name="salt_map_settings['.$args['id'].']" id="'.$args['id'].'" value="'. $salt_map_settings[$args['id']] . '" size="40" />';
		echo '<input type="button" for="'.$args['id'].'" class="uploadButton"  value="Upload Image" />';
	}
	
	public function create_fields_settings(array $args){
		$salt_map_settings = get_option( "salt_map_settings", null );
		echo '<div class="fieldSettings">';
		echo '<input type="hidden" id="salt_map_settings_'.$args['id'].'" name="salt_map_settings['.$args['id'].']" value=\''. $salt_map_settings[$args['id']] . '\'/>';
		echo '<div id="field_data" >';
		echo '	<label class="fieldLabel">' . __('Name:', "salt-map") .  ' </label> <input class="field" type="text" id="name"/><br>';
		echo '	<label class="fieldLabel">' . __('Description:', "salt-map") . ' </label> <input class="field" type="text" id="description"/><br>';
		echo '	<label class="fieldLabel">' . __('Id:', "salt-map") . ' </label> <input class="field" type="text" id="id"/><br>';
		echo '	<div class="hide" id="options_field"><label class="fieldLabel">' . __('Options:', "salt-map") . ' </label> <input class="field" type="text" id="options"/><br></div>';
		echo '	<label class="fieldLabel">' . __('Default:', "salt-map") . ' </label> <input class="field" type="text" id="default"/><br>';
		echo '	<label class="fieldLabel">' . __('Type:', "salt-map") . ' </label>';
		echo '	<select class="fieldSelect" id="type">';
		echo '		<option value="text">' . __('Text', "salt-map") . '</option>';
		echo '		<option value="select">' . __('Select', "salt-map") . '</option>';
		echo '	</select><br>';
		echo '</div>';
		echo '<button class="fieldButton" id="add_button">' . __('Add', "salt-map") . '</button>';
		echo '<button class="fieldButton" id="clear_button">' . __('Clear', "salt-map") . '</button>';
		echo '</div>';
		echo '<div id="display_container">';
		echo '</div>';
		echo '<script type="text/template" id="field_template">';
		echo '<div class="field">';
		echo '	<h3>{{name}}</h3>';
		echo '	<p>{{description}}';
		echo '	<p>';
		echo '		<strong>' . __('Id:', "salt-map") . ' </strong>{{id}}<br>';
		echo '		<strong>' . __('Options:', "salt-map") . ' </strong>{{options}}<br>';
		echo '		<strong>' . __('Type:', "salt-map") . ' </strong>{{type}}<br>';
		echo '		<strong>' . __('Default:', "salt-map") . ' </strong>{{default}}<br>';
		echo '	</p>';
		echo '	<button id="{{id}}" class="remove_button">' . __('Remove', "salt-map") . '</button>';
		echo '</div>';
		echo '</script>';
		echo '<script type="text/javascript">';
		echo 'go();';
		echo '</script>';
	}
}
?>