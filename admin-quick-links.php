<?php 
/**
 * Plugin Name: Admin Quick Links
 * -Plugin URI: https://aniomalia.com/plugins/admin-quick-links/
 * Author: Aniomalia
 * Author URI: https://aniomalia.com/
 * Description: Set up a menu for quick links you need frequently but aren't easily accessible.
 * Version: 1.0
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * 
 * Features
 * 
 */

function aql_setup_menu_location(){
    register_nav_menus( array(
        'admin_quick_links' => __( 'Admin Quick Links', 'aql' ),
    ) );
}
add_action( 'after_setup_theme', 'aql_setup_menu_location', 0 );

function aql_assets() {
    $plugin_url = plugin_dir_url( __FILE__ );
    wp_enqueue_style( 'aql-style',  $plugin_url . 'css/aql-style.css');
    wp_enqueue_script( 'aql-script',  $plugin_url . 'js/aql-script.js', array('jquery'), '');
}
add_action( 'admin_enqueue_scripts', 'aql_assets' );

include( plugin_dir_path( __FILE__ ) . 'includes/aql-output.php');

class AdminQuickLinks {
	private $admin_quick_links_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_quick_links_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'admin_quick_links_page_init' ) );
	}

	public function admin_quick_links_add_plugin_page() {
		add_options_page(
			'Admin Quick Links', // page_title
			'Admin Quick Links', // menu_title
			'manage_options', // capability
			'admin-quick-links', // menu_slug
			array( $this, 'admin_quick_links_create_admin_page' ) // function
		);
	}

	public function admin_quick_links_create_admin_page() {
		$this->admin_quick_links_options = get_option( 'admin_quick_links_option_name' ); ?>

		<div class="wrap">
			<h2>Admin Quick Links</h2>
			<p>Build your menu in the <a href="<?php echo get_home_url() . '/wp-admin/nav-menus.php'; ?>">Appearance › Menus</a> page. Set the "Display location" option to "Admin Quick Links".</p>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'admin_quick_links_option_group' );
					do_settings_sections( 'admin-quick-links-admin' );
					submit_button();
				?>
			</form>
		</div>
	<?php }

	public function admin_quick_links_page_init() {
		register_setting(
			'admin_quick_links_option_group', // option_group
			'admin_quick_links_option_name', // option_name
			array( $this, 'admin_quick_links_sanitize' ) // sanitize_callback
		);

		add_settings_section(
			'admin_quick_links_setting_section', // id
			'Settings', // title
			array( $this, 'admin_quick_links_section_info' ), // callback
			'admin-quick-links-admin' // page
		);

		add_settings_field(
			'enable_disable_0', // id
			'Enable / Disable', // title
			array( $this, 'enable_disable_0_callback' ), // callback
			'admin-quick-links-admin', // page
			'admin_quick_links_setting_section' // section
		);

		add_settings_field(
			'aql_select_size_2', // id
			'Size', // title
			array( $this, 'aql_select_size_2_callback' ), // callback
			'admin-quick-links-admin', // page
			'admin_quick_links_setting_section' // section
		);
	}

	public function admin_quick_links_sanitize($input) {
		$sanitary_values = array();
		if ( isset( $input['enable_disable_0'] ) ) {
			$sanitary_values['enable_disable_0'] = $input['enable_disable_0'];
		}

		if ( isset( $input['aql_select_size_2'] ) ) {
			$sanitary_values['aql_select_size_2'] = $input['aql_select_size_2'];
		}

		return $sanitary_values;
	}

	public function admin_quick_links_section_info() {
		
	}

	public function enable_disable_0_callback() {
		printf(
			'<input type="checkbox" name="admin_quick_links_option_name[enable_disable_0]" id="enable_disable_0" value="enable_disable_0" %s>',
			( isset( $this->admin_quick_links_options['enable_disable_0'] ) && $this->admin_quick_links_options['enable_disable_0'] === 'enable_disable_0' ) ? 'checked' : ''
		);
	}

	public function aql_select_size_2_callback() {
		?> <select name="admin_quick_links_option_name[aql_select_size_2]" id="aql_select_size_2">
			<?php $selected = (isset( $this->admin_quick_links_options['aql_select_size_2'] ) && $this->admin_quick_links_options['aql_select_size_2'] === 'aql-size-small') ? 'selected' : '' ; ?>
			<option value="aql-size-small" <?php echo $selected; ?>>Small</option>
			<?php $selected = (isset( $this->admin_quick_links_options['aql_select_size_2'] ) && $this->admin_quick_links_options['aql_select_size_2'] === 'aql-size-medium') ? 'selected' : '' ; ?>
			<option value="aql-size-medium" <?php echo $selected; ?>>Medium</option>
			<?php $selected = (isset( $this->admin_quick_links_options['aql_select_size_2'] ) && $this->admin_quick_links_options['aql_select_size_2'] === 'aql-size-large') ? 'selected' : '' ; ?>
			<option value="aql-size-large" <?php echo $selected; ?>>Large</option>
		</select> <?php
	}

}
if ( is_admin() )
	$admin_quick_links = new AdminQuickLinks();
