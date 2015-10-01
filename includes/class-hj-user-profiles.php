<?php

/**
 * The file that defines the core plugin class
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://www.midwestdigitalmarketing.com
 * @since      1.0.0
 * @package    hj_User_Profiles
 * @subpackage hj_User_Profiles/includes
 */

/**
 * The core plugin class.
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    hj_User_Profiles
 * @subpackage hj_User_Profiles/includes
 * @author     Midwest Digital Marketing <bob.moore@midwestdigitalmarketing.com>
 */
class hj_User_Profiles {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     * @since    1.0.0
     * @access   protected
     * @var      hj_User_Profiles_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     * @since    1.0.0
     */
    public function __construct() {
        $this->plugin_name = 'hj-user-profiles';
        $this->version = '1.0.0';
        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     * Include the following files that make up the plugin:
     * - hj_User_Profiles_Loader. Orchestrates the hooks of the plugin.
     * - hj_User_Profiles_i18n. Defines internationalization functionality.
     * - hj_User_Profiles_Admin. Defines all hooks for the admin area.
     * - hj_User_Profiles_Public. Defines all hooks for the public side of the site.
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {
        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hj-user-profiles-loader.php';
        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hj-user-profiles-i18n.php';
        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-hj-user-profiles-admin.php';
        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-hj-user-profiles-public.php';
        $this->loader = new hj_User_Profiles_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     * Uses the hj_User_Profiles_i18n class in order to set the domain and to register the hook
     * with WordPress.
     * @since    1.0.0
     * @access   private
     */
    private function set_locale() {
        $plugin_i18n = new hj_User_Profiles_i18n();
        $plugin_i18n->set_domain( $this->get_plugin_name() );
        $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {
        $plugin_admin = new hj_User_Profiles_Admin( $this->get_plugin_name(), $this->get_version() );
        $this->loader->add_action( 'init', $plugin_admin, 'init_settings_defaults' );
        $this->loader->add_action( 'init', $plugin_admin, 'init_settings' );
        $this->loader->add_action( 'admin_init', $plugin_admin, 'register_settings' );
        $this->loader->add_action( 'admin_init', $plugin_admin, 'update_image_size' );
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_settings_page' );
        $this->loader->add_action( 'show_user_profile', $plugin_admin, 'user_profile_meta' );
        $this->loader->add_action( 'edit_user_profile', $plugin_admin, 'user_profile_meta' );
        $this->loader->add_action( 'personal_options_update', $plugin_admin, 'save_user_profile_meta' );
        $this->loader->add_action( 'edit_user_profile_update', $plugin_admin, 'save_user_profile_meta' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() {
        $plugin_public = new hj_User_Profiles_Public( $this->get_plugin_name(), $this->get_version() );
        $this->loader->add_action( 'init', $plugin_public, 'register_shortcodes' );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     * @since    1.0.0
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     * @since     1.0.0
     * @return    hj_User_Profiles_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }
} // end class
