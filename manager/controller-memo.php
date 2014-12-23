<?php

if ( !class_exists( 'Coppi_Manager_Controller_Memo' ) ) :

final class Coppi_Manager_Controller_Memo extends Coppi_Manager_Abstract_Controller
{

	public function __construct()
	{
		
		global $coppi;
		
		$this->name           = 'memo';
		$this->do_screen_slug = $coppi->Plugin->main_slug;

		parent::__construct();
		
	}

	public function post_data()
	{
		
		global $coppi;

		parent::post_data();
		
		if( empty( $this->post_data_checked ) )
			return false;
		
		if( empty( $_POST[$coppi->Form->nonce . 'update_memo'] ) )
			return false;

		if( !check_admin_referer( $coppi->Form->nonce . 'update_memo' , $coppi->Form->nonce . 'update_memo' ) )
			return false;

		if( empty( $_POST['data'] ) )
			return false;

		if( empty( $_POST['update_field'] ) )
			return false;

		$update_field = strip_tags( $_POST['update_field'] );
		
		if( $update_field == 'update_memo' ) {
			
			$Model = new Coppi_Memo_Model();
			
			$Model->update_data( $_POST['data'] );

		}
		
		wp_redirect( add_query_arg( array( $coppi->Other->msg_notice => 'update_memo' ) , $coppi->Helper->get_action_link() ) );
		exit;
			
	}
	
	public function update_notice()
	{
		
		global $coppi;
		
		if( empty( $_GET ) or empty( $_GET[$coppi->Other->msg_notice] ) )
			return false;
		
		$update_notice = strip_tags( $_GET[$coppi->Other->msg_notice] );
		
		if( $update_notice == 'update_memo' )
			printf( '<div class="updated"><p><strong>%s</strong></p></div>' , __( 'Settings saved.' ) );

	}

	public static function get_data()
	{
		
		$Model = new Coppi_Memo_Model();

		return $Model->get_data();

	}

}

endif;
