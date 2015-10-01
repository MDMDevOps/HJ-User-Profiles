<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.midwestdigitalmarketing.com
 * @since      1.0.0
 * @package    hj_User_Profiles
 * @subpackage hj_User_Profiles/admin
 */

/**
 * The admin-specific functionality of the plugin.
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    hj_User_Profiles
 * @subpackage hj_User_Profiles/admin
 * @author     Midwest Digital Marketing <bob.moore@midwestdigitalmarketing.com>
 */
class hj_User_Profiles_Admin {

    /**
     * The ID of this plugin.
     * @since    1.0.0
     * @access   private
     * @var      string -> The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     * @since    1.0.0
     * @access   private
     * @var      string -> The current version of this plugin.
     */
    private $version;

    /**
     * The Settings Key
     * @since    1.0.0
     * @access   private
     * @var      string -> The settings key to use for updating settings.
     */
    private $settings_key;

    /**
     * The default settings
     * @since    1.0.0
     * @access   private
     * @var      array -> The default settings used
     */
    private $default_settings;

    /**
     * The settings
     * @since    1.0.0
     * @access   private
     * @var      array -> The settings retrieved from the database, and merged with defaults
     */
    private $settings;

    /**
     * Initialize the class and set its properties.
     * @since    1.0.0
     * @param    string -> The name of this plugin.
     * @param    string -> The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->settings_key = $this->plugin_name . '-settings';
    }

    /**
     * Register the stylesheets for the admin area.
     * @since    1.0.0
     */
    public function enqueue_styles() {
        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/hj-user-profiles-admin.css', array(), $this->version, 'all' );
    }

    /**
     * Register the JavaScript for the admin area.
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/hj-user-profiles-admin.js', array( 'jquery' ), $this->version, false );
    }

    /**
     * Add admin page to wordpress menu
     * @since    1.0.0
     */
    public function add_settings_page() {
        add_options_page(
            __( 'User Profile Photo Settings' ), // Page title
            __( 'User Profiles' ), // menu title
            'manage_options', // capabilities
            $this->settings_key, //menu-slug
            array( $this, 'display_settings_page' ) // callback function
        );
    }

    /**
     * Display settings page
     * @since 1.0.0
     */
    public function display_settings_page() {
        include plugin_dir_path( __FILE__ ) . '/partials/hj-user-profiles-admin-display.php';
    }

    /**
     * Default settings
     * @since 1.0.0
     */
    public function init_settings_defaults() {
        $this->default_settings = array(
                'user_photo' => array(
                    'type'    => 'photo-size',
                    'default_img' => esc_url_raw( plugins_url( 'img/default_profile.png', __FILE__ ) ),
                    'label'   => 'User Photo Size',
                    'width'   => '300',
                    'height'  => null
                ),
                'user_thumbnail' => array(
                    'type'    => 'photo-size',
                    'label'   => 'User Thumbnail',
                    'width'   => '125',
                    'height'  => null
                ),
                'avatar_fallback' => array(
                    'type'    => 'checkbox',
                    'label'   => 'Serve Avatar as Fallback',
                    'description' => 'In case the user does not have a photo uploaded or approved, their avatar will be fetched for them.',
                    'checked' => null
                ),
                'override_avatar' => array(
                    'type'    => 'checkbox',
                    'label'   => 'Serve Avatar as Fallback',
                    'description' => "When making calls to get_avatar(), the user's photo will be used instead if it is available.",
                    'checked' => null
                )
            );
        $this->settings = $this->default_settings;
    }

    /**
     * Init (load) the settings from the database and merge with defaults
     * @since 1.0.0
     */
    public function init_settings() {
        if( get_option( $this->settings_key ) ) {
            $settings = get_option( $this->settings_key );
            foreach( $settings as $key => $setting ) {
                $this->settings[$key] = array_merge( $this->default_settings[$key], array_filter( $settings[$key], 'trim' ) );
            }
        } else {
            $this->settings = $this->default_settings;
        }
    }

