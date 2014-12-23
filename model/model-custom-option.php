<?php

if ( !class_exists( 'Coppi_Custom_Option_Model' ) ) :

final class Coppi_Custom_Option_Model extends Coppi_Model_Abstract_Table
{

	private $orderby = 'create_date';
	private $order = 'asc';

	protected function init()
	{
		
		global $wpdb;
		global $coppi;

		if( $coppi->Site->is_multisite ) {

			$this->table = $this->prefix_network . $coppi->Tables->option;
			
		} else {

			$this->table = $this->prefix . $coppi->Tables->option;
			
		}
		
		$this->default_items = false;
		
	}

	public function get_datas( $request_orderby = false , $request_order = false )
	{
		
		global $wpdb;
		global $coppi;

		$data = array();
		
		$orderby = $this->orderby;
		$order = $this->order;

		if( !empty( $request_orderby ) )
			$orderby = strip_tags( $request_orderby );

		if( !empty( $request_order ) )
			$order = strip_tags( $request_order );

		$cache_key = 'option_get_datas_' . $orderby . '_' . $order;
		
		$cache = wp_cache_get( $cache_key , $coppi->Plugin->ltd );
		
		if( $cache !== false ) {
			
			$data = $cache;
			
		} else {

			$sql = "SELECT * FROM `$this->table`";
			$sql .= $wpdb->prepare( ' ORDER BY %1$s %2$s' , $orderby , strtoupper( $order ) );
	
			$get_datas = $wpdb->get_results( $sql );
	
			if( !empty( $get_datas ) ) {
				
				foreach( $get_datas as $get_data ) {
					
					$data[] = $this->data_format( $get_data );
					
				}
				
			}
			
			wp_cache_set( $cache_key , $data , $coppi->Plugin->ltd );

		}

		return $data;

	}
	
	public function add_datas( $post_datas = array() )
	{
		
		global $wpdb;
		global $coppi;

		$errors = new WP_Error();

		if( empty( $post_datas ) ) {

			$errors->add( 'empty_data' , __( 'Post data is empty.' , $coppi->Plugin->ltd ) );
			return $errors;

		}
		
		$create_date = current_time( 'mysql' );
		
		foreach( $post_datas as $key => $data ) {
			
			$data['option_id'] = 0;
			$data['create_date'] = $create_date;
			
			$data = $this->data_format( $data );

			$validate_error = $this->validate_data( $data );
			$error_codes = $validate_error->get_error_codes();

			if( !empty( $error_codes ) )
				return $validate_error;
			
			$post_datas[$key] = $data;
			
		}

		foreach( $post_datas as $key => $data ) {

			$wpdb->insert( $this->table , $data );
			
		}
		
		return $errors;

	}

	public function update_datas( $post_datas = array() )
	{
		
		global $wpdb;
		global $coppi;

		$errors = new WP_Error();

		if( empty( $post_datas ) ) {

			$errors->add( 'empty_data' , __( 'Post data is empty.' , $coppi->Plugin->ltd ) );
			return $errors;

		}
		
		$create_date = current_time( 'mysql' );

		foreach( $post_datas as $key => $data ) {
			
			$data['create_date'] = $create_date;

			$data = $this->data_format( $data );

			$validate_error = $this->validate_data( $data );
			$error_codes = $validate_error->get_error_codes();

			if( !empty( $error_codes ) )
				return $validate_error;
			
			$post_datas[$key] = $data;
			
		}

		foreach( $post_datas as $key => $data ) {

			$option_id = $data['option_id'];

			unset( $data['option_id'] , $data['create_date'] );

			$wpdb->update( $this->table , $data , array( 'option_id' => $option_id ) );
			
		}
		
		return $errors;

	}

	public function remove_datas( $post_datas = array() )
	{
		
		global $wpdb;
		global $coppi;

		$errors = new WP_Error();

		if( empty( $post_datas ) or empty( $post_datas['remove_ids'] ) ) {

			$errors->add( 'empty_data' , __( 'Post data is empty.' , $coppi->Plugin->ltd ) );
			return $errors;

		}
		
		foreach( $post_datas['remove_ids'] as $key => $option_id ) {

			$option_id = intval( $option_id );
			
			if( !empty( $option_id ) )
				$wpdb->delete( $this->table , array( 'option_id' => $option_id ) );
			
		}
		
		return $errors;

	}

	public function data_format( $data )
	{
		
		if( is_object( $data ) )
			$data = (array) $data;

		$data['option_id'] = intval( $data['option_id'] );
		$data['option_name'] = strip_tags( $data['option_name'] );
		$data['option_value'] = $data['option_value'];
		$data['cat_id'] = intval( $data['cat_id'] );
		$data['create_date'] = strip_tags( $data['create_date'] );
		
		return $data;
		
	}

	public function validate_data( $data )
	{
		
		global $coppi;
		
		$errors = new WP_Error();
		
		if( empty( $data['option_name'] ) ) {
			
			$errors->add( 'empty_option_name' , __( 'Option name is empty.' , $coppi->Plugin->ltd ) );
			return $errors;

		}
		
		if( empty( $data['option_value'] ) ) {
			
			$errors->add( 'empty_option_value' , __( 'Option value is empty.' , $coppi->Plugin->ltd ) );
			return $errors;

		}
		
		$get_options = $this->get_datas();

		if( !empty( $get_options ) ) {
			
			foreach( $get_options as $get_option ) {
				
				if( $get_option['option_id'] == $data['option_id'] )
					continue;

				if( $get_option['option_name'] == $data['option_name'] ) {
						
					$errors->add( 'dupilicate_option_name' , __( 'Option name is duplicated.' , $coppi->Plugin->ltd ) );
					return $errors;
	
				}
				
			}
			
		}
		
		return $errors;
		
	}

	public function get_data_by_name( $optoin_name = false )
	{
		
		global $wpdb;
		global $coppi;

		$data = array();
		
		if( empty( $optoin_name ) )
			return false;
		
		$optoin_name = strip_tags( $optoin_name );
		
		$sql = "SELECT * FROM `$this->table` WHERE ";
		$sql .= $wpdb->prepare( ' `option_name` = %s' , $optoin_name );
	
		$get_datas = $wpdb->get_results( $sql );
	
		if( !empty( $get_datas ) ) {
				
			foreach( $get_datas as $get_data ) {
					
				$data = $this->data_format( $get_data );
					
			}

		}

		return $data;

	}
	

}

endif;
