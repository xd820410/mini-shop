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
        var total = 0
        var subtoal = 0
        var totalText = ''
        var subtotalText = ''
        await jQuery.each(response.content, function(key, item) {
        //await response.content.forEach(function(goods) {
            jQuery("#cart-item-card-sample").clone().appendTo(jQuery("#cart-item-list"))
            jQuery(".cart-item-card-sample").last().attr('id', 'cart-item-' + item.goods_id)
            jQuery("#cart-item-" + item.goods_id + " .cart-item-title").text(item.title)
            subtoal = item.price * item.quantity
            subtotalText = '$' + toCurrency(item.price) + ' x ' + item.quantity + ' = ' + '$' + toCurrency(subtoal)
            total += subtoal
            jQuery("#cart-item-" + item.goods_id + " .cart-item-price").text(subtotalText)
            if (item.image_path != null && item.image_path != '') {
                jQuery("#cart-item-" + item.goods_id + " .cart-item-image").attr('src', baseUrl + item.image_path)
            }
            jQuery("#cart-item-" + item.goods_id + " .delete-cart-item").data('goods-id', item.goods_id)
            jQuery("#cart-item-" + item.goods_id).show()
        })

        jQuery("#cart-total").text('$' + toCurrency(total))
        jQuery("#cart-total-block").show()

        bindDeleteItemFromCartEvent()
    } else {
        jQuery("#cart-item-list").append('<p><small>Let\'s get something awesome!</small></p>')
    }
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