<?php
/*
Plugin Name: Custom Options Plus Post In
Description: This plugin is create to custom options in your WordPress. You can use in the Template and Shortcode.
Plugin URI: http://wordpress.org/plugins/custom-options-plus-post-in/
Version: 1.4.1
Author: gqevu6bsiz
Author URI: http://gqevu6bsiz.chicappa.jp/?utm_source=use_plugin&utm_medium=list&utm_content=coppi&utm_campaign=1_4_1
Text Domain: coppi
Domain Path: /languages
*/

/*  Copyright 2012 gqevu6bsiz (email : gqevu6bsiz@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/



if ( !class_exists( 'Coppi' ) ) :

final class Coppi
{

	public $name = 'Custom Options Plus Post In';
	public $ver = '1.4.1';

	private $plugin_dir;

	public $Plugin;
	public $Form;
	public $Other;
	public $Tables;
	public $Records;
	public $Site;
	public $Env;
	public $User;
	public $ThirdParty;

	public $Info;
	public $Api;
	public $Helper;

    public function __construct() {
		
        $this->plugin_dir = plugin_dir_path( __FILE__ );

		$this->Plugin     = new stdClass;
		$this->Form       = new stdClass;
		$this->Other      = new stdClass;
		$this->Tables     = new stdClass;
		$this->Records    = new stdClass;
		$this->Site       = new stdClass;
		$this->Env        = new stdClass;
		$this->User       = new stdClass;
		$this->ThirdParty = new stdClass;

		$this->Info       = new stdClass;
		$this->Api        = new stdClass;
		$this->Helper     = new stdClass;
		
	}
	
	public function init()
	{
		
		$this->includes();
		
		add_action( 'init' , array( $this , 'core_initialized' ) );
		
	}

	public function includes()
	{

		$includes = array(

			'core' => array(

				'init.php',
				'upgrader.php',
				'info.php',
				'api.php',
				'helper.php',

			),

			'manager' => array(

				'master.php',
				'before-init.php',
				'abstract-controller.php',
				'controller-not-do-manager.php',
				'controller-custom-option.php',
				'controller-category.php',
				'controller-memo.php',

			),

			'admin' => array(

				'master.php',

			),

			'front' => array(

				'master.php',
				'before-init.php',
				'controller-shortcode.php',

			),

			'model' => array(

				'abstract-model-record.php',
				'abstract-model-table.php',
				'model-category.php',
				'model-custom-option.php',
				'model-memo.php',
				'model-db-ver.php',

			),

		);
		
		foreach( $includes as $dir_name => $files ) {
		
			if( empty( $files ) or empty( $files[0] ) )
				continue;
			
			foreach( $files as $file_name ) {
				
				include_once( $this->plugin_dir . trailingslashit( $dir_name ) . $file_name );
				
			}
			
		}

	}
	
	public function core_initialized()
	{
		
		load_plugin_textdomain( $this->Plugin->ltd , false , $this->Plugin->plugin_slug . '/languages' );
		
		$this->Api = new Coppi_Api();
		$this->Helper = new Coppi_Helper();
		$this->Info = new Coppi_Info();
		
		if( $this->Env->is_admin ) {
			
			new Coppi_Admin_Master();
			new Coppi_Manager_Master();
			
		} else {
			
			new Coppi_Front_Master();

		}

		$this->set_actions();

	}
	
	private function set_actions()
	{
		
		do_action( $this->Plugin->ltd . '_init' );
		
	}
	
}

$GLOBALS['coppi'] = new Coppi();
$GLOBALS['coppi']->init();

endif;
