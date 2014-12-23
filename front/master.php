<?php

if ( !class_exists( 'Coppi_Front_Master' ) ) :

final class Coppi_Front_Master
{

	private $ready_front;






	public function __construct()
	{
		
		global $coppi;
		
		$this->ready_front = false;

		add_action( $coppi->Plugin->ltd . '_init' ,  array( $this , 'init' ) , 100 );

	}
	
	public function init()
	{
		
		global $coppi;
		
		if( $coppi->Env->is_admin )
			return false;
		
		$this->before_init();
		
		$this->ready_front_check();
		
		if( $this->ready_front ) {

			$this->do_front();
			
		} else {
			
			$this->not_do_front();

		}

	}
	
	private function before_init()
	{

		new Coppi_Front_Before_Init();

	}

	private function ready_front_check()
	{
		
		$this->ready_front = true;
		
	}
	
	private function do_front()
	{
		
		new Coppi_Front_Controller_Shortcode();
		
	}
	
	private function not_do_front() {}
	
}

endif;
