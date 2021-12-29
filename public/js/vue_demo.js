showGoodsList()

jQuery(function() {
    cartHoverEvent()
})

function showGoodsList() {
    const goodsListData = {
        data: function() {
            return {
                goodsList: []
            }
        },
        methods: {
            getGoodsList: function() {
                axios.get(baseUrl + '/api/goods')
                .then(response => {
                    console.log('getGoodsList', response.data)
                    console.log('this.goodsList1', this.goodsList)
                    this.goodsList = response.data.message
                    console.log('this.goodsList2', this.goodsList)
                }).catch(function (response) {
                    console.log('fail to getGoodsList', response)
                })
            }
        },
        created: function() {
            this.getGoodsList()
        }
    }

    const goodsListBlock = Vue.createApp(goodsListData)

    goodsListBlock.component('goods-card', {
        props: ['created_at', 'description', 'id', 'image_path', 'price', 'title', 'updated_at'],
        //template: '<h5 class="card-title goods-title">{{ title }}</h5>'
        template: `
            <div class="col-3 mb-3 goods-card-sample" id="goods-card-sample">
                <div class="card" style="width: 100%;">
                    <img :src="image_path" class="card-img-top goods-image" onError="this.src='/images/coming_soon.jpg';" style="width: 100%;">
    
                    <div class="card-img-overlay" style="height: 50%;">
                        <small style="color: #084298; background-color: #cfe2ff; font-weight: bold; display: none;" class="goods-discount-title rounded" :data-goods-id="id">
                        </small>
                    </div>
    
                    <div class="card-body">
                        <h5 class="card-title goods-title">{{ title }}</h5>
                        <p class="card-text goods-price">{{ price }}</p>
                        <button class="btn btn-primary add-to-cart" :data-goods-id="id">Add to cart</button>
                    </div>
                </div>
            </div>
        `
    })
    
    goodsListBlock.mount("#goods-list")
}

async function showDiscount() {
    var response = await getEffectiveDiscount()
    console.log('showDiscount response', response)
    if (typeof response == 'object' && response.result == 'SUCCESS' && response.content.length > 0) {
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

function getEffectiveDiscount() {
    var sendData = new Object()

    return jQuery.get(baseUrl + '/api/get_effective_discount', sendData)
}