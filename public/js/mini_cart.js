jQuery(function() {
    cartHoverEvent()
})

function cartHoverEvent() {
    var miniCartElement = document.getElementById('mini-cart-block')
    var miniCart = new bootstrap.Offcanvas(miniCartElement)

    miniCartElement.addEventListener('shown.bs.offcanvas', function () {
        //console.log('showing')
        cartShowingFlag = true
    })

    miniCartElement.addEventListener('hide.bs.offcanvas', function () {
        //console.log('hiding')
        cartShowingFlag = false
    })

    var cartShowingFlag = false
    jQuery("#mini-cart-icon").hover(function() {
        if (cartShowingFlag == false) {
            miniCart.show()
        }
    })
}
