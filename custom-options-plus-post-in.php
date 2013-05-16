<?php
/*
Plugin Name: Custom Options Plus Post In
Description: Add the value of the option. and Available for use in the post article.
Plugin URI: http://gqevu6bsiz.chicappa.jp
Version: 1.2.1
Author: gqevu6bsiz
Author URI: http://gqevu6bsiz.chicappa.jp/
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







class Custom_Options_Plus_Post_In
{

	var $Ver,
		$DBVer,
		$Name,
		$Dir,
		$ltd,
		$Table,
		$PageSlug,
		$UPFN,
		$Duplicated,
		$Order,
		$Msg;


	function __construct() {

		global $wpdb;

		$this->Ver = '1.2.1';
		$this->DBVer = '1.0';
		$this->Name = 'Custom Options Plus Post In';
		$this->Dir = WP_PLUGIN_URL . '/' . dirname( plugin_basename( __FILE__ ) ) . '/';
		$this->ltd = 'coppi';
		$this->Table = $wpdb->prefix . 'coppi';
		$this->PageSlug = 'coppi';
		$this->UPFN = 'Y';

		$this->Duplicated = false;
		$this->Order = array( "orderby" => "create_date" , "order" => "asc" );
		
		$this->PluginSetup();
	}


	// PluginSetup
	function PluginSetup() {
		// load text domain
		load_plugin_textdomain( $this->ltd , false , basename( dirname( __FILE__ ) ) . '/languages' );

		// plugin links
		add_filter( 'plugin_action_links' , array( $this , 'plugin_action_links' ) , 10 , 2 );

		// add menu
		add_action( 'admin_menu' , array( $this , 'admin_menu' ) , 2 );
		
		// setup database
		register_activation_hook( __FILE__ , array( $this , 'Setup_DB' ) );
	}

	// PluginSetup
	function plugin_action_links( $links , $file ) {

		if( plugin_basename(__FILE__) == $file ) {

			$mofile = $this->TransFileCk();
			if( $mofile == false ) {
				$translation_link = '<a href="http://gqevu6bsiz.chicappa.jp/please-translation/">Please translation</a>'; 
				array_unshift( $links, $translation_link );
			}
			$donation_link = '<a href="http://gqevu6bsiz.chicappa.jp/please-donation/">' . __( 'Donation' , $this->ltd ) . '</a>';
			array_unshift( $links, $donation_link );
			array_unshift( $links, '<a href="' . admin_url( 'options-general.php?page=' . $this->PageSlug ) . '">' . __('Settings') . '</a>' );

		}

		return $links;

	}

	// PluginSetup
	function admin_menu() {
		add_options_page( $this->Name , __( 'Customs Option' , $this->ltd ) . '(coppi)' , 'administrator' , $this->PageSlug , array( $this , 'coppi_setting') );
	}


	// Translation File Check
	function TransFileCk() {
		$file = false;
		$moFile = WP_PLUGIN_DIR . '/' . dirname( plugin_basename( __FILE__ ) ) . '/languages/' . $this->ltd . '-' . get_locale() . '.mo';
		if( file_exists( $moFile ) ) {
			$file = true;
		}
		return $file;
	}


	// Setup Database
	function Setup_DB () {
		global $wpdb;

		if( $wpdb->get_var( "SHOW TABLES LIKE '" . $this->Table . "'" ) != $this->Table ) {
	
			if ( ! empty( $wpdb->charset ) ) {
				$charset_collate = "DEFAULT CHARACTER SET " . $wpdb->charset;
			}
			if ( ! empty( $wpdb->collate ) ) {
				$charset_collate .= " COLLATE " . $wpdb->collate;
			}
	
			$sql = "CREATE TABLE " . $this->Table . " (
				option_id bigint(20) unsigned NOT NULL auto_increment,
				option_name varchar(255) NOT NULL default '',
				option_value longtext NOT NULL,
				create_date datetime NOT NULL default '0000-00-00 00:00:00',
				PRIMARY KEY  (option_id),
				UNIQUE (option_name)
			) " . $charset_collate . ";";
	
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);

			// Data Move
			$OldData = get_option( $this->PageSlug );
			if( !empty( $OldData ) ) {
				foreach( $OldData as $content ) {
					$Update["option_name"] = strip_tags( $content["key"] );
					$Update["option_value"] = $content["val"];
					$Update["create_date"] = strip_tags( $content["create_date"] );
					$wpdb->query(
						"INSERT INTO `" . $this->Table . "` (`option_id`, `option_name`, `option_value`, `create_date`) VALUES (NULL, '" . join( "','" , $Update ) . "');"
					);
				}
			}

		}

		update_option( 'coppi_db_ver' , $this->DBVer );

	}




	// SettingPage
	function coppi_setting() {
		if( !empty( $_POST["update"] ) ) {
			$this->update();
		} elseif( !empty( $_GET["delete"] ) ) {
			$this->delete();
		}
		include_once 'inc/setting.php';
	}




	// SettingList
	function order() {
		if ( !empty( $_GET['orderby'] ) && !empty( $_GET['order'] ) ) {
			$od1 = strip_tags( $_GET['orderby'] );
			$od2 = strip_tags( $_GET['order'] );
			
			if( $od1 == 'create_date' or $od1 == 'option_name' or $od1 == 'option_value' ) {
				$this->Order["orderby"] = strip_tags( $_GET['orderby'] );
			}
			if( $od2 == 'asc' or $od2 == 'desc' ) {
				$this->Order["order"] = strip_tags( $_GET['order'] );
			}
		}
	}




	// GetData
	function get_datas() {
		global $wpdb;

		$GetData = $wpdb->get_results( "SELECT * FROM " . $this->Table . " ORDER BY " . $this->Order["orderby"] . " " . $this->Order["order"] );

		$Data = array();
		if( !empty( $GetData ) ) {
			$Data = $GetData;
		}

		return $Data;
	}

	// GetData
	function get_data( $option_name ) {
		global $wpdb;

		$GetData = $wpdb->get_var( "SELECT `option_value` FROM " . $this->Table . " WHERE `option_name` LIKE '" . strip_tags( $option_name ) . "'" );

		$Data = "";
		if( !empty( $GetData ) ) {
			$Data = $GetData;
		}

		return $Data;
	}





	// DataUpdate
	function update_validate() {
		$Update = array();

		if( !empty( $_POST[$this->UPFN] ) && check_admin_referer( 'coppi' ) ) {
			$UPFN = strip_tags( $_POST[$this->UPFN] );
			if( $UPFN == $this->UPFN ) {
				$Update["UPFN"] = strip_tags( $_POST[$this->UPFN] );
			}
		}

		return $Update;
	}


	// DataUpdate
	function update() {
		global $wpdb;

		$Update = $this->update_validate();
		if( !empty( $Update ) ) {

			if( !empty( $_POST["data"] ) ) {
				
				// duplicate check
				$checkkey = "";
				$checkid = "";
				if( !empty( $_POST["data"]["create"]["option_name"] ) ) {
					$checkkey = strip_tags( $_POST["data"]["create"]["option_name"] );
				} elseif( !empty( $_POST["data"]["update"]["option_name"] ) ) {
					$checkkey = strip_tags( $_POST["data"]["update"]["option_name"] );
					$checkid = $_POST["data"]["update"]["option_id"];
				}
				$dup_query = $wpdb->get_var( "SELECT * FROM " . $this->Table . " WHERE `option_name` LIKE '" . $checkkey . "'" );
				if( !empty( $dup_query ) && $dup_query != $checkid ) {
					$this->Duplicated = true;
				}

				$type = false;

				// create
				if( !empty( $_POST["data"]["create"] ) ) {

					$tmpK = strip_tags( $_POST["data"]["create"]["option_name"] );
					$tmpV = $_POST["data"]["create"]["option_value"];
					$date = date( 'Y-m-d H:i:s' );
					$Update = array( "option_name" => $tmpK , "option_value" => $tmpV , "create_date" => $date );
					$type = 'create';

				}

				// update
				if( !empty( $_POST["data"]["update"] ) ) {

					$tmpK = strip_tags( $_POST["data"]["update"]["option_name"] );
					$tmpV = $_POST["data"]["update"]["option_value"];
					$Update = array( "option_name" => $tmpK , "option_value" => $tmpV , "option_id" => strip_tags( $_POST["data"]["update"]["option_id"] ) );
					$type = 'update';
				}

				if( $this->Duplicated == false ) {
					
					if( $type == 'create' ) {
						$wpdb->query(
							"INSERT INTO `" . $this->Table . "` (`option_id`, `option_name`, `option_value`, `create_date`) VALUES (NULL, '" . join( "','" , $Update ) . "');"
						);
					} elseif( $type == 'update' ) {
						$wpdb->query(
							"UPDATE " . $this->Table . " SET option_name = '" . $Update["option_name"] . "',option_value = '" . $Update["option_value"] . "' WHERE option_id = " . $Update["option_id"]
						);
					}
					$this->Msg .= '<div class="updated"><p><strong>' . __('Settings saved.') . '</strong></p></div>';
				} else {
					$this->Msg .= '<div class="error"><p><strong>' . __( 'Option name is duplicated.' , $this->ltd ) . '</strong></p></div>';
				}



			}
		}
	}


	// DataUpdate
	function delete() {

		global $wpdb;

		$id = $_GET["delete"];
		$wpdb->query( "DELETE FROM " . $this->Table . " WHERE option_id = " . $id );
		$this->Msg .= '<div class="updated"><p><strong>' . __('Settings saved.') . '</strong></p></div>';

	}


}

$coppi = new Custom_Options_Plus_Post_In();






// Shortcode
function coppi_shortcode( $atts ) {
	$ret = '';
	
	if( !empty( $atts["key"] ) ) {
		$coppi = new Custom_Options_Plus_Post_In();
		$ret = $coppi->get_data( $atts["key"] );
	}
	
	return $ret;
}
add_shortcode('coppi', 'coppi_shortcode');



// Template
function get_coppi( $key = '' ) {
	$ret = '';
	
	if( !empty( $key ) ) {
		$coppi = new Custom_Options_Plus_Post_In();
		$ret = $coppi->get_data( $key );
	}
	
	return $ret;
}


?>