    /**
     * Setting page description markup
     * @since 1.0.0
     */
    public function settings_page_description() {
        include plugin_dir_path( __FILE__ ) . 'partials/hj-user-profiles-admin-description.php';
    }

    /**
     * Setting page form markup
     * @since 1.0.0
     * @param $args -> array of individual field arguments
     */
    public function settings_page_fields( $args ) {
        include plugin_dir_path( __FILE__ ) . 'partials/hj-user-profiles-settings-fields.php';
    }

    /**
     * Register settings
     * @since 1.0.0
     */
    public function register_settings() {
        // register_setting( $option_group, $option_name, $sanitize_callback );
        register_setting( $this->settings_key, $this->settings_key );
        // Add Section: add_settings_section( $id, $title, $callback, $page )
        add_settings_section( 'hj_user_profile_settings', 'User Profile Settings', array( $this, 'settings_page_description' ), $this->settings_key );
        foreach( $this->settings as $key => $setting ) {
            $setting['key'] = $key;
            // add_settings_field( $id, $title, $callback, $page, $section, $args );
            add_settings_field( $key, $setting['label'], array( $this, 'settings_page_fields' ), $this->settings_key, 'hj_user_profile_settings', $setting );
        }
    }

    /**
     * Update image sizes
     * @since 1.0.0
     */
    public function update_image_size() {
        // Build Options
        $photo_width = $this->settings['user_photo']['width'];
        $photo_height = ( trim( $this->settings['user_photo']['height'] ) != false ) ? $this->settings['user_photo']['height'] : '9999';
        $thumb_width = $this->settings['user_thumbnail']['width'];
        $thumb_height = ( trim( $this->settings['user_thumbnail']['height'] ) != false ) ? $this->settings['user_thumbnail']['height'] : '9999';
        $photo_crop = true;

        // Update sizes
        add_image_size( 'hj_profile_photo', $photo_width, $photo_height, $photo_crop );
        add_image_size( 'hj_profile_thumbnail', $thumb_width, $thumb_height, true );
    }

    /**
     * Add meta boxes on user profile screen
     * @since 1.0.0
     */
    public function user_profile_meta( $user ){
        // Default Options
            $params = array(
                'img_width'       => $this->default_settings['user_photo']['width'], // img width to crop images to
                'default_img_src' => esc_url_raw( plugins_url( 'img/default_profile.png', __FILE__ ) ),  // source of default profile image
                'user_img'        => null, // fully formed <img> tag of users uploaded photo, cropped to img size defined in settings
                'phone'           => null, // direct phone number
                'email'           => null, // direct email
                'linkedin'        => null, // linked in profile URI
                'role'            => null, // role in the company
                'excerpt'         => null, // short profile excerpt
                'description'     => null, // Longer profile description
                'associate'       => null, // boolean, associate status
                'directory'       => null, // boolean, show profile in directory
            );
        // Get Settings from Database
        $settings = get_option( $this->settings_key );
        // Set parameters if settings is available
        if( $settings ) {
            $params = array(
                'img_width'       => ( isset( $settings['user_photo']['width'] ) && trim( $settings['user_photo']['width'] ) != false ) ? esc_attr( $settings['user_photo']['width'] ) : $this->default_settings['user_photo']['width'],
                'default_img_src' => esc_url_raw( plugins_url( 'img/default_profile.png', __FILE__ ) ),
                'user_img'        => ( isset( $user->hj_profile_photo ) ) ? $user->hj_profile_photo : null,
                'phone'           => ( isset( $user->hj_profile_phone ) ) ? esc_attr( $user->hj_profile_phone ) : null,
                'email'           => ( isset( $user->hj_profile_email ) ) ? esc_attr( $user->hj_profile_email ) : null,
                'linkedin'        => ( isset( $user->hj_profile_linkedin ) ) ? esc_attr( $user->hj_profile_linkedin ) : null,
                'role'            => ( isset( $user->hj_profile_role ) ) ? esc_attr( $user->hj_profile_role ) : null,
                'excerpt'         => ( isset( $user->hj_profile_excerpt ) ) ? esc_attr( $user->hj_profile_excerpt ) : null,
                'description'     => ( isset( $user->hj_profile_description ) ) ? esc_attr( $user->hj_profile_description ) : null,
                'associate'       => ( isset( $user->hj_profile_associate ) ) ? esc_attr( $user->hj_profile_associate ) : null,
                'directory'       => ( isset( $user->hj_profile_directory ) ) ? esc_attr( $user->hj_profile_directory ) : null
            );
            // Set display image, which requires parameter from params
            $params['display_img'] = ( isset( $user->hj_profile_photo ) && trim( $user->hj_profile_photo ) != false ) ? wp_get_attachment_image( $user->hj_profile_photo, 'hj_profile_photo', false, array('id' => 'hj-user-photo-image') ) : '<img id="hj-user-photo-image" src="' . $params['default_img_src'] . '" alt="Profile Image" width="' . $params['img_width'] . 'px">';
        }

        // Inlude path to markup
        include plugin_dir_path( __FILE__ ) . 'partials/hj-user-profiles-user-fields.php';
        // Enqueue the media manager for photo upload
        wp_enqueue_media();
    }

