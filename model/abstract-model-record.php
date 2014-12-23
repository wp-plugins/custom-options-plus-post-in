<?php

if ( !class_exists( 'Coppi_Model_Abstract_Record' ) ) :

abstract class Coppi_Model_Abstract_Record
{
	
	protected $record;
	




	public function __construct() {}
	
	public function get_name()
	{
		
		return $this->record;
		
	}

	protected function get_record()
	{
		
		global $coppi;

		if( $coppi->Site->is_multisite ) {

			$data = get_option( $this->record );
			
		} else {

			$data = get_option( $this->record );
			
		}
		
		return $data;

	}

	protected function update_record( $data )
	{
		
		global $coppi;

		if( $coppi->Site->is_multisite ) {

			update_option( $this->record , $data );
			
		} else {

			update_option( $this->record , $data );
			
		}
		
	}

}

endif;
