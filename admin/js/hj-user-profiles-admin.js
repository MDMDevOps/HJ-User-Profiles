jQuery( document ).ready( function( $ ) {
    'use strict';
    var file_frame;
    $( '#hj-upload-image' ).on( 'click', function( event ) {
        event.preventDefault();

        // If the media frame already exists, reopen it.
        if( file_frame ) {
          file_frame.open();
          return;
        }
        // Create new media frame
        file_frame = wp.media.frames.file_frame = wp.media({
            title: 'Select Media',
            button: {
                text: 'Select Image'
            },
            multiple: false
        });

        file_frame.on( 'select', function() {
            var attachment = file_frame.state().get('selection').first().toJSON();
            console.log( attachment );
            $('#hj_profile_photo').attr('value', attachment.id );
            $('#hj-user-photo-image').attr('src', attachment.url );
        });
        file_frame.open();
    });
    $( '#hj-remove-image' ).on( 'click', function( event ) {
        event.preventDefault();
        if( confirm( 'Are you sure you want to remove this image from your profile?' ) ) {
            var default_img = $('#hj_default_photo').attr('value');
            $('#hj_profile_photo').attr('value', '' );
            $('#hj-user-photo-image').attr('src', default_img );
        }
    });
});