    /**
     * Validate and save the meta data
     * @since 1.0.0
     */
    public function save_user_profile_meta( $user_id ) {
        // if current user unable to upload files, return early
        ob_start();
        print_r($_POST);
        if( !current_user_can( 'upload_files', $user_id ) ) {
            return false;
        }
        // Sanitize and update email address
        if( isset( $_POST['hj_profile_email'] ) ) {
            $email = sanitize_email( $_POST['hj_profile_email'] );
            if ( is_email( $email ) || trim( $email ) == false ) {
                update_user_meta( $user_id, 'hj_profile_email', $email );
            }
        }
        // Sanitize and update phone number
        if( isset( $_POST['hj_profile_phone'] ) ) {
            update_user_meta( $user_id, 'hj_profile_phone', sanitize_text_field( $_POST['hj_profile_phone'] ) );
        }
        // Sanitize and update linkedin URI
        if( isset( $_POST['hj_profile_linkedin'] ) ) {
            update_user_meta( $user_id, 'hj_profile_linkedin', esc_url_raw( $_POST['hj_profile_linkedin'] ) );
        }
        // Sanitize and update role
        if( isset( $_POST['hj_profile_role'] ) ) {
            update_user_meta( $user_id, 'hj_profile_role', sanitize_text_field( $_POST['hj_profile_role'] ) );
        }
        // Sanitize and update excerpt
        if( isset( $_POST['hj_profile_excerpt']  ) ) {
            update_user_meta( $user_id, 'hj_profile_excerpt', sanitize_text_field( $_POST['hj_profile_excerpt'] ) );
        }
        // Sanitize and update description
        if( isset( $_POST['hj_profile_description']  ) ) {
            update_user_meta( $user_id, 'hj_profile_description', esc_textarea( $_POST['hj_profile_description'] ) );
        }
        // Sanitize and update associate
        if( isset( $_POST['hj_profile_associate']  ) ) {
            update_user_meta( $user_id, 'hj_profile_associate', $_POST['hj_profile_associate'] );
        } else {
            update_user_meta( $user_id, 'hj_profile_associate', false );
        }
        // Sanitize and update display
        if( isset( $_POST['hj_profile_directory']  ) ) {
            update_user_meta( $user_id, 'hj_profile_directory', $_POST['hj_profile_directory'] );
        } else {
            update_user_meta( $user_id, 'hj_profile_directory', false );
        }
        // Sanitize and update photo
        if( isset( $_POST['hj_profile_photo']  ) ) {
            update_user_meta( $user_id, 'hj_profile_photo', $_POST['hj_profile_photo'] );
        }
    }
} // end class
