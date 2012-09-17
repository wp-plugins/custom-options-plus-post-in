<?php
/*
Plugin Name: Custom Options Plus Post In
Description: Add the value of the option. and Available for use in the post article.
Plugin URI: http://gqevu6bsiz.chicappa.jp
Version: 1.0.1
Author: gqevu6bsiz
Author URI: http://gqevu6bsiz.chicappa.jp/author/admin/
Text Domain: custom_options_plus_post_in
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

load_plugin_textdomain('custom_options_plus_post_in', false, basename(dirname(__FILE__)).'/languages');

define ('COPPI_VER', '1.0.1');
define ('COPPI_PLUGIN_NAME', __('Customs Option', 'custom_options_plus_post_in'));
define ('COPPI_SHORT_NAME', 'coppi');
define ('COPPI_MANAGE_URL', admin_url('options-general.php').'?page=coppi');
define ('COPPI_RECORD_NAME', 'coppi');
define ('COPPI_PLUGIN_DIR', WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)).'/');
?>
<?php
function coppi_add_menu() {
	// add menu
	add_options_page('custom_options_plus_post_in (coppi)', COPPI_PLUGIN_NAME.'(coppi)', 'administrator', 'coppi', 'coppi_setting');

	// plugin links
	add_filter('plugin_action_links', 'coppi_plugin_setting', 10, 2);
}



// plugin setup
function coppi_plugin_setting($links, $file) {
	if(plugin_basename(__FILE__) == $file) {
		$settings_link = '<a href="'.COPPI_MANAGE_URL.'">'.__('Settings').'</a>'; 
		array_unshift( $links, $settings_link );
	}
	return $links;
}
add_action('admin_menu', 'coppi_add_menu');



// setting
function coppi_setting() {
	$UPFN = 'sett';
	$duplicated = false;
	$Msg = '';
	
	if(isset($_GET["delete"])) {
		// delete
		$id = $_GET["delete"];
		$Data = get_option(COPPI_RECORD_NAME);
		unset($Data[$id]);
		update_option(COPPI_RECORD_NAME, $Data);
		echo '<div class="updated"><p><strong>'.__('Settings saved.').'</strong></p></div>';
	} else if(!empty($_POST[$UPFN])) {
		// update
		if($_POST[$UPFN] == 'Y') {
			unset($_POST[$UPFN]);

			$Update = array();
			if(!empty($_POST["update"])) {
				foreach ($_POST["update"] as $key => $val) {
					$Update[$key] = array("key" => $val["key"], "val" => $val["val"]);
				}
			}
			if(!empty($_POST["create"]) && !empty($_POST["create"]["key"])) {
				if(!empty($Update)) {
					foreach($Update as $key => $val) {
						if($val["key"] == strip_tags($_POST["create"]["key"])) {
							$duplicated = true;
						}
					}
				}
				if($duplicated == false) {
					$Update[] = array("key" => strip_tags($_POST["create"]["key"]), "val" => $_POST["create"]["value"]);
				}
			}
			
			if($duplicated == false) {
				update_option(COPPI_RECORD_NAME, $Update);
				$Msg = '<div class="updated"><p><strong>'.__('Settings saved.').'</strong></p></div>';
			} else {
				$Msg = '<div class="error"><p><strong>'.__('Option name is duplicated.', 'custom_options_plus_post_in').'</strong></p></div>';
			}
		}
	}
	
	// get data
	$Data = get_option(COPPI_RECORD_NAME);
	
	// include js css
	$ReadedJs = array('jquery', 'thickbox');
	wp_enqueue_script('coppy', COPPI_PLUGIN_DIR.dirname(plugin_basename(__FILE__)).'.js', $ReadedJs, COPPI_VER);
	wp_enqueue_style('thickbox');
	wp_enqueue_style('coppy', COPPI_PLUGIN_DIR.dirname(plugin_basename(__FILE__)).'.css', array(), COPPI_VER);
?>
<div class="wrap">
	<div class="icon32" id="icon-options-general"></div>
	<h2><?php echo COPPI_PLUGIN_NAME; ?></h2>
	<?php echo $Msg; ?>
		
	<form id="coppi_form" method="post" action="<?php echo COPPI_MANAGE_URL; ?>">
		<input type="hidden" name="<?php echo $UPFN; ?>" value="Y">
		<?php wp_nonce_field(-1, '_wpnonce', false); ?>
		<div id="create">
			<h3><?php _e('Create an option', 'custom_options_plus_post_in'); ?></h3>
			<p><?php _e('Japanese available use.', 'custom_options_plus_post_in'); ?></p>
			<?php $type = 'create'; ?>
			<table class="form-table">
				<tbody>
					<tr>
						<th><label for="<?php echo $type; ?>_key"><?php _e('Option Name', 'custom_options_plus_post_in'); ?></label> *</th>
						<td>
							<?php $val = ''; if($duplicated == true) { $val = strip_tags($_POST["create"]["key"]); } ?>
							<input type="text" class="regular-text" id="<?php echo $type; ?>_key" name="<?php echo $type; ?>[key]" value="<?php echo $val; ?>">
						</td>
					</tr>
					<tr>
						<th><label for="<?php echo $type; ?>_value"><?php _e('Option Value', 'custom_options_plus_post_in'); ?></label></th>
						<td>
							<?php $val = ''; if($duplicated == true) { $val = stripslashes($_POST["create"]["value"]); } ?>
							<textarea rows="5" cols="30" name="<?php echo $type; ?>[value]" id="<?php echo $type; ?>_value"><?php echo $val; ?></textarea><br />
							<?php _e('Usable Javascript and Html tag.', 'custom_options_plus_post_in'); ?>
						</td>
					</tr>
				</tbody>
			</table>
			<p class="submit">
				<input type="button" class="button-primary" value="<?php _e('Save'); ?>" />
			</p>
		</div>
		
		<div id="update">
			<h3><?php _e('List of options that you created', 'custom_options_plus_post_in'); ?></h3>
			<?php if(!empty($Data)) : ?>
				<?php $type = 'update'; ?>
	
				<table cellspacing="0" class="widefat fixed">
					<thead>
						<tr>
							<th><?php _e('Option Name', 'custom_options_plus_post_in'); ?></th>
							<th><?php _e('Option Value', 'custom_options_plus_post_in'); ?></th>
							<th class="template-tag"><?php _e('Tag of the template', 'custom_options_plus_post_in'); ?></th>
							<th class="short-tag"><?php _e('Tag of the post', 'custom_options_plus_post_in'); ?></th>
							<th class="operation">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($Data as $key => $content) : ?>
							<tr id="tr_<?php echo $key; ?>">
								<td class="key">
									<input type="text" value="<?php echo strip_tags($content["key"]); ?>" name="<?php echo $type; ?>[<?php echo $key; ?>][key]">
									<span><?php echo strip_tags($content["key"]); ?></span>
								</td>
								<td class="val">
									<textarea rows="10" cols="25" name="<?php echo $type; ?>[<?php echo $key; ?>][val]"><?php echo stripslashes($content["val"]); ?></textarea>
									<span><?php echo stripslashes(esc_html($content["val"])); ?></span>
								</td>
								<td class="template-tag">
									<code>&lt;?php echo get_coppi('<?php echo esc_html($content["key"]); ?>'); ?&gt;</code>
								</td>
								<td class="short-tag">
									<code>[<?php echo COPPI_SHORT_NAME; ?> key="<?php echo esc_html($content["key"]); ?>"]</code>
								</td>
								<td class="operation">
									<span>
										<a class="edit" href="javascript:void(0)"><?php _e('Edit'); ?></a>
										&nbsp;|&nbsp;
										<a class="delete" href="<?php echo COPPI_MANAGE_URL; ?>&delete=<?php echo $key; ?>"><?php _e('Delete'); ?></a>
									</span>
									<p class="submit">
										<input type="button" class="button-primary" value="<?php _e('Save'); ?>" />
									</p>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>

				<div id="Confirm">
					<div id="ConfirmSt">
						<p>&nbsp;</p>
						<a class="button-secondary" id="cancelbtn" href="javascript:void(0);"><?php _e('Cancel'); ?></a>
						<a class="button-secondary" id="deletebtn" href=""><?php _e('Continue'); ?></a>
					</div>
				</div>

<script type="text/javascript">
jQuery(document).ready(function($) {
	// delete
	$("a.delete").click(function() {
		var $DelUrl = $(this).attr("href");
		var $DelName = $(this).parent().parent().parent().children('td.key').children('span').text();
		var $ConfDlg = $("#Confirm #ConfirmSt");
		$ConfDlg.children("a#deletebtn").attr("href", $DelUrl);
		$ConfDlg.children("p").html('<?php echo sprintf( __( 'You are about to delete <strong>%s</strong>.' ), '' ); ?>');
		$ConfDlg.children("p").children("strong").text($DelName);
		
		tb_show('<?php _e('Confirm Deletion'); ?>', '#TB_inline?height=100&width=240&inlineId=Confirm', '');
		return false;
	});
	
	$("a#cancelbtn").click(function() {
		tb_remove();
	});
});
</script>

			<?php else : ?>

				<p><?php _e('Not created option.', 'custom_options_plus_post_in'); ?></p>

			<?php endif; ?>
		</div>

	</form>
</div>
<?php
}



// Shortcode
function coppi_shortcode($atts) {
	$ret = '';
	
	if(!empty($atts["key"])) {
		$Data = get_option(COPPI_RECORD_NAME);
		if(!empty($Data)) {
			foreach($Data as $val) {
				if($atts["key"] == $val["key"]) {
					$ret = stripslashes($val["val"]);
				}
			}
		}
	}
	
	return $ret;
}
add_shortcode('coppi', 'coppi_shortcode');



// Template
function get_coppi($key = '') {
	$ret = '';
	
	if(!empty($key)) {
		$Data = get_option(COPPI_RECORD_NAME);
		if(!empty($Data)) {
			foreach($Data as $val) {
				if($key == $val["key"]) {
					$ret = stripslashes($val["val"]);
				}
			}
		}
	}
	
	return $ret;
}
?>