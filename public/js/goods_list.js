jQuery(function() {
    wipe()
    showGoodsList()
    cartHoverEvent()
})

async function showGoodsList() {
    var response = await getGoodsList()
    console.log('showGoodsList response', response)
    if (typeof response == 'object' && response.result == 'SUCCESS' && response.message.length > 0) {
        await response.message.forEach(function(goods) {
            jQuery("#goods-card-sample").clone().appendTo(jQuery("#goods-list"))
            jQuery(".goods-card-sample").last().attr('id', 'goods-' + goods.id)

            //discount
            jQuery("#goods-" + goods.id + " .goods-discount-title").attr('id', 'goods-discount-title-' + goods.id)
            //jQuery("#goods-discount-title-" + goods.id).addClass('goods-discount-title-dupe')
            //jQuery("#goods-discount-title-" + goods.id).data('goods-id', goods.id)

            jQuery("#goods-" + goods.id + " .goods-title").text(goods.title)
            jQuery("#goods-" + goods.id + " .goods-price").text('$' + toCurrency(goods.price))
            if (goods.image_path != null && goods.image_path != '') {
                jQuery("#goods-" + goods.id + " .goods-image").attr('src', baseUrl + goods.image_path)
            }
            jQuery("#goods-" + goods.id + " .add-to-cart").data('goods-id', goods.id)
            jQuery("#goods-" + goods.id).show()
        })

        bindAddToCartEvent()
        showDiscount()
    }
}

async function showDiscount() {
    var response = await getEffectiveDiscount()
    console.log('showDiscount response', response)
    if (typeof response == 'object' && response.result == 'SUCCESS' && response.content.length > 0) {
        var discountText = ''
        var newDiscountText = ''
        var fillDiscountList = {}
        await response.content.forEach(function(eachDiscount) {
            /**
             * payload sample:
             * {
             *      "affected": [158],
             *      "threshold": 2,
             *      "discount_type": "percent",
             *      "discount_value": 20,
             *  }
             */
            if (typeof eachDiscount.payload !== 'undefined' && typeof eachDiscount.title !== 'undefined' && Object.keys(eachDiscount.payload).length > 0 && eachDiscount.title.length > 0) {
                if (typeof eachDiscount.payload.affected !== 'undefined' && eachDiscount.payload.affected.length > 0) {
                    eachDiscount.payload.affected.forEach(function(affectedGoodsId) {
                        if (typeof fillDiscountList[affectedGoodsId] === 'undefined') {
                            fillDiscountList[affectedGoodsId] = ''
                        }
                        if (fillDiscountList[affectedGoodsId].length > 0) {
                            fillDiscountList[affectedGoodsId] += '<br />'
                        }
                        fillDiscountList[affectedGoodsId] += eachDiscount.title
                    })
                }
            }
        })
        //console.log('fillDiscountList', fillDiscountList)
        jQuery.each(fillDiscountList, function(goodsId, discountText) {
            jQuery("#goods-discount-title-" + goodsId).html(discountText).show()
        })
    }
}

function bindAddToCartEvent() {
    jQuery(".add-to-cart").unbind().click(function() {
        console.log('goods-id', jQuery(this).data('goods-id'))

        var sendData = new Object()
        sendData.goods_id = jQuery(this).data('goods-id')
        sendData.quantity = 1
        sendData._token = document.head.querySelector('meta[name="csrf-token"]').content

        jQuery.post(baseUrl + '/cart', sendData)
        .done(function(response) {
            console.log('AddToCart response', response)
            refreshMiniCartContent()
            miniCartHoverTimer = null
            miniCart.show()
        }).fail(function() {
            console.log('fail to AddToCart')
        })
    })
}

function wipe() {
    jQuery("#goods-list").empty()
}

function getGoodsList() {
    var sendData = new Object()

    return jQuery.get(baseUrl + '/api/goods', sendData)
}

function getEffectiveDiscount() {
    var sendData = new Object()

    return jQuery.get(baseUrl + '/api/get_effective_discount', sendData)
}