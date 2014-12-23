<?php

if ( !class_exists( 'Coppi_Helper' ) ) :

final class Coppi_Helper
{
	
	public function get_action_link( $remove_query = array() )
	{
		
		global $coppi;

		if( empty( $remove_query ) ) {
			
			if( $coppi->Site->is_multisite ) {
				
				$url = $coppi->Plugin->url_admin_network;
				
			} else {
				
				$url = $coppi->Plugin->url_admin;
				
			}
			
		} else {
			
			$url = remove_query_arg( array( $remove_query ) );
			
		}
		
		return esc_url( $url );
		
	}
	
	public function is_correctly_form( $post_data = array() )
	{
		
		global $coppi;
		
		if( empty( $post_data ) )
			return false;
		
		if( empty( $post_data[$coppi->Form->field] ) )
			return false;

		$form_field = strip_tags( $post_data[$coppi->Form->field] );

		if( $form_field !== $coppi->Form->UPFN )
			return false;

		return true;
		
	}
	
}

endif;
