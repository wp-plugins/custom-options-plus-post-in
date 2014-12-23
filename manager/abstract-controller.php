<?php

if ( !class_exists( 'Coppi_Manager_Abstract_Controller' ) ) :

abstract class Coppi_Manager_Abstract_Controller
{

	protected $name;
	protected $do_screen_slug;
	protected $menu_title;

	protected $view_dir;
	protected $assets_url;

	protected $post_data_checked;
	
	protected $Inputed;
	protected $errors;



	public function __construct()
	{
		
		global $coppi;
		
		$this->view_dir   = $coppi->Plugin->dir_manager . trailingslashit( 'view' );
		$this->assets_url = $coppi->Plugin->url . trailingslashit( 'manager' ) . trailingslashit( 'assets' );

		$this->post_data_checked = false;
		
		$this->Inputed = new stdClass;
		$this->errors = new WP_Error();
		
		$this->init();
		
	}
	
	protected function init()
	{
		
		global $coppi;
		
		if( !$coppi->Env->is_admin or !$coppi->User->is_manager )
			return false;

		if( !$coppi->Env->is_ajax ) {
			
			if( $coppi->Site->is_multisite ) {
				
				add_action( 'network_admin_menu' , array( $this , 'network_admin_menu' ) );
					
			} else {
				
				add_action( 'admin_menu' , array( $this , 'admin_menu' ) );
					
			}
			
			add_action( 'admin_init' , array( $this , 'admin_init' ) );
			
		} else {
			
			$this->setup_ajax();
			
		}
		
	}
	
	public function network_admin_menu() {}

	public function admin_menu() {}
	
	public function admin_init()
	{

		global $plugin_page;
		global $coppi;
		
		if( empty( $this->do_screen_slug ) or $plugin_page != $this->do_screen_slug )
			return false;

		$this->set_inputed();
			
		$this->post_data();
			
		if( $coppi->Site->is_multisite ) {
					
			add_action( 'network_admin_notices' , array( $this , 'update_notice' ) );
			add_action( 'network_admin_notices' , array( $this , 'maybe_show_error' ) );
						
		} else {
				
			add_action( 'admin_notices' , array( $this , 'update_notice' ) );
			add_action( 'admin_notices' , array( $this , 'maybe_show_error' ) );
						
		}
	
		add_action( 'admin_print_scripts' , array( $this , 'admin_print_scripts' ) );
			
	}
	
	protected function set_inputed() {}
	
	protected function post_data()
	{
		
		global $coppi;
		
		if( empty( $_POST ) )
			return false;

		if( !$coppi->Helper->is_correctly_form( $_POST ) )
			return false;

		if( !$coppi->User->is_manager )
			return false;

		$this->post_data_checked = true;
		
	}
	
	public function update_notice() {}

	public function maybe_show_error()
	{
		
		if( empty( $this->errors ) )
			return false;

		$error_codes = $this->errors->get_error_codes();
		
		if( empty( $error_codes ) )
			return false;

		echo '<div class="error">';
			
		foreach ( $error_codes as $code ) {
					
			printf( '<p title="error_%1$s">%2$s</p>' , $code , $this->errors->get_error_message( $code ) );
					
		}
			
		echo '</div>';

	}
	
	public function admin_print_scripts()
	{
		
		global $coppi;
		
		wp_enqueue_style( 'thickbox' );
		wp_enqueue_style( 'wp-jquery-ui-dialog' );

		$include_files = array( 'jquery' , 'thickbox' , 'jquery-ui-dialog' );

		wp_enqueue_script( $coppi->Plugin->main_slug ,  $this->assets_url . 'js/manager.js', $include_files , date( 'Ymd' ) );
		wp_enqueue_style( $coppi->Plugin->main_slug ,  $this->assets_url . 'css/manager.css', array() , date( 'Ymd' ) );
		
	}
	
	protected function setup_ajax() {}

}

endif;
