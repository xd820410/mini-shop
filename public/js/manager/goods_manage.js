jQuery(function() {
    wipe()
    showGoodsList()
})

function wipe() {
    jQuery("#goods-list").empty()
    jQuery("#alert-block").empty()
    jQuery("#alert-block").hide()
}

async function showGoodsList() {
    var response = await getGoodsList()
    console.log(response)
    if (typeof response == 'object' && response.result == 'SUCCESS' && response.message.length > 0) {
        await response.message.forEach(function(goods) {
            jQuery("#goods-card-sample").clone().appendTo(jQuery("#goods-list"))
            jQuery(".goods-card-sample").last().attr('id', 'goods-' + goods.id)

            if (goods.image_path == null) {
                goods.image_path = '/images/coming_soon.jpg';
            }
            jQuery("#goods-" + goods.id + " .goods-image").attr('src', goods.image_path)

            jQuery("#goods-" + goods.id + " .name").val(goods.title)
            jQuery("#goods-" + goods.id + " .name").attr('id', 'name-' + goods.id)

            jQuery("#goods-" + goods.id + " .description").val(goods.description)
            jQuery("#goods-" + goods.id + " .description").attr('id', 'description-' + goods.id)

            jQuery("#goods-" + goods.id + " .price").val(goods.price)
            jQuery("#goods-" + goods.id + " .price").attr('id', 'price-' + goods.id)

            jQuery("#goods-" + goods.id + " .image").attr('id', 'image-' + goods.id)

            jQuery("#goods-" + goods.id + " .update").data('goods-id', goods.id)
            jQuery("#goods-" + goods.id + " .delete").data('goods-id', goods.id)

            jQuery("#goods-" + goods.id).show()
        })

        jQuery(".update").unbind().click(function() {
            updateGoods(jQuery(this).data('goods-id'))
        })
    }
}

function getGoodsList() {
    var sendData = new Object()

    return jQuery.get(baseUrl + '/api/goods', sendData)
}

function updateGoods(goodsId) {
    //console.log('goodsId', goodsId)
    var formData = new FormData()
    formData.append('title', jQuery("#name-" + goodsId).val())
    formData.append('description', jQuery("#description-" + goodsId).val())
    formData.append('price', jQuery("#price-" + goodsId).val())
    if (typeof jQuery("#image-" + goodsId)[0].files[0] !== 'undefined') {
        formData.append('image', jQuery("#image-" + goodsId)[0].files[0])
    }
    formData.append('_method', 'PATCH')

    jQuery.ajax({
        url: baseUrl + '/api/goods/' + goodsId,
        type: 'post',
        data: formData,
        processData: false,
        headers: {"Authorization": "Bearer 236|y1AoZjmfmpb9lrpNCmxYvuQsU7S74HYpyKNsqvgq"},
        contentType: false,
    }).done(function(response, textStatus, jqXHR) {
        var message = '發生錯誤，請點擊F12並切換到Console頁籤，將內容截圖提供給開發廠商'
        var status = 'fail'

        if (jqXHR.status == '204') {
            message = 'Updated successfully.'
            status = 'success'
        }
        if (jqXHR.status == '201') {
            message = 'Created successfully.'
            status = 'success'
        }

        wipe()
        showGoodsList()
        showMessage(message, status)

        if (jqXHR.status != '204' && jqXHR.status != '201') {
            console.log('error on updateGoods:', response)
        }
    }).fail(function() {
        console.log('fail to updateGoods')
    })
}

function showMessage(message, status = 'success') {
    var now = new Date()
    var alertBlock = jQuery("#alert-block")
    alertBlock.removeClass('alert-danger').removeClass('alert-success')
    if (status == 'success') {
        alertBlock.addClass('alert-success')
    } else {
        alertBlock.addClass('alert-danger')
    }
    var hour = String(now.getHours())
    if (hour.length == 1) {
        hour = '0' + hour
    }
    var minute = String(now.getMinutes())
    if (minute.length == 1) {
        minute = '0' + minute
    }
    var second = String(now.getSeconds())
    if (second.length == 1) {
        second = '0' + second
    }
    alertBlock.text(message + ' (' + hour + ':' + minute + ':' + second + ')')
    alertBlock.show()

    jQuery("html, body").animate({
        scrollTop: 0,
    }, 200)
}