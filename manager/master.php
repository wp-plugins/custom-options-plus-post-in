<?php

if ( !class_exists( 'Coppi_Manager_Master' ) ) :

final class Coppi_Manager_Master
{

	private $ready_manager;






	public function __construct()
	{
		
		global $coppi;
		
		$this->ready_manager = false;
		
		add_action( $coppi->Plugin->ltd . '_init' ,  array( $this , 'init' ) , 100 );

	}
	
	public function init()
	{
		
		global $coppi;
		
		if( !$coppi->Env->is_admin or !$coppi->User->is_manager )
			return false;
		
		$this->before_init();
		
		$this->ready_manage_check();
		
		if( $this->ready_manager ) {

			$this->do_manager();
			
		} else {
			
			$this->not_do_manager();

		}

	}
	
	private function before_init()
	{

		new Coppi_Manager_Before_Init();

	}

	private function ready_manage_check()
	{
		
		$upgrader = new Coppi_Upgrader();
		
		if( !$upgrader->is_upgrade() )
			return false;
		
		$this->ready_manager = true;
		
	}
	
	private function do_manager()
	{
		
		new Coppi_Manager_Controller_Custom_Option();
		new Coppi_Manager_Controller_Memo();
		new Coppi_Manager_Controller_Category();
		
	}
	
	private function not_do_manager()
	{
		
		new Coppi_Manager_Controller_Not_Do();

	}
	
}

endif;
