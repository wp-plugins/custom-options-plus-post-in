<?php

if ( !class_exists( 'Coppi_DB_Ver_Model' ) ) :

final class Coppi_DB_Ver_Model extends Coppi_Model_Abstract_Record
{

	public function __construct()
	{
		
		global $coppi;
		
		if( $coppi->Site->is_multisite ) {

			$this->record = $coppi->Records->db_ver;
			
		} else {

			$this->record = $coppi->Records->db_ver;
			
		}
		
		parent::__construct();

	}
	
	public function get_data()
	{
		
		global $coppi;
		
		$data = $this->get_record();

		if( !empty( $data ) )
			$data = $this->data_format( $data );
		
		return $data;
		
	}

	public function update_data( $post_data = false )
	{
		
		global $coppi;
		
		if( empty( $post_data ) )
			return false;
		
		$data = $this->data_format( $post_data );
		
		$this->update_record( $data );
				
	}
	
	protected function data_format( $data )
	{
		
		return strip_tags( $data );
		
	}

	
}

endif;
