jQuery(function() {
    wipe()
    showGoodsList()
})

async function showGoodsList() {
    var response = await getGoodsList()
    console.log(response)
    if (typeof response == 'object' && response.result == 'SUCCESS' && response.message.length > 0) {
        await response.message.forEach(function(goods) {
            jQuery("#goods-card-sample").clone().appendTo(jQuery("#goods-list"))
            jQuery(".goods-card-sample").last().attr('id', 'goods-' + goods.id)
            jQuery("#goods-" + goods.id + " .goods-title").text(goods.title)
            jQuery("#goods-" + goods.id + " .goods-price").text('$' + toCurrency(goods.price))
            jQuery("#goods-" + goods.id + " .goods-image").attr('src', baseUrl + goods.image_path)
            jQuery("#goods-" + goods.id + " .add-to-cart").data('goods-id', goods.id)
            jQuery("#goods-" + goods.id).show()
        })

        bindAddToCartEvent()
    }
}

function bindAddToCartEvent() {
    jQuery(".add-to-cart").unbind().click(function() {
        console.log(jQuery(this).data('goods-id'))
    })
}

function wipe() {
    jQuery("#goods-list").empty()
}

function getGoodsList() {
    var sendData = new Object()

    return jQuery.get(baseUrl + '/api/goods', sendData)
}
