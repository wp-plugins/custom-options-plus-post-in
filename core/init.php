<?php

if ( !class_exists( 'Coppi_Init' ) ) :

final class Coppi_Init
{

	private $plugin_slug = 'custom-options-plus-post-in';
	private $main_slug = 'coppi';
	private $ltd = 'coppi';

	private $framework_ver = '1.0';

	private $Plugin;
	private $Form;
	private $Other;
	private $Tables;
	private $Records;
	private $Site;
	private $Env;
	private $User;
	private $ThirdParty;

    public function __construct()
	{

		$this->Plugin     = new stdClass;
		$this->Form       = new stdClass;
		$this->Other      = new stdClass;
		$this->Tables     = new stdClass;
		$this->Records    = new stdClass;
		$this->Site       = new stdClass;
		$this->Env        = new stdClass;
		$this->User       = new stdClass;
		$this->ThirdParty = new stdClass;
		
		add_action( 'init' , array( $this , 'init' ) );
		add_action( $this->ltd . '_init' , array( $this , 'setup_manager' ) , 20 );

    }
	
	public function init()
	{
		
		$this->setup_Plugin();
		$this->setup_Form();
		$this->setup_Other();
		$this->setup_Tables();
		$this->setup_Records();
		$this->setup_Site();
		$this->setup_Env();
		$this->setup_User();
		$this->setup_Third_Party();
		$this->complete();

	}
	
	private function setup_Plugin()
	{
		
		$this->Plugin->dir                = trailingslashit( dirname( dirname( __FILE__ ) ) );
		$this->Plugin->dir_core           = trailingslashit( dirname( __FILE__ ) );
		$this->Plugin->dir_model          = $this->Plugin->dir . trailingslashit( 'model' );
		$this->Plugin->dir_manager        = $this->Plugin->dir . trailingslashit( 'manager' );
		$this->Plugin->dir_admin          = $this->Plugin->dir . trailingslashit( 'admin' );
		$this->Plugin->dir_front          = $this->Plugin->dir . trailingslashit( 'front' );

		$this->Plugin->url                = plugin_dir_url( dirname( __FILE__ ) );
		$this->Plugin->url_admin_network  = network_admin_url( 'admin.php?page=' . $this->main_slug );
		$this->Plugin->url_admin          = admin_url( 'options-general.php?page=' . $this->main_slug );

		$this->Plugin->capability         = 'manage_options';
		
	}

	private function setup_Form()
	{
		
		$this->Form->UPFN  = 'Y';
		$this->Form->field = $this->ltd . '_settings';
		$this->Form->nonce = $this->ltd . '_';

	}

	private function setup_Other()
	{
		
		$this->Other->msg_notice = sprintf( '%s_msg' , $this->ltd );
		
	}

	private function setup_Tables()
	{
		
		$this->Tables->option = $this->ltd;
		$this->Tables->cat = $this->ltd . '_cat';
		
	}

	private function setup_Records()
	{
		
		$this->Records->db_ver = $this->ltd . '_db_ver';
		$this->Records->memo = $this->ltd . '_memo';
		
	}

	private function setup_Site()
	{
		
		$this->Site->is_multisite = is_multisite();
		$this->Site->blog_id = get_current_blog_id();

		$this->Site->main_blog = false;

		if( $this->Site->blog_id == 1 )
			$this->Site->main_blog = true;
		
	}

	private function setup_Env()
	{
		
		$this->Env->is_admin         = is_admin();
		$this->Env->is_network_admin = is_network_admin();
		$this->Env->is_ajax          = false;

		if( defined( 'DOING_AJAX' ) )
			$this->Env->is_ajax = true;
			
		$this->Env->schema = is_ssl() ? 'https://' : 'http://';

	}
	
	private function setup_User()
	{
		
		$this->User->user_login         = is_user_logged_in();
		$this->User->user_role          = false;
		$this->User->user_id            = false;
		$this->User->superadmin         = false;
		$this->User->is_manager         = false;

		if( !$this->User->user_login )
			return false;

		$this->User->user_id = get_current_user_id();

		$User = wp_get_current_user();
	
		if( !empty( $User->roles ) ) {
	
			$user_roles = $User->roles;

			foreach( $user_roles as $role ) {
	
				$this->User->user_role = $role;
				break;
	
			}
	
		}

		if( $this->Site->is_multisite )
			$this->User->superadmin = is_super_admin();

	}
	
	private function setup_Third_Party()
	{
		
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		
		$check_plugins = array();
		
		if( empty( $check_plugins ) )
			return false;
		
		$plugins = array();

		foreach( $check_plugins as $name => $base_name ) {
			
			if( is_plugin_active( $base_name ) ) {
				
				$plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/' . $base_name );
				$plugins[$name] = array( 'ver' => $plugin_data['Version'] );
				
			}

		}
		
		$this->ThirdParty = (object) $plugins;

	}

	private function complete()
	{
		
		global $coppi;
		
		$this->Plugin->plugin_slug = $this->plugin_slug;
		$this->Plugin->main_slug = $this->main_slug;
		$this->Plugin->ltd = $this->ltd;
		$this->Plugin->framework_ver = $this->framework_ver;

		$coppi->Plugin     = $this->Plugin;
		$coppi->Form       = $this->Form;
		$coppi->Other      = $this->Other;
		$coppi->Tables     = $this->Tables;
		$coppi->Records    = $this->Records;
		$coppi->Site       = $this->Site;
		$coppi->Env        = $this->Env;
		$coppi->User       = $this->User;
		$coppi->ThirdParty = $this->ThirdParty;
		
	}

	public function setup_manager()
	{
		
		global $coppi;
		
		$capability = $this->Plugin->capability;

		if( $coppi->Site->is_multisite )
			$capability = 'manage_network';

		$coppi->Plugin->capability = apply_filters( $coppi->Plugin->ltd . '_capability' , $capability );
		
		if( current_user_can( $coppi->Plugin->capability ) )
			$coppi->User->is_manager = true;

	}

}

new Coppi_Init();

endif;
