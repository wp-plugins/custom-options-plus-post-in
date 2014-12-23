<?php

if ( !class_exists( 'Coppi_Front_Controller_Shortcode' ) ) :

final class Coppi_Front_Controller_Shortcode
{

	public function __construct()
	{
		
		$this->init();
		
	}

	private function init()
	{
		
		global $coppi;
		
		if( $coppi->Env->is_admin )
			return false;

		if( !$coppi->Env->is_ajax ) {
			
			add_shortcode( $coppi->Plugin->ltd , array( $this , 'add_shortcode' ) );

		}
		
	}
	
	public function add_shortcode( $atts )
	{

		global $coppi;
		
		if( empty( $atts['key'] ) )
			return false;
		
		return $coppi->Api->get_coppi( $atts['key'] );

	}

}

endif;
