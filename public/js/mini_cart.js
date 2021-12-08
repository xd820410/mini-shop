var miniCartHoverTimer = null
var cartShowingFlag = false
var miniCartElement
var miniCart

jQuery(function() {
    refreshMiniCartContent()
    cartHoverEvent()
})

async function refreshMiniCartContent() {
    jQuery("#cart-total-block").hide()
    jQuery("#cart-item-list").empty()
    var response = await getCart()
    if (typeof response == 'object' && response.result == 'SUCCESS' && response.content !== null && Object.keys(response.content).length > 0) {
        console.log('mini cart', response)
        var discountCounts = 0
        var discountText = ''
        var subtotal = 0
        var subtotalText = ''
        await jQuery.each(response.content, function(key, item) {
            jQuery("#cart-item-card-sample").clone().appendTo(jQuery("#cart-item-list"))
            jQuery(".cart-item-card-sample").last().attr('id', 'cart-item-' + item.goods_id)
            jQuery("#cart-item-" + item.goods_id + " .cart-item-title").text(item.title)
            subtotal = item.price * item.quantity
            subtotalText = '$' + toCurrency(item.price) + ' x ' + item.quantity + ' ='
            jQuery("#cart-item-" + item.goods_id + " .cart-item-price-text").text(subtotalText)
            jQuery("#cart-item-" + item.goods_id + " .cart-item-subtotal").text('$' + toCurrency(subtotal))
            if (item.image_path != null && item.image_path != '') {
                jQuery("#cart-item-" + item.goods_id + " .cart-item-image").attr('src', baseUrl + item.image_path)
            }
            jQuery("#cart-item-" + item.goods_id + " .delete-cart-item").data('goods-id', item.goods_id)

            //quantity
            jQuery("#cart-item-" + item.goods_id + " .quantity-dropdown").attr('id', 'cart-item-quantity-' + item.goods_id)
            jQuery("#cart-item-quantity-" + item.goods_id).text(item.quantity)
            jQuery("#cart-item-" + item.goods_id + " .dropdown-item").data('goods-id', item.goods_id)

            //discount
            jQuery("#cart-item-" + item.goods_id + " .discount-block").attr('id', 'cart-item-discount-block-' + item.goods_id)
            if (typeof item.discount !== 'undefined') {
                if (item.discount.length > 0) {
                    discountCounts = 0
                    item.discount.forEach(function(discountData) {
                        jQuery("#discount-sample").clone().appendTo(jQuery("#cart-item-discount-block-" + item.goods_id))
                        jQuery(".discount-sample").last().attr('id', 'cart-item-' + item.goods_id + '-discount-' + discountCounts)
                        jQuery("#cart-item-" + item.goods_id + "-discount-" + discountCounts + " .discount-title").text(discountData.title)
                        discountText = toCurrency(discountData.discount)
                        discountText = discountText.slice(0, 1) + '$' + discountText.slice(1)
                        jQuery("#cart-item-" + item.goods_id + "-discount-" + discountCounts + " .discount").text(discountText)
                        jQuery("#cart-item-" + item.goods_id + "-discount-" + discountCounts).show()
                        discountCounts++
                    })
                }
            }

            jQuery("#cart-item-" + item.goods_id).show()
        })

        //jQuery("#cart-total").text('$' + toCurrency(total))
        jQuery("#cart-total-block").show()

        bindDeleteItemFromCartEvent()
        bindEditItemQuantityFromCartEvent()
    } else {
        jQuery("#cart-item-list").append('<p><small>Let\'s get something awesome!</small></p>')
    }
}

function bindEditItemQuantityFromCartEvent() {
    jQuery(".dropdown-item").unbind().click(function() {
        //if you change the quantity
        if (jQuery(this).data('quantity') != jQuery("#cart-item-quantity-" + jQuery(this).data('goods-id')).text()) {
            // console.log('goods-id to edit quantity', jQuery(this).data('goods-id'))
            // console.log('quantity', jQuery(this).data('quantity'))
            var sendData = new Object()
            sendData.goods_id = jQuery(this).data('goods-id')
            sendData.quantity = jQuery(this).data('quantity')
            sendData._token = document.head.querySelector('meta[name="csrf-token"]').content
    
            jQuery.post(baseUrl + '/edit_item_quantity_from_cart', sendData)
            .done(function(response) {
                console.log('EditItemQuantityFromCart response', response)
                refreshMiniCartContent()
            }).fail(function() {
                console.log('fail to EditItemQuantityFromCart')
            })
        }
    })
}

function bindDeleteItemFromCartEvent() {
    jQuery(".delete-cart-item").unbind().click(function() {
        console.log('goods-id to delete from cart', jQuery(this).data('goods-id'))

        var sendData = new Object()
        sendData.goods_id = jQuery(this).data('goods-id')
        sendData._token = document.head.querySelector('meta[name="csrf-token"]').content

        jQuery.post(baseUrl + '/delete_item_from_cart', sendData)
        .done(function(response) {
            console.log('DeleteItemFromCart response', response)
            refreshMiniCartContent()
        }).fail(function() {
            console.log('fail to DeleteItemFromCart')
        })
    })
}

function cartHoverEvent() {
    miniCartElement = document.getElementById('mini-cart-block')
    miniCart = new bootstrap.Offcanvas(miniCartElement)

    miniCartElement.addEventListener('shown.bs.offcanvas', function () {
        //console.log('showing')
        cartShowingFlag = true
    })

    miniCartElement.addEventListener('hide.bs.offcanvas', function () {
        //console.log('hiding')
        cartShowingFlag = false
    })

    cartShowingFlag = false
    jQuery("#mini-cart-icon").hover(
        function() {
            if (miniCartHoverTimer === null && cartShowingFlag === false) {
                miniCartHoverTimer = window.setTimeout(function() {
                    miniCartHoverTimer = null
                    miniCart.show()
                }, 300)
            }
        },
        function () {
            //clear timer
            if (miniCartHoverTimer != null) {
                window.clearTimeout(miniCartHoverTimer);
                miniCartHoverTimer = null
            }
        }
    )
}

function getCart() {
    var sendData = new Object()

    return jQuery.get(baseUrl + '/cart', sendData)
}