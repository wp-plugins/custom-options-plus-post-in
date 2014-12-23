<?php

if ( !class_exists( 'Coppi_Manager_Controller_Custom_Option' ) ) :

final class Coppi_Manager_Controller_Custom_Option extends Coppi_Manager_Abstract_Controller
{

	public function __construct()
	{
		
		global $coppi;
		
		$this->name           = 'option';
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
		
		include_once( $this->view_dir . 'custom-option.php' );

	}
	
	protected function set_inputed()
	{
		
		$this->Inputed->add_option = new stdClass;
		
		$this->Inputed->add_option->option_name  = false;
		$this->Inputed->add_option->option_value = false;
		$this->Inputed->add_option->cat_id       = false;

		$this->Inputed->add_cat           = new stdClass;
		$this->Inputed->add_cat->cat_name = false;

		$this->Inputed->list_options          = new stdClass;
		$this->Inputed->list_options->orderby = 'create_date';
		$this->Inputed->list_options->order   = 'asc';

		if( !empty( $_POST ) && !empty( $_POST['data'] ) ) {

			$post_data = $_POST['data'];
			
			if( !empty( $post_data['add_option']['option_name'] ) )
				$this->Inputed->add_option->option_name = strip_tags( $post_data['add_option']['option_name'] );
	
			if( !empty( $post_data['add_option']['option_value'] ) )
				$this->Inputed->add_option->option_value = $post_data['add_option']['option_value'];
	
			if( !empty( $post_data['add_option']['cat_id'] ) )
				$this->Inputed->add_option->cat_id = intval( $post_data['add_option']['cat_id'] );
	
			if( !empty( $post_data['add_cat']['cat_name'] ) )
				$this->Inputed->add_cat->cat_name = strip_tags( $post_data['add_cat']['cat_name'] );
				
		} elseif( !empty( $_GET ) ) {
			
			if( !empty( $_GET['orderby'] ) )
				$this->Inputed->list_options->orderby = strip_tags( $_GET['orderby'] );
	
			if( !empty( $_GET['order'] ) )
				$this->Inputed->list_options->order = strip_tags( $_GET['order'] );
	
		}
	
	}
	
