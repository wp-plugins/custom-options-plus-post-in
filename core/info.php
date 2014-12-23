<?php

if ( !class_exists( 'Coppi_Info' ) ) :

final class Coppi_Info
{

	private $donate_key;
	private $author_url;
	private $donate_record;
	private $get_donate_key;

	public $forum_url;
	public $review_url;
	public $profile_url;

	private $do_screen_slug;
	
	public function __construct()
	{
		
		global $coppi;

		$this->donate_key = 'd77aec9bc89d445fd54b4c988d090f03';
		$this->author_url = 'http://gqevu6bsiz.chicappa.jp/';
		$this->donate_record = $coppi->Plugin->ltd . '_donated';
		$this->get_donate_key = $this->get_donate_key();

		$this->forum_url = 'http://wordpress.org/support/plugin/' . $coppi->Plugin->plugin_slug;
		$this->review_url = 'http://wordpress.org/support/view/plugin-reviews/' . $coppi->Plugin->plugin_slug;
		$this->profile_url = 'http://profiles.wordpress.org/gqevu6bsiz';
		
		$this->do_screen_slug = $coppi->Plugin->main_slug;
		
		add_action( 'admin_init' , array( $this , 'admin_init' ) );

	}

	private function get_donate_key()
	{
		
		global $coppi;

		if( $coppi->Site->is_multisite ) {

			$get_donate_key = get_option( $this->donate_record );

		} else {

			$get_donate_key = get_option( $this->donate_record );

		}
		
		return $get_donate_key;

	}

	public function is_donated()
	{
		
		if( !$this->validate_donate_key( $this->get_donate_key ) )
			return false;
		
		return true;
		
	}

	private function validate_donate_key( $donate_key = false )
	{
		
		if( empty( $donate_key ) )
			return false;
		
		$donate_key = strip_tags( $donate_key );
		
		if( $this->donate_key != $donate_key )
			return false;
		
		return true;

	}

	public function get_add_class()
	{
		global $coppi;

		$class = false;
		
		if( $this->is_donated() )
			$class = 'full-width';
		
		return $class;
		
	}

	public function author_url( $args )
	{
		
		$url = $this->author_url;
		
		if( !empty( $args['translate'] ) ) {
			$url .= 'please-translation/';
		} elseif( !empty( $args['donate'] ) ) {
			$url .= 'please-donation/';
		} elseif( !empty( $args['contact'] ) ) {
			$url .= 'contact-us/';
		}
		
		$url .= $this->get_utm_link( $args );

		return $url;

	}
	
	public function get_utm_link( $args )
	{
		
		global $coppi;

		$url = '?utm_source=' . $args['tp'];
		$url .= '&utm_medium=' . $args['lc'];
		$url .= '&utm_content=' . $coppi->Plugin->ltd;
		$url .= '&utm_campaign=' . str_replace( '.' , '_' , $coppi->ver );

		return $url;

	}
	
	public function get_gravatar_src( $size )
	{
		
		global $coppi;

		$size = intval( $size );
		$img_src = $coppi->Env->schema . 'www.gravatar.com/avatar/7e05137c5a859aa987a809190b979ed4?s=' . $size;

		return $img_src;

	}

	public function version_checked()
	{

		global $coppi;

		$readme = file_get_contents( $coppi->Plugin->dir . 'readme.txt' );
		$items = explode( "\n" , $readme );
		$version_checked = '';

		foreach( $items as $key => $line ) {

			if( strpos( $line , 'Requires at least: ' ) !== false ) {

				$version_checked .= str_replace( 'Requires at least: ' , '' ,  $line );
				$version_checked .= ' - ';

			} elseif( strpos( $line , 'Tested up to: ' ) !== false ) {

				$version_checked .= str_replace( 'Tested up to: ' , '' ,  $line );
				break;

			}

		}
		
		return $version_checked;
		
	}
	
	public function admin_init()
	{

		global $plugin_page;
		global $coppi;

		if( empty( $this->do_screen_slug ) or $plugin_page != $this->do_screen_slug )
			return false;

		$this->post_data();
		
		if( $coppi->Site->is_multisite ) {
			
			add_action( 'network_admin_notices' , array( $this , 'update_notice' ) );
			
		} else {
			
			add_action( 'admin_notices' , array( $this , 'update_notice' ) );
			
		}

	}

	private function post_data()
	{
		
		global $coppi;
		
		if( !$coppi->Helper->is_correctly_form( $_POST ) )
			return false;
		
		if( empty( $_POST[$coppi->Form->nonce . 'update_donate'] ) )
			return false;

		if( !check_admin_referer( $coppi->Form->nonce . 'update_donate' , $coppi->Form->nonce . 'update_donate' ) )
			return false;

		$this->update();
		
	}
	
	private function update()
	{
		
		global $coppi;

		if( empty( $_POST['donate_key'] ) )
			return false;
		
		$donate_key = md5( strip_tags( $_POST['donate_key'] ) );
		
		if( !$this->validate_donate_key( $donate_key ) )
			return false;

		if( $coppi->Site->is_multisite ) {
						
			update_option( $this->donate_record , $donate_key );
						
		} else {
			
			update_option( $this->donate_record , $donate_key );

		}

		wp_redirect( add_query_arg( array( $coppi->Other->msg_notice => 'update_donated' ) , $coppi->Helper->get_action_link() ) );
		exit;

	}

	public function update_notice()
	{
		
		global $coppi;

		if( empty( $_GET ) or empty( $_GET[$coppi->Other->msg_notice] ) )
			return false;
		
		$this->update_notice = strip_tags( $_GET[$coppi->Other->msg_notice] );
		
		if( empty( $this->update_notice ) )
			return false;
		
		if( $this->update_notice == 'update_donated' )
			printf( '<div class="updated"><p><strong>%s</strong></p></div>' , __( 'Thank you for your donation.' , $coppi->Plugin->ltd ) );

	}
	
}

endif;
