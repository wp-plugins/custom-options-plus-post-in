<?php
/*
Plugin Name: Custom Options Plus Post In
Description: This plugin is create to custom options in your WordPress. You can use in the Template and Shortcode.
Plugin URI: http://wordpress.org/plugins/custom-options-plus-post-in/
Version: 1.3
Author: gqevu6bsiz
Author URI: http://gqevu6bsiz.chicappa.jp/?utm_source=use_plugin&utm_medium=list&utm_content=coppi&utm_campaign=1_3
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
		$Url,
		$AuthorUrl,
		$ltd,
		$ltd_p,
		$Record,
		$Table,
		$PageSlug,
		$PluginSlug,
		$Nonce,
		$Schema,
		$UPFN,
		$DonateKey,
		$Duplicated,
		$Order,
		$Msg;


	function __construct() {

		global $wpdb;

		$this->Ver = '1.3';
		$this->DBVer = '1.1';
		$this->Name = 'Custom Options Plus Post In';
		$this->Dir = plugin_dir_path( __FILE__ );
		$this->Url = plugin_dir_url( __FILE__ );
		$this->AuthorUrl = 'http://gqevu6bsiz.chicappa.jp/';
		$this->ltd = 'coppi';
		$this->ltd_p = $this->ltd . '_plugin';
		$this->Record = array(
			"db_ver" => $this->ltd . '_db_ver',
			"memo" => $this->ltd . '_memo',
			"donate" => $this->ltd . '_donated',
			"donate_width" => $this->ltd . '_donated_width',
		);
		$this->Table = array( "option" => $wpdb->prefix . 'coppi' , "cat" => $wpdb->prefix . 'coppi_cat' );
		$this->PageSlug = 'coppi';
		$this->PluginSlug = dirname( plugin_basename( __FILE__ ) );
		$this->Nonce = $this->PageSlug;
		$this->Schema = is_ssl() ? 'https://' : 'http://';
		$this->UPFN = 'Y';
		$this->DonateKey = 'd77aec9bc89d445fd54b4c988d090f03';

		$this->Duplicated = false;
		$this->DuplicatedCat = false;
		$this->Order = array( "orderby" => "create_date" , "order" => "asc" );
		
		$this->PluginSetup();
	}


	// PluginSetup
	function PluginSetup() {
		// load text domain
		load_plugin_textdomain( $this->ltd , false , $this->PluginSlug . '/languages' );
		load_plugin_textdomain( $this->ltd_p , false , $this->PluginSlug . '/languages' );

		// plugin links
		add_filter( 'plugin_action_links' , array( $this , 'plugin_action_links' ) , 10 , 2 );

		// add menu
		add_action( 'admin_menu' , array( $this , 'admin_menu' ) , 2 );
		
		// setup database
		register_activation_hook( __FILE__ , array( $this , 'Setup_DB' ) );

		// get donation toggle
		add_action( 'wp_ajax_' . $this->ltd . '_get_donation_toggle' , array( $this , 'wp_ajax_' . $this->ltd . '_get_donation_toggle' ) );

		// set donation toggle
		add_action( 'wp_ajax_' . $this->ltd . '_set_donation_toggle' , array( $this , 'wp_ajax_' . $this->ltd . '_set_donation_toggle' ) );
	}

	// PluginSetup
	function plugin_action_links( $links , $file ) {

		if( plugin_basename(__FILE__) == $file ) {

			$mofile = $this->TransFileCk();
			if( $mofile == false ) {
				$translation_link = '<a href="' . $this->AuthorUrl . 'please-translation/">Please translate</a>'; 
				array_unshift( $links, $translation_link );
			}
			array_unshift( $links, '<a href="' . admin_url( 'options-general.php?page=' . $this->PageSlug ) . '">' . __( 'Settings' ) . '</a>' );

		}

		return $links;

	}

	// PluginSetup
	function admin_menu() {
		add_options_page( $this->Name , __( 'Custom Option' , $this->ltd ) . '(coppi)' , 'administrator' , $this->PageSlug , array( $this , 'coppi_setting') );
	}


	// Translation File Check
	function TransFileCk() {
		$file = false;
		$moFile = $this->Dir . 'languages/' . $this->ltd . '-' . get_locale() . '.mo';
		if( file_exists( $moFile ) ) {
			$file = true;
		}
		return $file;
	}


	// Setup Database
	function Setup_DB () {
		global $wpdb;

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		$CurrentDBVer = get_option( $this->Record["db_ver"] );

		if( empty( $CurrentDBVer ) ) {
			
			if( $wpdb->get_var( "SHOW TABLES LIKE '" . $this->Table["option"] . "'" ) != $this->Table["option"] ) {
		
				if ( ! empty( $wpdb->charset ) ) {
					$charset_collate = "DEFAULT CHARACTER SET " . $wpdb->charset;
				}
				if ( ! empty( $wpdb->collate ) ) {
					$charset_collate .= " COLLATE " . $wpdb->collate;
				}
		
				$sql = "CREATE TABLE " . $this->Table["option"] . " (
					option_id bigint(20) unsigned NOT NULL auto_increment,
					option_name varchar(255) NOT NULL default '',
					option_value longtext NOT NULL,
					cat_id bigint(20) unsigned NOT NULL default '0',
					create_date datetime NOT NULL default '0000-00-00 00:00:00',
					PRIMARY KEY  (option_id),
					UNIQUE (option_name)
				) " . $charset_collate . ";";
	
				dbDelta($sql);
	
				// Data Move
				$OldData = get_option( $this->PageSlug );
				if( !empty( $OldData ) ) {
					foreach( $OldData as $content ) {
						$Update["option_name"] = strip_tags( $content["key"] );
						$Update["option_value"] = $content["val"];
						$Update["create_date"] = strip_tags( $content["create_date"] );
						$wpdb->query(
							"INSERT INTO `" . $this->Table["option"] . "` (`option_id`, `option_name`, `option_value`, `create_date`) VALUES (NULL, '" . join( "','" , $Update ) . "');"
						);
					}
				}
	
			}

		} elseif ( version_compare( $CurrentDBVer , $this->Ver , '<' ) ) {
			
			$wpdb->query(
				"ALTER TABLE " . $this->Table["option"] . " ADD `cat_id` bigint(20) unsigned NOT NULL default '0' AFTER `option_value`"
			);

		}

		if( $wpdb->get_var( "SHOW TABLES LIKE '" . $this->Table["cat"] . "'" ) != $this->Table["cat"] ) {
		
			if ( ! empty( $wpdb->charset ) ) {
				$charset_collate = "DEFAULT CHARACTER SET " . $wpdb->charset;
			}
			if ( ! empty( $wpdb->collate ) ) {
				$charset_collate .= " COLLATE " . $wpdb->collate;
			}
		
			$sql = "CREATE TABLE " . $this->Table["cat"] . " (
				cat_id bigint(20) unsigned NOT NULL auto_increment,
				cat_name varchar(255) NOT NULL default '',
				create_date datetime NOT NULL default '0000-00-00 00:00:00',
				PRIMARY KEY  (cat_id),
				UNIQUE (cat_name)
			) " . $charset_collate . ";";
	
			dbDelta($sql);
	
		}

		update_option( $this->Record["db_ver"] , $this->DBVer );

	}




	// SettingPage
	function coppi_setting() {
		if( !empty( $_POST["donate"] ) ) {
			$this->donate_check();
		} elseif( !empty( $_POST["update_memo"] ) ) {
			$this->update_memo();
		} elseif( !empty( $_POST["update_cat"] ) ) {
			$this->update_cat();
		} elseif( !empty( $_POST["update"] ) ) {
			$this->update();
		} elseif( !empty( $_GET["delete_cat"] ) ) {
			$this->delete_cat();
		} elseif( !empty( $_GET["delete"] ) ) {
			$this->delete();
		} elseif( !empty( $_POST["bulk"] ) ) {
			$this->update_bulk();
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

		$GetData = $wpdb->get_results( "SELECT * FROM " . $this->Table["option"] . " ORDER BY " . $this->Order["orderby"] . " " . $this->Order["order"] );

		$Data = array();
		if( !empty( $GetData ) ) {
			$Data = $GetData;

			$Categories_key = $wpdb->get_results( "SELECT `cat_id` FROM " . $this->Table["cat"] );
			if( !empty( $Categories_key ) ) {
				$Cat_key = array();
				foreach( $Categories_key as $k => $cat ) {
					$Cat_key[] = $cat->cat_id;
				}
				foreach( $Data as $kk => $content ) {
					if( $content->cat_id != 0 ) {
						if( !in_array( $content->cat_id , $Cat_key ) ) {
							$Data[$kk]->cat_id = 0;
						}
					}
				}
			} else {
				foreach( $Data as $kk => $content ) {
					if( $content->cat_id != 0 ) {
						$Data[$kk]->cat_id = 0;
					}
				}
			}
		}

		return $Data;
	}

	// GetData
	function get_data( $option_name ) {
		global $wpdb;

		$GetData = $wpdb->get_var( "SELECT `option_value` FROM " . $this->Table["option"] . " WHERE `option_name` LIKE '" . strip_tags( $option_name ) . "'" );

		$Data = "";
		if( !empty( $GetData ) ) {
			$Data = $GetData;
		}

		return $Data;
	}

	// GetData
	function get_categories() {
		global $wpdb;

		$GetData = $wpdb->get_results( "SELECT * FROM " . $this->Table["cat"] . " ORDER BY `cat_id` ASC" );

		$Data = array();
		if( !empty( $GetData ) ) {
			$Data = $GetData;
		}

		return $Data;
	}




	// SettingList
	function category_option_count( $category ) {
		$Count = 0;
		$Data = $this->get_datas();

		if( !empty( $Data ) ) {
			foreach( $Data as $k => $content ) {
				if( $category->cat_id == $content->cat_id ) {
					$Count++;
				}
			}
		}
		
		return $Count;
	}

	// SettingList
	function get_list_optoin( $cat_id , $option_count ) {
		$Data = $this->get_datas();
		$Categories = $this->get_categories();

		if( !empty( $Data ) && !empty( $option_count ) ) {
			$style = '';
			if( $cat_id != 0 ) {
				$style = 'style="display: none;"';
			}
?>

			<div class="lists" <?php echo $style; ?>>
				<div class="tablenav top">
					<select name="bulkaction">
						<option value=""><?php _e( 'Bulk Actions' ); ?></option>
						<option value="delete"><?php _e( 'Delete' ); ?></option>
						<option value="change_cat"><?php _e( 'Category change' , $this->ltd ); ?></option>
					</select>
					<span class="bulk_change_cat" style="display: none;">
						<select name="cat_to">
							<option value="0"><?php _e( 'Uncategorized' ); ?></option>
							<?php if( !empty( $Categories ) ) : ?>
								<?php foreach( $Categories as $k => $cat) : ?>
									<option value="<?php echo strip_tags( $cat->cat_id ); ?>"><?php echo strip_tags( $cat->cat_name ); ?></option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
						<input type="hidden" name="cat_from" value="<?php echo $cat_id; ?>" />
					</span>

					<input type="button" class="button-secondary action" value="<?php _e( 'Apply' ); ?>">
				</div>
				<table cellspacing="0" class="widefat fixed">
					<thead>
						<tr>
							<th class="check-column"><input type="checkbox"></th>
							<?php
							$SortHeader = array(
								array( "sort_type" => "create_date" , "sort_name" => __( 'Create Date' , $this->ltd ) ),
								array( "sort_type" => "option_name" , "sort_name" => __( 'Option Name' , $this->ltd ) ),
								array( "sort_type" => "option_value" , "sort_name" => __( 'Option Value' , $this->ltd ) ),
							);
							?>
							<?php foreach( $SortHeader as $sorter ) : ?>
								<?php $Cls = 'sortable asc'; $Od = 'asc'; ?>
								<?php if( $this->Order["orderby"] == $sorter["sort_type"] ) : ?>
									<?php $Cls = 'sorted ' . $this->Order["order"]; ?>
								<?php endif; ?>
								<?php if( $this->Order["order"] == 'asc' ) : ?>
									<?php $Od = 'desc'; ?>
								<?php endif; ?>
								<th class="<?php echo $sorter["sort_type"]; ?> <?php echo $Cls; ?>">
									<a href="<?php echo esc_url( add_query_arg( array( "orderby" => $sorter["sort_type"] , "order" => $Od ) ) ); ?>">
										<span><?php echo $sorter["sort_name"]; ?></span>
										<span class="sorting-indicator"></span>
									</a>
								</th>
							<?php endforeach; ?>
							<th class="template_tag"><?php _e( 'Tag of the template' , $this->ltd ); ?></th>
							<th class="shortcode"><?php _e( 'Shortcode' , $this->ltd ); ?></th>
							<th class="operation">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						<?php $field = 'update'; ?>
						<?php foreach( $Data as $key => $content ) : ?>
							<?php if( $content->cat_id == $cat_id ) : ?>
	
								<form class="coppi_form" method="post" action="">
									<input type="hidden" name="<?php echo $this->UPFN; ?>" value="Y" />
									<input type="hidden" name="data[<?php echo $field; ?>][option_id]" value="<?php echo strip_tags( $content->option_id ); ?>" />
									<?php wp_nonce_field( $this->PageSlug ); ?>
			
									<tr id="tr_<?php echo $content->option_id; ?>">
										<th class="check-column">
											<input type="checkbox" name="data[<?php echo $field; ?>][]" value="<?php echo strip_tags( $content->option_id ); ?>">
										</th>
										<td class="create_date">
											<?php echo mysql2date( get_option('date_format') , strip_tags( $content->create_date ) ); ?><br />
											<span style="font-size: 10px;">(<?php echo strip_tags( $content->create_date ); ?>)</span>
										</td>
										<td class="option_name">
											<div class="off">
												<p><input type="text" name="data[<?php echo $field; ?>][option_name]" value="<?php echo strip_tags( $content->option_name ); ?>" /></p>
												<p><?php _e( 'Category' ); ?>: <select name="data[<?php echo $field; ?>][cat_id]">
													<option value="0" <?php selected( 0 , $content->cat_id ); ?>><?php _e( 'Uncategorized' ); ?></option>
													<?php if( !empty( $Categories ) ) : ?>
														<?php foreach( $Categories as $k => $cat) : ?>
															<option value="<?php echo strip_tags( $cat->cat_id ); ?>" <?php selected( $cat->cat_id , $content->cat_id ); ?>><?php echo strip_tags( $cat->cat_name ); ?></option>
														<?php endforeach; ?>
													<?php endif; ?>
												</select></p>
											</div>
											<div class="on">
												<?php echo strip_tags( $content->option_name ); ?>
											</div>
										</td>
										<td class="option_value">
											<div class="off">
												<textarea name="data[<?php echo $field; ?>][option_value]" rows="10" cols="25"><?php echo stripslashes( $content->option_value ); ?></textarea>
											</div>
											<div class="on">
												<?php echo stripslashes( esc_html( $content->option_value ) ); ?>
											</div>
										</td>
										<td class="template_tag">
											<code>&lt;?php echo get_coppi('<?php echo esc_html( $content->option_name ); ?>'); ?&gt;</code>
										</td>
										<td class="shortcode">
											<code>[coppi key="<?php echo esc_html( $content->option_name); ?>"]</code>
										</td>
										<td class="operation">
											<div class="on">
												<div class="alignleft">
													<a class="edit button-primary" href="javascript:void(0)"><?php _e('Edit'); ?></a>
												</div>
												<div class="alignright">
													<a class="delete button" title="<?php _e( 'Confirm Deletion' ); ?>" href="<?php echo esc_url( add_query_arg( array( "delete" => $content->option_id , '_wpnonce' => wp_create_nonce( $this->Nonce ) ) ) ); ?>"><?php _e('Delete'); ?></a>
												</div>
												<div class="clear"></div>
											</div>
											<div class="off">
												<input type="submit" class="button-primary" name="update" value="<?php _e( 'Save' ); ?>" />
											</div>
										</td>
									</tr>
								</form>
	
							<?php endif; ?>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
<?php

		}

	}

	// SetList
	function wp_ajax_coppi_get_donation_toggle() {
		echo get_option( $this->Record["donate_width"] );
		die();
	}

	// SetList
	function wp_ajax_coppi_set_donation_toggle() {
		update_option( $this->Record["donate_width"] , intval( $_POST["f"] ) );
		die();
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
	function donate_check() {
		$Update = $this->update_validate();
		if( !empty( $Update ) && check_admin_referer( $this->Nonce ) ) {

			if( !empty( $_POST["donate_key"] ) ) {
				$SubmitKey = md5( strip_tags( $_POST["donate_key"] ) );
				if( $this->DonateKey == $SubmitKey ) {
					update_option( $this->Record["donate"] , $SubmitKey );
					$this->Msg .= '<div class="updated"><p><strong>' . __( 'Thank you for your donation.' , $this->ltd_p ) . '</strong></p></div>';
				}
			}

		}
	}

	// DataUpdate
	function update_memo() {
		$Update = $this->update_validate();
		if( !empty( $Update ) && check_admin_referer( $this->Nonce ) ) {

			if( isset( $_POST["memo"] ) ) {
				$Memo = $_POST["memo"];
				update_option( $this->Record["memo"] , $Memo );
				$this->Msg .= '<div class="updated"><p><strong>' . __( 'Settings saved to memo.' , $this->ltd ) . '</strong></p></div>';
			}

		}
	}

	// DataUpdate
	function update() {
		global $wpdb;

		$Update = $this->update_validate();
		if( !empty( $Update ) && check_admin_referer( $this->Nonce ) ) {

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
				$dup_query = $wpdb->get_var( "SELECT * FROM " . $this->Table["option"] . " WHERE `option_name` LIKE '" . $checkkey . "'" );
				if( !empty( $dup_query ) && $dup_query != $checkid ) {
					$this->Duplicated = true;
				}

				$type = false;

				// create
				if( !empty( $_POST["data"]["create"] ) ) {

					$tmpK = strip_tags( $_POST["data"]["create"]["option_name"] );
					$tmpV = $_POST["data"]["create"]["option_value"];
					$cat = intval( $_POST["data"]["create"]["cat_id"] );
					$date = date( 'Y-m-d H:i:s' );
					$Update = array( "option_name" => $tmpK , "option_value" => $tmpV , "cat_id" => $cat , "create_date" => $date );
					$type = 'create';

				}

				// update
				if( !empty( $_POST["data"]["update"] ) ) {

					$tmpK = strip_tags( $_POST["data"]["update"]["option_name"] );
					$tmpV = $_POST["data"]["update"]["option_value"];
					$cat = intval( $_POST["data"]["update"]["cat_id"] );
					$tmpID = strip_tags( $_POST["data"]["update"]["option_id"] );
					$Update = array( "option_name" => $tmpK , "option_value" => $tmpV , "cat_id" => $cat , "option_id" => $tmpID );
					$type = 'update';

				}

				if( $this->Duplicated == false ) {
					
					if( $type == 'create' ) {
						$wpdb->query(
							"INSERT INTO `" . $this->Table["option"] . "` (`option_id`, `option_name`, `option_value`, `cat_id`, `create_date`) VALUES (NULL, '" . join( "','" , $Update ) . "');"
						);
					} elseif( $type == 'update' ) {
						$wpdb->query(
							"UPDATE " . $this->Table["option"] . " SET " .
							"option_name = '" . $Update["option_name"] . "'," .
							"option_value = '" . $Update["option_value"] . "'," .
							"cat_id = '" . $Update["cat_id"] . "' WHERE option_id = " . $Update["option_id"]
						);
					}
					$this->Msg .= '<div class="updated"><p><strong>' . __( 'Settings saved.' ) . '</strong></p></div>';
				} else {
					$this->Msg .= '<div class="error"><p><strong>' . __( '"Option name" is duplicated.' , $this->ltd ) . '</strong></p></div>';
				}

			}
		}
	}

	// DataUpdate
	function update_cat() {
		global $wpdb;

		$Update = $this->update_validate();
		if( !empty( $Update ) && check_admin_referer( $this->Nonce ) ) {

			if( !empty( $_POST["data"] ) ) {

				// duplicate check
				$checkkey = "";
				$checkid = "";
				if( !empty( $_POST["data"]["create_cat"]["cat_name"] ) ) {
					$checkkey = strip_tags( $_POST["data"]["create_cat"]["cat_name"] );
				} elseif( !empty( $_POST["data"]["update_cat"]["cat_name"] ) ) {
					$checkkey = strip_tags( $_POST["data"]["update_cat"]["cat_current_name"] );
					$checkid = $_POST["data"]["update_cat"]["cat_id"];
				}
				
				$dup_query = $wpdb->get_var( "SELECT * FROM " . $this->Table["cat"] . " WHERE `cat_name` = '" . $checkkey . "'" );
				if( !empty( $dup_query ) && $dup_query != $checkid ) {
					$this->DuplicatedCat = true;
				}

				$type = false;

				// create
				if( !empty( $_POST["data"]["create_cat"] ) ) {

					$date = date( 'Y-m-d H:i:s' );
					$Update = array( "cat_name" => $checkkey , "create_date" => $date );
					$type = 'create';

				}

				// update
				if( !empty( $_POST["data"]["update_cat"] ) ) {

					$tmpK = strip_tags( $_POST["data"]["update_cat"]["cat_name"] );
					$tmpV = strip_tags( $_POST["data"]["update_cat"]["cat_id"] );
					$Update = array( "cat_name" => $tmpK , "cat_id" => $tmpV );
					$type = 'update';

				}

				if( $this->DuplicatedCat == false ) {
					if( $type == 'create' ) {
						$wpdb->query(
							"INSERT INTO `" . $this->Table["cat"] . "` (`cat_id`, `cat_name`, `create_date`) VALUES (NULL, '" . join( "','" , $Update ) . "');"
						);
					} elseif( $type == 'update' ) {
						$wpdb->query(
							"UPDATE " . $this->Table["cat"] . " SET cat_name = '" . $Update["cat_name"] . "' WHERE cat_id = " . $Update["cat_id"]
						);
					}
					$this->Msg .= '<div class="updated"><p><strong>' . __( 'Settings saved.' ) . '</strong></p></div>';
				} else {
					$this->Msg .= '<div class="error"><p><strong>' . __( '"Category name" is duplicated.' , $this->ltd ) . '</strong></p></div>';
				}

			}
		}
	}

	// DataUpdate
	function delete() {

		if( check_admin_referer( $this->Nonce ) ) {
			global $wpdb;
	
			$id = intval( $_GET["delete"] );
			$wpdb->query( "DELETE FROM " . $this->Table["option"] . " WHERE option_id = " . $id );
			$this->Msg .= '<div class="updated"><p><strong>' . __( 'Settings saved.' ) . '</strong></p></div>';
		}

	}

	// DataUpdate
	function delete_cat() {

		if( check_admin_referer( $this->Nonce ) ) {
			global $wpdb;

			$id = intval( $_GET["delete_cat"] );
			$wpdb->query( "DELETE FROM " . $this->Table["cat"] . " WHERE cat_id = " . $id );
			$this->Msg .= '<div class="updated"><p><strong>' . __( 'Settings saved.' ) . '</strong></p></div>';
		}

	}

	// DataUpdate
	function update_bulk() {
		if( check_admin_referer( $this->Nonce ) ) {

			global $wpdb;

			$type = strip_tags( $_POST["bulk"] );
			if( $type == 'change_cat' ) {
				$cat = intval( $_POST["to"] );

				$sql = "UPDATE " . $this->Table["option"] . " SET cat_id = '" . $cat . "' WHERE ";
				foreach( $_POST["data"]["option_id"] as $k => $option_id ) {
					if( $k != 0 ) {
						$sql .= " OR ";
					}
					$sql .= "option_id = '" . intval( $option_id ) . "'";
				}

			} elseif( $type == 'delete' ) {

				$sql = "DELETE FROM " . $this->Table["option"] . " WHERE ";
				foreach( $_POST["data"]["option_id"] as $k => $option_id ) {
					if( $k != 0 ) {
						$sql .= " OR ";
					}
					$sql .= "option_id = '" . intval( $option_id ) . "'";
				}

			}
			
			if( !empty( $sql ) ) {
				$wpdb->query( $sql );
				$this->Msg .= '<div class="updated"><p><strong>' . __( 'Settings saved.' ) . '</strong></p></div>';
			}

		}
	}

}

$coppi = new Custom_Options_Plus_Post_In();






// Shortcode
function coppi_shortcode( $atts ) {
	$ret = '';
	
	if( !empty( $atts["key"] ) ) {
		global $coppi;
		$ret = $coppi->get_data( $atts["key"] );
	}
	
	return $ret;
}
add_shortcode('coppi', 'coppi_shortcode');



// Template
function get_coppi( $key = '' ) {
	$ret = '';
	
	if( !empty( $key ) ) {
		global $coppi;
		$ret = $coppi->get_data( $key );
	}
	
	return $ret;
}


?>