<?php

if ( !class_exists( 'Coppi_Manager_Controller_Not_Do' ) ) :

final class Coppi_Manager_Controller_Not_Do extends Coppi_Manager_Abstract_Controller
{

	public function __construct()
	{
		
		global $coppi;
		
		$this->name           = 'not_do_manager';
		$this->do_screen_slug = $coppi->Plugin->main_slug;
		$this->menu_title     = __( 'Custom Option' , $coppi->Plugin->ltd ) . '(' . $coppi->Plugin->ltd . ')';
		
		parent::__construct();
		
	}

	public function network_admin_menu()
	{
		
		global $coppi;
		
		add_menu_page( $coppi->name , $this->menu_title , $coppi->Plugin->capability , $this->do_screen_slug , array( $this , 'view' ) );

	}
	
	public function admin_menu()
	{
		
		global $coppi;
		
		add_options_page( $coppi->name , $this->menu_title , $coppi->Plugin->capability , $this->do_screen_slug , array( $this , 'view' ) );

	}
	
	public function view()
	{
		
		global $coppi;
		
		include_once( $this->view_dir . 'upgrade-announce.php' );

	}
	
	public function post_data()
	{
		
		global $coppi;

		parent::post_data();
		
		if( empty( $this->post_data_checked ) )
			return false;

		$nonce_key = $coppi->Form->nonce . 'db_upgrade';

		if( empty( $_POST[$nonce_key] ) )
			return false;

		if( !check_admin_referer( $nonce_key , $nonce_key ) )
			return false;

		if( empty( $_POST['upgrade_action'] ) )
			return false;

		$upgrade_action = strip_tags( $_POST['upgrade_action'] );
		
		$upgrader = new Coppi_Upgrader();

		if( $upgrade_action == 'install' ) {
			
			$upgrader->db_install();
			
		}
		
		wp_redirect( add_query_arg( array( $coppi->Other->msg_notice => 'upgrade_db' ) , $coppi->Helper->get_action_link() ) );
		exit;
			
	}

}

endif;
