/**
 * T-Platform Admin JavaScript
 *
 * @package T_Platform_WooCommerce_Theme
 * @since 0.2.0
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        
        // Tabs functionality
        $('.t-platform-tabs .nav-tab').on('click', function(e) {
            e.preventDefault();
            
            var targetTab = $(this).data('tab');
            
            // Remove active class from all tabs and content
            $('.t-platform-tabs .nav-tab').removeClass('nav-tab-active');
            $('.t-platform-tab-content').removeClass('active');
            
            // Add active class to clicked tab and target content
            $(this).addClass('nav-tab-active');
            $('#' + targetTab).addClass('active');
            
            // Update URL hash
            window.location.hash = targetTab;
        });
        
        // Open tab from URL hash on page load
        if (window.location.hash) {
            var hash = window.location.hash.substring(1);
            $('.t-platform-tabs .nav-tab[data-tab="' + hash + '"]').trigger('click');
        }
        
        // Logo upload - open media library
        $('.t-platform-upload-logo').on('click', function(e) {
            e.preventDefault();
            
            var button = $(this);
            var previewContainer = button.siblings('.t-platform-logo-preview');
            var hiddenInput = button.siblings('.t-platform-logo-url');
            
            var mediaUploader = wp.media({
                title: 'Izberi logotip',
                button: {
                    text: 'Uporabi ta logotip'
                },
                multiple: false
            });
            
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                hiddenInput.val(attachment.url);
                previewContainer.html('<img src="' + attachment.url + '" alt="Logo" />');
            });
            
            mediaUploader.open();
        });
        
        // Color picker initialization (if WordPress color picker is available)
        if ($.fn.wpColorPicker) {
            $('.t-platform-color-picker').wpColorPicker();
        }
        
    });

})(jQuery);
