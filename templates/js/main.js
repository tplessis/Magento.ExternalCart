var baseUri = '/cart/index.php';

/**
 * Update item quantity in the cart
 *
 * @return void
 */
$(function(){
	$('.item_qty').change( function() {
		$.ajax({
            type: 'GET',
            url: baseUri,
            data: { action: 'update', id: $(this).attr('data-id'), quantity: $(this).val() },
            success: function(content) {
            	// TODO: Refresh shopping cart here
            }
        });
        return false;
	});
});