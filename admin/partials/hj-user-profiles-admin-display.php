<?php

/**
 * Provide a admin area view for the plugin
 * Uses wordpress settings api to display setting spage
 * @since      1.0.0
 * @package    hj_User_Profiles
 * @subpackage hj_User_Profiles/admin/partials
 */

?>
<div class="wrap">
    <form method="post" action="options.php">
        <?php wp_nonce_field( 'update-options' ); ?>
        <?php settings_fields( $this->settings_key ); ?>
        <?php do_settings_sections( $this->settings_key ); ?>
        <?php submit_button(); ?>
    </form>
</div>
