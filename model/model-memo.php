<?php

if ( !class_exists( 'Coppi_Memo_Model' ) ) :

final class Coppi_Memo_Model extends Coppi_Model_Abstract_Record
{

	public function __construct()
	{
		
		global $coppi;
		
		if( $coppi->Site->is_multisite ) {

			$this->record = $coppi->Records->memo;
			
		} else {

			$this->record = $coppi->Records->memo;
			
		}
		
		parent::__construct();

	}
	
	public function get_data()
	{
		
		global $coppi;
		
		$data = $this->get_record();
		
		if( empty( $data ) ) {

			$data['memo'] = false;
			
		} else {

			$data = $this->data_format( array( 'memo' => $data ) );
			
		}
		
		return $data['memo'];
		
	}
	
	public function update_data( $post_data = false )
	{
		
		global $coppi;
		
		if( empty( $post_data ) )
			return false;
		
		$data = $this->data_format( $post_data );
		
		$this->update_record( $data['memo'] );
				
	}
	
	public function data_format( $data )
	{
		
		$data['memo'] = strip_tags( $data['memo'] );
		
		return $data;
		
	}
	
}

endif;
