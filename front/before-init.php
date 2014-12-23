<?php

if ( !class_exists( 'Coppi_Front_Before_Init' ) ) :

final class Coppi_Front_Before_Init
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

	}
	
}

endif;
