<?php

/**
 * The public-facing functionality of the plugin.
 * @link       http://www.midwestdigitalmarketing.com
 * @since      1.0.0
 * @package    hj_User_Profiles
 * @subpackage hj_User_Profiles/public
 */

/**
 * The public-facing functionality of the plugin.
 * Defines the plugin name, version, and public hooks
 * @package    hj_User_Profiles
 * @subpackage hj_User_Profiles/public
 * @author     Midwest Digital Marketing <bob.moore@midwestdigitalmarketing.com>
 */
class hj_User_Profiles_Public {

    /**
     * The ID of this plugin.
     * @since    1.0.0
     * @access   private
     * @var      string
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     * @since    1.0.0
     * @access   private
     * @var      string
     */
    private $version;

    /**
     * Array to hold list of users & user meta
     * @since    1.0.0
     * @access   private
     * @var      array
     */
    private $user_directory;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     * @since    1.0.0
     */
    public function enqueue_styles() {
        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/hj-user-profiles-public.css', array(), $this->version, 'all' );
    }

    /**
     * Register the javascript for the public-facing side of the site.
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/hj-user-profiles-public.js', array( 'jquery' ), $this->version, false );
    }

    /**
     * Get user directory from database
     * @since    1.0.0
     */
    public function get_user_directory() {
        $directory = array(); // initialize array to hold our user directory
        foreach( get_users( ) as $user ) { // actual user object
            $user_meta = get_user_meta( $user->ID ); // holds meta-data for user
            if( $user_meta['hj_profile_directory'][0] ) { // if directory flag == true (1), proceed
                // get initial data from user object
                $temp = array(
                    'user_id'      => $user->ID,
                    'user_url'     => $user->data->user_url,
                    'display_name' => $user->data->display_name
                );
                // fill remaining meta-data from meta query
                foreach( $user_meta as $meta => $value ) {
                    $temp[ $meta ] = $value[0];
                }
                // push temp array to list of users to display
                $directory[] = $temp;
            } // end if directory
        } // end foreach
        usort( $directory, array( $this, 'sort_users' ) );
        return $directory;
    } // end function

    /**
     * Sort array alphabetically by last name, and then first name if necessary
     * @since    1.0.0
     */
    public function sort_users( $user_a, $user_b ) {
        // Cast last names to lowercase to compare
        $a_name = strtolower( $user_a['last_name'] );
        $b_name = strtolower( $user_b['last_name'] );

        // If same last name, compare first names
        if( $a_name == $b_name ) {
            // Case first names to lowercase to compare
            $a_name = strtolower( $user_a['first_name'] );
            $b_name = strtolower( $user_b['first_name'] );
            // If first names also equal, return 0
            if( $a_name == $b_name ) {
                return 0;
            } elseif( $a_name > $b_name ) {
                // if a_name bigger than b_name, return 1
                return 1;
            } else {
                // only possibility left is a_name smaller than b_name, return -1
                return -1;
            }
        } elseif( $a_name > $b_name ) {
            // if a_name bigger than b_name, return 1
            return 1;
        } else {
            // only possibility left is a_name smaller than b_name, return -1
            return -1;
        }
    } // end function

    public function hj_display_users( $atts ) {

        // Attributes
        extract( shortcode_atts(
            array(
                'sorting' => 'alphabetical',
            ), $atts )
        );
        $this->user_directory = $this->get_user_directory();

        $sorted_users = array(
            'ag' => array(
                'employees'  => null,
                'associates' => null
            ),
            'hm' => array(
                'employees'  => null,
                'associates' => null
            ),
            'ns' => array(
                'employees'  => null,
                'associates' => null
            ),
            'tz' => array(
                'employees'  => null,
                'associates' => null
            )
        );

        // sort users into alphabetical
        foreach( $this->user_directory as $user ) {
            // Temporary variable to hold alpha placement
            $alpha_sort;
            // Temporary variable to hold associate status
            $assoc_sort;
            // Set alpha
            $index = strtolower( substr( $user['last_name'], 0, 1) );
            if( $index <= 'g') {
                echo 'less than g';
                $alpha_sort = 'ag';
            } elseif( $index > 'g' && $index <= 'm' ) {
                $alpha_sort = 'hm';
            } elseif( $index > 'm' && $index <= 's' ) {
                $alpha_sort = 'ns';
            } elseif( $index > 's' && $index <= 'z' ) {
                $alpha_sort = 'tz';
            }
            // Sort associate status
            if( isset( $user['associate'] ) && $user['associate'] ) {
                $assoc_sort = 'associate';
            } else {
                $assoc_sort = 'employees';
            }
            // Place in proper array
            $sorted_users[ $alpha_sort ][ $assoc_sort ][] = $user;
        }
        ob_start();
        print_r( $sorted_users );
    }
    public function register_shortcodes() {
        add_shortcode( 'hj_user_directory', array( $this, 'hj_display_users' ) );
    }
} // end lcass
