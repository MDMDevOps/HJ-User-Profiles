<div id="hj-user-photo-container">
    <table class="form-table">
        <tr>
            <th><label for="hj_profile_phone"><?php _e( 'Direct Number', 'hj-user-profiles' ); ?></label></th>
            <td><input class="regular-text" type="text" id="hj_profile_phone" name="hj_profile_phone" value="<?php echo $params['phone']; ?>" placeholder="Users Direct Phone Number"></td>
        </tr>
        <tr>
            <th><label for="hj_profile_email"><?php _e( 'Email', 'hj-user-profiles' ); ?></label></th>
            <td><input class="regular-text" type="text" id="hj_profile_email" name="hj_profile_email" value="<?php echo $params['email']; ?>" placeholder="Users Public Email"></td>
        </tr>
        <tr>
            <th><label for="hj_profile_linkedin"><?php _e( 'LinkedIn', 'hj-user-profiles' ); ?></label></th>
            <td><input class="regular-text" type="text" id="hj_profile_linkedin" name="hj_profile_linkedin" value="<?php echo $params['linkedin']; ?>" placeholder="Users Linkedin URI"></td>
        </tr>
        <tr>
            <th><label for="hj_profile_role"><?php _e( 'Role', 'hj-user-profiles' ); ?></label></th>
            <td><input class="regular-text" type="text" id="hj_profile_role" name="hj_profile_role" value="<?php echo $params['role']; ?>" placeholder="Users Role in Company"></td>
        </tr>
        <tr>
            <th><label for="hj_profile_excerpt"><?php _e( 'Profile Excerpt', 'hj-user-profiles' ); ?></label></th>
            <td><input class="regular-text" type="text" id="hj_profile_excerpt" name="hj_profile_excerpt" value="<?php echo $params['excerpt']; ?>" placeholder="Short Profile Excerpt"></td>
        </tr>
        <tr>
            <th><label for="hj_profile_description"><?php _e( 'Profile Description', 'hj-user-profiles' ); ?></label></th>
            <td>
            <textarea name="hj_profile_description" id="hj_profile_description" cols="30" rows="10" placeholder="Profile Description"><?php echo $params['description']; ?></textarea>
            </td>
        </tr>
        <tr>
            <th><label for="cupp_meta"><?php _e( 'Profile Photo', 'hj-user-profiles' ); ?></label></th>
            <td>
                <div id="hj-user-photo-wrapper">
                    <div id="hj-user-photo-display">
                        <?php echo $params['display_img']; ?>
                    </div>
                    <input type="text" id="hj_profile_photo" class="hidden" name="hj_profile_photo" value="<?php echo $params['user_img']; ?>"><!-- used to keep from saving default photo as user photo -->
                    <input type="button" id="hj-upload-image" class="hj-photo-upload-button button-primary" value="<?php _e( 'Choose Photo', 'hj-user-profiles' ); ?>">
                    <input type="button" id="hj-remove-image" class="hj-photo-remove-button button-primary" value="<?php _e( 'Remove Photo', 'hj-user-profiles' ); ?>">
                    <input type="text" id="hj_default_photo" class="hidden" name="hj_default_photo" value="<?php echo $params['default_img_src']; ?>">
                </div>
            </td>
        </tr>
        <tr>
            <th><label for="hj_profile_associate"><?php _e( 'Associate', 'hj-user-profiles' ); ?></label></th>
            <td><label for="hj_profile_associate"><input type="checkbox" id="hj_profile_associate" name="hj_profile_associate" value="1" <?php checked( $params['associate'], true, true ); ?>>&nbsp;Associate?</label></td>
        </tr>
        <tr>
            <th><label for="hj_profile_directory"><?php _e( 'User Directory', 'hj-user-profiles' ); ?></label></th>
            <td><label for="hj_profile_directory"><input type="checkbox" id="hj_profile_directory" name="hj_profile_directory" value="1" <?php checked( $params['directory'], true, true ); ?>>&nbsp;Display in Directory?</label></td>
        </tr>
    </table>
</div>