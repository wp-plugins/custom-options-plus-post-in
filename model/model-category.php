<?php

if ( !class_exists( 'Coppi_Categories_Model' ) ) :

final class Coppi_Categories_Model extends Coppi_Model_Abstract_Table
{

	protected function init()
	{
		
		global $wpdb;
		global $coppi;

		if( $coppi->Site->is_multisite ) {

			$this->table = $this->prefix_network . $coppi->Tables->cat;
			
		} else {

			$this->table = $this->prefix . $coppi->Tables->cat;
			
		}
		
		$this->default_items = false;
		
	}

	public function get_datas()
	{
		
		global $wpdb;
		global $coppi;

		$data = array();
		
		$cache_key = 'cat_get_datas';
		
		$cache = wp_cache_get( $cache_key , $coppi->Plugin->ltd );

		if( $cache !== false ) {
			
			$data = $cache;
			
		} else {

			$sql = "SELECT * FROM `$this->table` ORDER BY `cat_id` ASC";
	
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
			
			$data['cat_id'] = 0;
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

			$cat_id = $data['cat_id'];

			unset( $data['cat_id'] , $data['create_date'] );

			$wpdb->update( $this->table , $data , array( 'cat_id' => $cat_id ) );
			
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
		
		foreach( $post_datas['remove_ids'] as $key => $cat_id ) {

			$cat_id = intval( $cat_id );
			
			if( !empty( $cat_id ) )
				$wpdb->delete( $this->table , array( 'cat_id' => $cat_id ) );
			
		}
		
		return $errors;

	}

	public function data_format( $data )
	{
		
		if( is_object( $data ) )
			$data = (array) $data;

		$data['cat_id'] = intval( $data['cat_id'] );
		$data['cat_name'] = strip_tags( $data['cat_name'] );
		$data['create_date'] = strip_tags( $data['create_date'] );
		
		return $data;
		
	}
	
	public function validate_data( $data )
	{
		
		global $coppi;
		
		$errors = new WP_Error();

		if( empty( $data['cat_name'] ) ) {
			
			$errors->add( 'empty_cat_name' , __( 'Category name is empty.' , $coppi->Plugin->ltd ) );
			return $errors;

		}
		
		$get_cats = $this->get_datas();
		
		if( !empty( $get_cats ) ) {
			
			foreach( $get_cats as $get_cat ) {
				
				if( $get_cat['cat_name'] == $data['cat_name'] ) {
					
					$errors->add( 'dupilicate_cat_name' , __( 'Category name is duplicated.' , $coppi->Plugin->ltd ) );
					return $errors;

				}
				
			}
			
		}
		
		return $errors;
		
	}
	
	public function get_ids()
	{
		
		global $coppi;

		$data = array();
		
		$cache_key = 'cat_get_ids';
		
		$cache = wp_cache_get( $cache_key , $coppi->Plugin->ltd );

		if( $cache !== false ) {
			
			$data = $cache;
			
		} else {

			$get_datas = $this->get_datas();
	
			if( !empty( $get_datas ) ) {
				
				foreach( $get_datas as $get_data ) {
					
					$cat = $this->data_format( $get_data );
					$data[] = $cat['cat_id'];
					
				}
				
			}
			
			wp_cache_set( $cache_key , $data , $coppi->Plugin->ltd );

		}

		return $data;

	}


}

endif;
