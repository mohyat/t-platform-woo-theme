/**
 * T-Platform Main JavaScript
 * Frontend interactions and AJAX functionality
 *
 * @package T_Platform_WooCommerce_Theme
 * @since 0.1.0
 */

(function($) {
    'use strict';
    
    /**
     * T-Platform Main Object
     */
    const TPlatform = {
        
        /**
         * Inicializacija
         */
        init: function() {
            this.bindEvents();
            this.initTooltips();
            console.log('T-Platform initialized');
        },
        
        /**
         * Povezi evente
         */
        bindEvents: function() {
            // Add to cart AJAX
            $(document).on('click', '.t-platform-add-to-cart', this.handleAddToCart);
            
            // Quick view
            $(document).on('click', '.t-platform-quick-view', this.handleQuickView);
            
            // Search form
            $(document).on('submit', '.t-platform-search-form', this.handleSearch);
            
            // Product image zoom
            $(document).on('mouseenter', '.t-platform-product-image img', this.handleImageZoom);
            $(document).on('mouseleave', '.t-platform-product-image img', this.handleImageZoomOut);
        },
        
        /**
         * Add to cart handler
         */
        handleAddToCart: function(e) {
            e.preventDefault();
            
            const $button = $(this);
            const productId = $button.data('product-id');
            const quantity = $button.data('quantity') || 1;
            
            // Disable button
            $button.prop('disabled', true).text('Adding...');
            
            // AJAX request
            $.ajax({
                url: tPlatform.ajaxUrl,
                type: 'POST',
                data: {
                    action: 't_platform_add_to_cart',
                    product_id: productId,
                    quantity: quantity,
                    nonce: tPlatform.nonce
                },
                success: function(response) {
                    if (response.success) {
                        // Update cart count
                        $('.t-platform-cart-count').text(response.data.cart_count);
                        
                        // Show success message
                        TPlatform.showNotification('Product added to cart!', 'success');
                        
                        // Reset button
                        $button.prop('disabled', false).text('Add to Cart');
                    } else {
                        TPlatform.showNotification(response.data.message, 'error');
                        $button.prop('disabled', false).text('Add to Cart');
                    }
                },
                error: function() {
                    TPlatform.showNotification('Error adding product to cart', 'error');
                    $button.prop('disabled', false).text('Add to Cart');
                }
            });
        },
        
        /**
         * Quick view handler
         */
        handleQuickView: function(e) {
            e.preventDefault();
            
            const productId = $(this).data('product-id');
            
            // TODO: Implement quick view modal
            console.log('Quick view for product:', productId);
            TPlatform.showNotification('Quick view coming soon!', 'info');
        },
        
        /**
         * Search handler
         */
        handleSearch: function(e) {
            e.preventDefault();
            
            const searchTerm = $(this).find('.t-platform-search-input').val();
            
            if (searchTerm.trim() === '') {
                e.preventDefault();
                return false;
            }
            
            // Let form submit normally
            return true;
        },
        
        /**
         * Image zoom handler
         */
        handleImageZoom: function() {
            $(this).css('transform', 'scale(1.1)');
        },
        
        /**
         * Image zoom out handler
         */
        handleImageZoomOut: function() {
            $(this).css('transform', 'scale(1)');
        },
        
        /**
         * Initialize tooltips
         */
        initTooltips: function() {
            $('[data-tooltip]').each(function() {
                const $el = $(this);
                const tooltipText = $el.data('tooltip');
                
                $el.attr('title', tooltipText);
            });
        },
        
        /**
         * Show notification
         */
        showNotification: function(message, type) {
            type = type || 'info';
            
            const $notification = $(`
                <div class="t-platform-notification t-platform-notification-${type}">
                    ${message}
                </div>
            `);
            
            $('body').append($notification);
            
            // Animate in
            setTimeout(function() {
                $notification.addClass('show');
            }, 10);
            
            // Remove after 3 seconds
            setTimeout(function() {
                $notification.removeClass('show');
                setTimeout(function() {
                    $notification.remove();
                }, 300);
            }, 3000);
        }
    };
    
    /**
     * Initialize on document ready
     */
    $(document).ready(function() {
        TPlatform.init();
    });
    
})(jQuery);
