<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    hummingbird
 * @subpackage hummingbird/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    hummingbird
 * @subpackage hummingbird/admin
 * @author     Simon McWhinnie <simon.mcwhinnie@gmail.com>
 */
class hummingbird_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $hummingbird    The ID of this plugin.
	 */
	private $hummingbird;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $hummingbird       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $hummingbird, $version ) {

		$this->plugin_name = $hummingbird;
		$this->version = $version;

		$this->option_group       = $this->plugin_name . '_option_group';
		$this->option_name        = $this->plugin_name . '_option_name';
		$this->setting_section_id = $this->plugin_name . '_setting_section_id';

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in hummingbird_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The hummingbird_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/hummingbird-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in hummingbird_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The hummingbird_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/hummingbird-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0
	 */
	public function add_plugin_admin_menu() {

		/*
		 * Add a settings page for this plugin to the Settings menu.
		*/
		add_options_page(
			__( 'MyAnimeList Options', $this->plugin_name ),
			__( 'MyAnimeList', $this->plugin_name ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'display_plugin_admin_page' )
		);

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0
	 */
	public function display_plugin_admin_page() {
		require_once( 'partials/hummingbird-admin-display.php' );
	}

	public function register_settings() {

		// Register a setting and its sanitization callback
		register_setting(
			$this->option_group, // Option group
			$this->option_name, // Option name
			array( $this, 'validate_options' ) //validation callback
		);

		//  Add a new section to a settings page.
		add_settings_section(
			$this->setting_section_id, // ID
			__( 'Settings', $this->plugin_name ), // Title
			array( $this, 'print_section_info' ), // Callback
			$this->plugin_name // Page
		);


		// Add the username option
		add_settings_field(
			'mal_username',
			__( 'MyAnimeList Username', $this->plugin_name ),
			array( $this, 'show_username_option' ),
			$this->plugin_name,
			$this->setting_section_id
		);

		// Add the cache time option
		add_settings_field(
			'mal_cache_timer',
			__( 'Cache expire (in minutes)', $this->plugin_name ),
			array( $this, 'show_cache_timer_option' ), 
			$this->plugin_name, 
			$this->setting_section_id
		);


	}

	/**
	 * Outputs the section info
	 */
	public function print_section_info() {
		_e( 'This page allows you to set all the options for the MyAnimeList plugin', $this->plugin_name );
	}

	/**
	 * Adds a show 404 checkbox option.
	 */
	public function show_username_option() {
		$options = get_option( $this->option_name );
		$value = isset( $options[ 'mal_username' ] ) ? $options[ 'mal_username' ] : '';
		printf(
			'<input type="text" id="mal_username" name="%s" value="%s" />',
			$this->option_name . "[mal_username]",
			$value
		);
	}

	public function show_cache_timer_option() {
		$options = get_option( $this->option_name );
		$value = isset( $options[ 'mal_cache_timer' ] ) ? $options[ 'mal_cache_timer' ] : '';
		printf(
			'<input type="text" id="mal_cache_timer" name="%s" value="%s" />',
			$this->option_name . "[mal_cache_timer]",
			$value
		);
	}

	public function validate_options( $options ) {
		foreach ($options as $key => $value) {
			switch ($key) {
				case 'mal_cache_timer':
					if( !is_numeric( $value ) ){
						$options[ $key ] = '';
					}
					break;
				
				default:
					break;
			}
		}
		return $options;
	}

	public function register_widget(){
		register_widget( 'MyAnimeList_Widget' );
	}
}
