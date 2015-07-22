<?php

class Hummingbird_Widget extends WP_Widget{

	private $text_domain = 'hummingbird';
	private $default_template_path = 'public/template/default.php';
	private $options_name = 'hummingbird_option_name';

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		parent::__construct(
			'hummingbird_widget',
			__( 'Hummingbird', 'hummingbird' ),
			array( 'description' => __(' List out various items from your Hummingbird profile', 'hummingbird' ) )
		);
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		extract( $args );
		$result = get_transient($widget_id);
		$requests_library = Requests::get_requests();
		if(!$result){
			$rest = new Rest();
			$options = get_option( $this->options_name );
			$expires = $options['hb_cache_timer'] * 60;
			$result = $rest->get( $instance['request'] , $options['hb_username'] );
			set_transient( $widget_id, $result, $expires );
		}

		if( $result['httpCode'] != $requests_library[ $instance['request'] ][ 'success_response' ] ){
			return false;
		}

		$json_feed = $result['json'];

		echo $before_widget;

		$template_path = $instance['template'];
		if( $template_path == $this->default_template_path ){
			include plugin_dir_path( dirname( __FILE__ ) ) . $template_path;
		}else{
			include $this->get_template_path_from_theme() . $template_path;
		}

		echo $after_widget;
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		$request = ! empty( $instance['request'] ) ? $instance['request'] : '';
		$requests = Requests::get_requests();
		$request_format = '
		<p>
			<label for="%s">%s</label>
			<select name="%s" id="%s" >
				%s
			</select>
		</p>
		';
		$request_options_format = '<option value="%s" %s>%s</option>';

		$options_string = '';
		foreach( $requests as $req){
			$options_string .= sprintf($request_options_format, $req['slug'], selected( $request, $req['slug'], false ), __( $req['name'], $this->text_domain ) );
		}

		printf($request_format,
			$this->get_field_id( 'request' ),
			__( 'Request: ', $this->text_domain),
			$this->get_field_name( 'request' ),
			$this->get_field_id( 'request' ),
			$options_string
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
			printf('<option value="%s" %s>%s</option>', $template_option['file'], selected( $template, $template_option['file'], false ), $template_option['name'] );
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
		$instance = array();
		$instance[ 'request' ] = $new_instance[ 'request' ];
		$instance[ 'template'] = $new_instance[ 'template' ];
		return $instance;
	}

	public function get_templates(){
		$templates = array(
			array( 
				'name' => __('Default', $this->text_domain), 
				'file' => $this->default_template_path)
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
					'file' => $file
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
		$hb_options = get_option( $this->options_name );
		$folder = isset( $hb_options['template_directory'] ) ? "/" . $hb_options['template_directory'] . "/" : '/hummingbird-templates/';
		return $folder;
	}

}

?>