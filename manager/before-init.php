<?php

if ( !class_exists( 'Coppi_Manager_Before_Init' ) ) :

final class Coppi_Manager_Before_Init
{

	public function __construct()
	{
		
		$this->init();

	}

	private function init()
	{
		
		global $coppi;
		
		if( !$coppi->Env->is_admin or !$coppi->User->is_manager )
			return false;

		if( !$coppi->Env->is_ajax ) {
			
			$base_plugin = trailingslashit( $coppi->Plugin->plugin_slug ) . $coppi->Plugin->plugin_slug . '.php';

			if( $coppi->Site->is_multisite ) {
				
				add_action( "network_admin_plugin_action_links_$base_plugin" , array( $this , 'plugin_action_links' ) );
					
			} else {
				
				add_action( "plugin_action_links_$base_plugin" , array( $this , 'plugin_action_links' ) );
					
			}

		}

	}
	
	public function plugin_action_links( $links )
	{
		
		global $coppi;
		
		if( $coppi->Site->is_multisite ) {
			
			$link = $coppi->Plugin->url_admin_network;
			
		} else {
			
			$link = $coppi->Plugin->url_admin;
			
		}

		array_unshift( $links , sprintf( '<a href="%1$s">%2$s</a>' , $link , __( 'Settings' ) ) );
		
		return $links;

	}
	
}

endif;
