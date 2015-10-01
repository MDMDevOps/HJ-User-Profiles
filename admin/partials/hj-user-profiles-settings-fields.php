<?php
    $field = $this->settings_key . '[' . esc_attr( $args['key'] ) . ']';
?>

<?php if( $args['type'] === 'photo-size' ) {
    $width  = isset( $args['width'] ) ? esc_attr( $args['width'] ) : null;
    $height = isset( $args['height'] ) ? esc_attr( $args['height'] ) : null;
    $default_img = isset( $args['default_img'] ) ? esc_url_raw( $args['default_img'] ) : null;
    ?>
    <div class="hj-setting-field">
        <div class="hj-setting-group">
            <div class="column sm-4">
                <label for="<?php echo $field . '[width]'; ?>">Max Width (px)</label>
                <input type="number" class="widefat" name="<?php echo $field . '[width]'; ?>" value="<?php echo $width; ?>" placeholder="Max Width">
            </div>
            <div class="column sm-4 end">
                <label for="<?php echo $field . '[height]'; ?>">Max Height (px)</label>
                <input type="number" class="widefat" name="<?php echo $field . '[height]'; ?>" value="<?php echo $height; ?>" placeholder="Max Height">
            </div>
        </div>
        <div class="hidden" type="text" name="<?php echo $field . '[default_img]' ?>" value="<?php echo $default_img; ?>">
    </div>
<?php } ?>

<?php if( $args['type'] === 'checkbox' ) {
    $checked  = isset( $args['checked'] ) ? esc_attr( $args['checked'] ) : null;
    $description = isset( $args['description'] ) ? esc_attr( $args['description'] ) : null;
    ?>
    <div class="hj-setting-field">
        <label for="<?php echo $field . '[checked]'; ?>"><input type="checkbox" name="<?php echo $field . '[checked]'; ?>" <?php checked( $checked, 'on', true ); ?>> <?php echo $description ?></label>
        <input type="text" class="hidden" name="<?php echo $field . '[type]'; ?>" value="<?php echo $args['type']; ?>">
        <input type="text" class="hidden" name="<?php echo $field . '[label]'; ?>" value="<?php echo $args['label']; ?>">
        <input type="text" class="hidden" name="<?php echo $field . '[description]'; ?>" value="<?php echo $args['description']; ?>">
        <input type="text" class="hidden" name="<?php echo $field . '[key]'; ?>" value="<?php echo $args['key']; ?>">
    </div>
<?php } ?>