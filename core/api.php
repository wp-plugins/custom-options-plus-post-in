<?php

if ( !class_exists( 'Coppi_Api' ) ) :

final class Coppi_Api
{

	public function __construct() {}
	
	public function get_coppi( $key = false )
	{
		
		$str = false;
		
		if( empty( $key ) )
			return false;
		
		$option_name = strip_tags( $key );
		
		$Model = new Coppi_Custom_Option_Model();
		$data = $Model->get_data_by_name( $option_name );
		
		if( empty( $data ) or empty( $data['option_value'] ) )
			return false;

		$str = stripslashes( $data['option_value'] );
		
		return $str;

	}
	
}

endif;




function get_coppi( $key = false )
{
	
	global $coppi;
	
	return $coppi->Api->get_coppi( $key );
	
}




