<?php

if ( !class_exists( 'Coppi_Manager_Controller_Category' ) ) :

final class Coppi_Manager_Controller_Category extends Coppi_Manager_Abstract_Controller
{

	public function __construct()
	{
		
		global $coppi;
		
		$this->name = 'category';
		$this->do_screen_slug = $coppi->Plugin->main_slug;

		parent::__construct();
		
	}

	public function post_data()
	{
		
		global $coppi;

		parent::post_data();
		
		if( empty( $this->post_data_checked ) )
			return false;
		
		$Model = new Coppi_Categories_Model();
		
		if( !empty( $_POST[$coppi->Form->nonce . 'add_category'] ) ) {
			
			$nonce_key = $coppi->Form->nonce . 'add_category';

			if(	check_admin_referer( $nonce_key , $nonce_key ) ) {
				
				if( empty( $_POST['data']['add_cat'] ) )
					return false;

				$errors = $Model->add_datas( array( $_POST['data']['add_cat'] ) );
				$redirect = 'added_category';
				
			}
			
		} elseif( !empty( $_POST[$coppi->Form->nonce . 'update_category'] ) ) {
			
			$nonce_key = $coppi->Form->nonce . 'update_category';
			
			if(	check_admin_referer( $nonce_key , $nonce_key ) ) {
				
				if( empty( $_POST['data']['update_cat'] ) )
					return false;

				$errors = $Model->update_datas( array( $_POST['data']['update_cat'] ) );
				$redirect = 'update_category';

			}
			
		} elseif( !empty( $_POST[$coppi->Form->nonce . 'remove_category'] ) ) {
			
			$nonce_key = $coppi->Form->nonce . 'remove_category';
			
			if(	check_admin_referer( $nonce_key , $nonce_key ) ) {
				
				if( empty( $_POST['data']['remove_cat'] ) or empty( $_POST['data']['remove_cat']['remove_ids'] ) )
					return false;

				$errors = $Model->remove_datas( $_POST['data']['remove_cat'] );
				$redirect = 'remove_category';

			}
			
		}
		
		if( !isset( $errors ) )
			return false;
		
		$error_codes = $errors->get_error_codes();
		
		if( !empty( $error_codes ) ) {
			
			$this->errors = $errors;
			
		} else {
			
			wp_redirect( add_query_arg( array( $coppi->Other->msg_notice => $redirect ) , $coppi->Helper->get_action_link() ) );
			exit;

		}
		
	}
	
	public function update_notice()
	{
		
		global $coppi;
		
		if( empty( $_GET ) or empty( $_GET[$coppi->Other->msg_notice] ) )
			return false;
		
		$update_notice = strip_tags( $_GET[$coppi->Other->msg_notice] );
		
		if( $update_notice == 'added_category' or $update_notice == 'update_category' or $update_notice == 'remove_category' ) {

			printf( '<div class="updated"><p><strong>%s</strong></p></div>' , __( 'Settings saved.' ) );
			
		}

	}

	public static function get_data()
	{
		
		$Model = new Coppi_Categories_Model();
		$categories = $Model->get_datas();
		
		array_unshift( $categories , array( 'cat_id' => 0 , 'cat_name' => __( 'Uncategorized' ) ) );

		return $categories;

	}

}

endif;
