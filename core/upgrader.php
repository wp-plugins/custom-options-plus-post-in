<?php

if ( !class_exists( 'Coppi_Upgrader' ) ) :

final class Coppi_Upgrader
{

	private $db_ver;
	private $current_db_ver;
	private $Model;

	public function __construct()
	{
		
		global $coppi;

		$this->db_ver = '1.2';

		$this->Model = new stdClass;

		$this->Model->DBVer = new Coppi_DB_Ver_Model();

		$this->current_db_ver = $this->Model->DBVer->get_data();

	}

	private function init()
	{
		
		global $coppi;

		$this->Model->CustomOption = new Coppi_Custom_Option_Model();

		$this->Model->Category = new Coppi_Categories_Model();

	}

	public function is_upgrade()
	{
		
		global $coppi;
		
		if( empty( $this->current_db_ver ) )
			return false;

		if( $this->current_db_ver != $this->db_ver )
			return false;

		return true;
		
	}

	public function db_install()
	{
		
		global $wpdb;
		global $coppi;

		$this->init();
		
		$is_table_custom_option = $this->Model->CustomOption->is_db();
		$is_table_category = $this->Model->Category->is_db();
		
		if( !empty( $is_table_custom_option ) or !empty( $is_table_category ) )
			return false;

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		$sql = $this->get_custom_option_table_insert_sql();
		$sql .= $this->get_category_table_insert_sql();
		
		dbDelta($sql);
		$this->Model->DBVer->update_data( $this->db_ver );

	}
	
	private function get_custom_option_table_insert_sql()
	{
		
		global $wpdb;
		global $coppi;
		
		$charset_collate = false;

		if( !empty( $wpdb->charset ) )
			$charset_collate = $wpdb->prepare( "DEFAULT CHARACTER SET %s", $wpdb->charset );

		if( !empty( $wpdb->collate ) )
			$charset_collate .= $wpdb->prepare( " COLLATE %s", $wpdb->collate );

		$table = $this->Model->CustomOption->get_name();

		$sql = "CREATE TABLE $table(
			option_id bigint(20) unsigned NOT NULL auto_increment,
			option_name varchar(255) NOT NULL default '',
			option_value longtext NOT NULL,
			cat_id bigint(20) unsigned NOT NULL default '0',
			create_date datetime NOT NULL default '0000-00-00 00:00:00',
			PRIMARY KEY  (option_id),
			UNIQUE (option_name)
		) $charset_collate;";

		return $sql;

	}

	private function get_category_table_insert_sql()
	{
		
		global $wpdb;
		
		$charset_collate = false;

		if( !empty( $wpdb->charset ) )
			$charset_collate = $wpdb->prepare( "DEFAULT CHARACTER SET %s", $wpdb->charset );

		if( !empty( $wpdb->collate ) )
			$charset_collate .= $wpdb->prepare( " COLLATE %s", $wpdb->collate );

		$table = $this->Model->Category->get_name();

		$sql = "CREATE TABLE $table(
			cat_id bigint(20) unsigned NOT NULL auto_increment,
			cat_name varchar(255) NOT NULL default '',
			create_date datetime NOT NULL default '0000-00-00 00:00:00',
			PRIMARY KEY  (cat_id),
			UNIQUE (cat_name)
		) $charset_collate;";

		return $sql;

	}

}

endif;
