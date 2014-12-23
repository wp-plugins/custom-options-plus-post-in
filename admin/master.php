<?php

if ( !class_exists( 'Coppi_Admin_Master' ) ) :

final class Coppi_Admin_Master
{

	private $ready_admin;






	public function __construct() {
		
		global $coppi;
		
		add_action( $coppi->Plugin->ltd . '_init' ,  array( $this , 'init' ) , 100 );
		
	}
	
	public function init()
	{
		
		global $coppi;
		
		if( !$coppi->Env->is_admin )
			return false;
		
		$this->ready_admin_check();
		
		if( $this->ready_admin ) {

			$this->do_admin();
			
		} else {
			
			$this->not_do_admin();

		}

	}
	
	private function ready_admin_check()
	{
		
		$this->ready_admin = true;
		
	}
	
	private function do_admin() {}
	
	private function not_do_admin() {}
	
}

endif;
