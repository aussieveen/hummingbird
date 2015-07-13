<?php

class MyAnimeList_Widget extends WP_Widget{

	private $text_domain = 'hummingbird';
	private $default_template_path = 'public/template/default.php';
	private $options_name = 'hummingbird_option_name';

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		parent::__construct(
			'hummingbird_widget',
			__( 'MyAnimeList', 'hummingbird' ),
			array( 'description' => __(' List out various items from your MyAnimeList profile', 'hummingbird' ) )
		);
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		$rest = new Rest();
		$options = get_option( $this->options_name );
		$username = $options['mal_username'];
		if( $username == null ){
			printf('<p>Username not set. Please set in order to get information from MyAnimeList</p>');
			return;
		}

		$results = $rest->request($username, $instance['display']);

		$template_path = $instance['template'];
		if( $template_path == $this->default_template_path ){
			include plugin_dir_path( dirname( __FILE__ ) ) . $template_path;
		}else{
			include $this->get_template_path_from_theme() . $template_path;
		}
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		$display = ! empty( $instance['display'] ) ? $instance['display'] : 'all';
		$display_format = '
		<p>
			<label for = "%s">%s</label>
			<select name="%s" id="%s">
				<option value="all" %s>%s</option>
				<option value="anime" %s>%s</option>
				<option value="manga" %s>%s</option>
			</select>
		</p>';
		printf( $display_format, 
			$this->get_field_id( 'display' ), 
			__( 'Display: ', $this->text_domain ),
			$this->get_field_name( 'display' ), 
			$this->get_field_id( 'display' ),  
			selected( $display, 'all', false ),
			__( 'All', $this->text_domain ),
			selected( $display, 'anime', false ),
			__( 'Anime', $this->text_domain ),
			selected( $display, 'manga', false ),
			__( 'Manga', $this->text_domain )
		);

		$status = ! empty( $instance['status'] ) ? $instance['status'] : '0';
		$status_format = '
		<p>
			<label for = "%s">%s</label>
			<select name="%s" id="%s">
				<option value="0" %s>%s</option>
				<option value="2" %s>%s</option>
				<option value="1" %s>%s</option>
				<option value="6" %s>%s</option>
				<option value="3" %s>%s</option>
				<option value="4" %s>%s</option>
			</select>
		</p>';
		printf( $status_format, 
			$this->get_field_id( 'status' ), 
			__( 'Status: ', $this->text_domain ),
			$this->get_field_name( 'status' ), 
			$this->get_field_id( 'status' ),  
			selected( $status, '0', false ),
			__( 'All', $this->text_domain ),
			selected( $status, '2', false ),
			__( 'Completed', $this->text_domain ),
			selected( $status, '1', false ),
			__( 'Viewing/Reading', $this->text_domain ),
			selected( $status, '6', false ),
			__( 'Plan to watch/read', $this->text_domain ),
			selected( $status, '3', false ),
			__( 'On hold', $this->text_domain ),
			selected( $status, '4', false ),
			__( 'Dropped', $this->text_domain )
		);

		$templates = $this->get_templates();

		printf('<p><label for = "%s">%s</label><select name="%s" id="%s">',
			$this->get_field_id( 'template' ), 
			__( 'Template: ', $this->text_domain ),
			$this->get_field_name( 'template' ), 
			$this->get_field_id( 'template' )
		);

		$template = $instance['template'];
		foreach($templates as $template_option){
			var_dump($template);
			printf('<option value="%s" %s>%s</option>', $template_option['path'], selected( $template, $template_option['path'], false ), $template_option['name'] );
		}
		printf('</select></p>');
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		var_dump($new_instance);
		$instance = array();
		$instance[ 'display' ] = $new_instance[ 'display' ];
		$instance[ 'status' ] = $new_instance[ 'status' ];
		$instance[ 'template'] = $new_instance[ 'template' ];
		return $instance;
	}

	public function get_templates(){
		$templates = array(
			array( 
				'name' => __('Default', $this->text_domain), 
				'path' => $this->default_template_path)
		);
		$path = $this->get_template_path_from_theme();
		$files = array();
		if( file_exists( $path ) ){
			$files = scandir( $path );
		}
		foreach($files as $file){
			if( $file == '.' || $file == '..' ){
				continue;
			}
			$file_data = get_file_data( $path . $file, array( 'name' => 'Template Name' ) );
			if( isset( $file_data['name'] ) ){
				$templates[] = array( 
					'name' => $file_data['name'], 
					'path' => $this->get_theme_template_folder() . $file 
				);
			}
		}
		return $templates;
	}

	public function get_template_path_from_theme(){
		$theme_directory = get_template_directory();
		$folder = $this->get_theme_template_folder();
		return $theme_directory . $folder;
	}

	public function get_theme_template_folder(){
		$mal_options = get_option( $this->options_name );
		$folder = isset( $mal_options['template_directory'] ) ? "/" . $mal_options['template_directory'] . "/" : '/mal-templates/';
		return $folder;
	}

}

?>