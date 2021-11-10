jQuery(function() {
    wipe()
    jQuery("#create-goods").click(createGoods)
    showGoodsList()
})

function wipe() {
    jQuery("#goods-list").empty()
    jQuery("#alert-block").empty()
    jQuery("#alert-block").hide()
}

function columnValidate(name, price) {
    var requireColumn = ''
    if (name.length < 1) {
        requireColumn += 'Name'
    }
    if (price.length < 1) {
        if (requireColumn.length > 0) {
            requireColumn += ', '
        }
        requireColumn += 'Price'
    }

    var message = false
    if (requireColumn.length > 0) {
        message = requireColumn + ' must be filled.'
    }

    return message
}

function createGoods() {
    var message = ''
    var status = 'fail'

    var responseOfColumnValidate = columnValidate(jQuery("#new-name").val(), jQuery("#new-price").val())
    if (responseOfColumnValidate !== false) {
        showMessage(responseOfColumnValidate, 'fail')
        return
    }

    var formData = new FormData()
    formData.append('title', jQuery("#new-name").val())
    formData.append('description', jQuery("#new-description").val())
    formData.append('price', jQuery("#new-price").val())
    if (typeof jQuery("#new-image")[0].files[0] !== 'undefined') {
        formData.append('image', jQuery("#new-image")[0].files[0])
    }

    jQuery.ajax({
        url: baseUrl + '/api/goods',
        type: 'POST',
        data: formData,
        processData: false,
        headers: {"Authorization": "Bearer 236|y1AoZjmfmpb9lrpNCmxYvuQsU7S74HYpyKNsqvgq"},
        contentType: false,
    }).done(function(response, textStatus, jqXHR) {
        message = '發生錯誤，請點擊F12並切換到Console頁籤，將內容截圖提供給開發廠商'
        if (jqXHR.status == '201') {
            message = 'Created successfully.'
            status = 'success'
        }

        wipe()
        showGoodsList()
        showMessage(message, status)

        if (jqXHR.status != '201') {
            console.log('error on createGoods:', response)
        }
    }).fail(function(jqXHR, textStatus, errorThrown) {
        message = '發生錯誤，請點擊F12並切換到Console頁籤，將內容截圖提供給開發廠商'
        showMessage(message, status)
        console.log('fail to createGoods: ', jqXHR.responseText)
    })
}

async function showGoodsList() {
    var response = await getGoodsList()
    //console.log(response)
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

        jQuery(".delete").unbind().click(function() {
            deleteGoods(jQuery(this).data('goods-id'))
        })
    }
}

function getGoodsList() {
    var sendData = new Object()

    return jQuery.get(baseUrl + '/api/goods', sendData)
}

function updateGoods(goodsId) {
    var message = ''
    var status = 'fail'

    var name = jQuery("#name-" + goodsId).val()
    var price = jQuery("#price-" + goodsId).val()
    var responseOfColumnValidate = columnValidate(name, price)
    if (responseOfColumnValidate !== false) {
        showMessage(responseOfColumnValidate, 'fail')
        return
    }

    var formData = new FormData()
    formData.append('title', name)
    formData.append('description', jQuery("#description-" + goodsId).val())
    formData.append('price', price)
    if (typeof jQuery("#image-" + goodsId)[0].files[0] !== 'undefined') {
        formData.append('image', jQuery("#image-" + goodsId)[0].files[0])
    }
    formData.append('_method', 'PATCH')

    jQuery.ajax({
        url: baseUrl + '/api/goods/' + goodsId,
        type: 'POST',
        data: formData,
        processData: false,
        headers: {"Authorization": "Bearer 236|y1AoZjmfmpb9lrpNCmxYvuQsU7S74HYpyKNsqvgq"},
        contentType: false,
    }).done(function(response, textStatus, jqXHR) {
        message = '發生錯誤，請點擊F12並切換到Console頁籤，將內容截圖提供給開發廠商'
        if (jqXHR.status == '204') {
            message = 'Updated successfully.'
            status = 'success'
        }

        wipe()
        showGoodsList()
        showMessage(message, status)

        if (jqXHR.status != '204') {
            console.log('error on updateGoods:', response)
        }
    }).fail(function(jqXHR, textStatus, errorThrown) {
        message = '發生錯誤，請點擊F12並切換到Console頁籤，將內容截圖提供給開發廠商'
        showMessage(message, status)
        console.log('fail to updateGoods: ', jqXHR.responseText)
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

function deleteGoods(goodsId) {
    jQuery.ajax({
        url: baseUrl + '/api/goods/' + goodsId,
        type: 'DELETE',
        processData: false,
        headers: {"Authorization": "Bearer 236|y1AoZjmfmpb9lrpNCmxYvuQsU7S74HYpyKNsqvgq"},
        contentType: false,
    }).done(function(response, textStatus, jqXHR) {
        var message = '發生錯誤，請點擊F12並切換到Console頁籤，將內容截圖提供給開發廠商'
        var status = 'fail'
        if (jqXHR.status == '204') {
            message = 'Deleted successfully.'
            status = 'success'
        }

        wipe()
        showGoodsList()
        showMessage(message, status)

        if (jqXHR.status != '204') {
            console.log('error on deleteGoods:', response)
        }
    }).fail(function(jqXHR, textStatus, errorThrown) {
        var message = '發生錯誤，請點擊F12並切換到Console頁籤，將內容截圖提供給開發廠商'
        var status = 'fail'
        showMessage(message, status)
        console.log('fail to deleteGoods: ', jqXHR.responseText)
    })
}