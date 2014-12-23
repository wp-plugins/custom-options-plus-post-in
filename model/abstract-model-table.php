<?php

if ( !class_exists( 'Coppi_Model_Abstract_Table' ) ) :

abstract class Coppi_Model_Abstract_Table
{

	protected $table;
	protected $prefix;
	protected $prefix_network;




	public function __construct()
	{
		
		global $wpdb;
		global $coppi;

		if( $coppi->Site->is_multisite ) {

			$this->prefix_network = $wpdb->base_prefix;
			
		} else {

			$this->prefix = $wpdb->prefix;
			
		}

		$this->init();
		
	}
	
	protected function init() {}
	
	public function is_db()
	{
		
		global $wpdb;

		$sql = $wpdb->prepare( "SHOW TABLES LIKE %s", $this->table );
		$results = $wpdb->get_var( $sql );
		
		if( empty( $results ) )
			return false;
		
		return true;

	}
	
	public function get_name()
	{
		return $this->table;
	}

}

endif;