	protected function post_data()
	{
		
		global $coppi;

		parent::post_data();
		
		if( empty( $this->post_data_checked ) )
			return false;
		
		$Model = new Coppi_Custom_Option_Model();
		
		if( !empty( $_POST[$coppi->Form->nonce . 'add_option'] ) ) {
			
			$nonce_key = $coppi->Form->nonce . 'add_option';

			if(	check_admin_referer( $nonce_key , $nonce_key ) ) {
				
				if( empty( $_POST['data']['add_option'] ) )
					return false;

				$errors = $Model->add_datas( array( $_POST['data']['add_option'] ) );
				$redirect = 'added_option';
				
			}
			
		} elseif( !empty( $_POST[$coppi->Form->nonce . 'bulk_update'] ) ) {
			
			$nonce_key = $coppi->Form->nonce . 'bulk_update';

			if(	check_admin_referer( $nonce_key , $nonce_key ) ) {
				
				if( empty( $_POST['data']['bulk_update']['bulkaction'] ) or empty( $_POST['data']['bulk_update']['ids'] ) )
					return false;
				
				$errors = $this->bulk_update( $_POST['data']['bulk_update'] );
				$redirect = 'bulk_update';
				
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
		
		if( $update_notice == 'added_option' or $update_notice == 'bulk_update' ) {

			printf( '<div class="updated"><p><strong>%s</strong></p></div>' , __( 'Settings saved.' ) );
			
		}

	}

	public function get_data()
	{
		
		$Model = new Coppi_Custom_Option_Model();
		$ModelCats = new Coppi_Categories_Model();

		$format_options = array();

		$get_options = $Model->get_datas( $this->Inputed->list_options->orderby , $this->Inputed->list_options->order );
		$cat_ids = $ModelCats->get_ids();
		
		if( !empty( $cat_ids ) ) {
			
			foreach( $cat_ids as $cat_id ) {
				
				$format_options[$cat_id] = array();
				
			}
			
		}
		
		if( !empty( $get_options ) ) {
			
			foreach( $get_options as $key => $option ) {
				
				if( empty( $option['cat_id'] ) )
					$option['cat_id'] = 0;
				
				if( !in_array( $option['cat_id'] , $cat_ids ) )
					$option['cat_id'] = 0;
				
				$format_options[$option['cat_id']][] = $option;

			}

		}
		
		return $format_options;

	}

	private function bulk_update( $bulk_datas )
	{
		
		global $coppi;

		$errors = new WP_Error();
		$Model = new Coppi_Custom_Option_Model();
		
		$ids = array();
		$bulkaction = strip_tags( $bulk_datas['bulkaction'] );
		$cat_from = intval( $bulk_datas['cat_from'] );
		$cat_to = intval( $bulk_datas['cat_to'] );
		
		foreach( $bulk_datas['ids'] as $option_id ) {
			
			$ids[] = $option_id;
			
		}
		
		if( $bulkaction == 'change_cat' ) {
			
			$get_data = $this->get_data();
			$data = array();

			if( !empty( $get_data[$cat_from] ) ) {
				
				foreach( $get_data[$cat_from] as $option ) {
					
					if( in_array( $option['option_id'] , $ids ) ) {

						$option['cat_id'] = $cat_to;
						$data[] = $option;
						
					}
					
				}
				
			}
			
			if( !empty( $data ) ) {
				
				$errors = $Model->update_datas( $data );

			}
			
		} elseif( $bulkaction == 'removes' ) {
			
			$data['remove_ids'] = $ids;
	
			$errors = $Model->remove_datas( $data );

		}
		
		return $errors;
		
	}


	protected function setup_ajax()
	{
		
		global $coppi;

		add_action( 'wp_ajax_' . $coppi->Plugin->ltd . '_remove_do' , array( $this , 'ajax_remove_do' ) );
		add_action( 'wp_ajax_' . $coppi->Plugin->ltd . '_update_do' , array( $this , 'ajax_update_do' ) );

	}
	
	public function ajax_remove_do()
	{
		
		global $coppi;

		if( empty( $_POST ) or empty( $_POST['data'] ) )
			return false;

		$nonce_key = $coppi->Form->nonce . 'remove_do';

		if( empty( $_POST[$nonce_key] ) )
			return false;
		
		if(	!check_ajax_referer( $nonce_key , $nonce_key ) )
			return false;

		if( empty( $_POST['data']['option_id'] ) )
			return false;
		
		$option_id = intval( $_POST['data']['option_id'] );
		
		if( empty( $option_id ) )
			return false;
		
		$data['remove_ids'] = array( $option_id );

		$Model = new Coppi_Custom_Option_Model();

		$errors = $Model->remove_datas( $data );
		$error_codes = $errors->get_error_codes();
		
		if( !empty( $error_codes ) ) {
			
			$return_errors = array();

			foreach ( $error_codes as $code ) {
				
				$return_errors[$code] = $errors->get_error_message( $code );
				
			}
			
			wp_send_json_error( array( 'errors' => $return_errors ) );
			
		} else {
			
			wp_send_json_success();
			
		}
		
		die();
		
	}

	public function ajax_update_do()
	{
		
		global $coppi;

		if( empty( $_POST ) or empty( $_POST['data'] ) )
			return false;

		$nonce_key = $coppi->Form->nonce . 'update_do';

		if( empty( $_POST[$nonce_key] ) )
			return false;
		
		if(	!check_ajax_referer( $nonce_key , $nonce_key ) )
			return false;
		
		if( empty( $_POST['data']['option_id'] ) )
			return false;
		
		$option_id = intval( $_POST['data']['option_id'] );
		
		if( empty( $option_id ) )
			return false;
		
		$option_name = false;
		$option_value = false;
		$cat_id = 0;

		if( !empty( $_POST['data']['option_name'] ) )
			$option_name = strip_tags( $_POST['data']['option_name'] );
		
		if( !empty( $_POST['data']['option_value'] ) )
			$option_value = $_POST['data']['option_value'];
		
		if( !empty( $_POST['data']['cat_id'] ) )
			$cat_id = intval( $_POST['data']['cat_id'] );

		$data = array();
		$data[] = array( 'option_id' => $option_id , 'option_name' => $option_name , 'option_value' => $option_value , 'cat_id' => $cat_id );

		$Model = new Coppi_Custom_Option_Model();

		$errors = $Model->update_datas( $data );
		$error_codes = $errors->get_error_codes();
		
		if( !empty( $error_codes ) ) {
			
			$return_errors = array();

			foreach ( $error_codes as $code ) {
				
				$return_errors[$code] = $errors->get_error_message( $code );
				
			}
			
			wp_send_json_error( array( 'errors' => $return_errors ) );
			
		} else {
			
			wp_send_json_success();
			
		}
		
		die();
		
	}
	
}

endif;
