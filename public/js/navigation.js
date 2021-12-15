jQuery(function() {
    showManagerLink()
})

async function showManagerLink() {
    var response = await checkUserIsAnAdministrator()
    console.log('checkUserIsAnAdministrator: ', response)
    if (typeof response == 'object' && response.result == 'SUCCESS' && response.content !== null && response.content == true) {
        var managerHtml = '<a class="dropdown-item" href="/manager/goods">Goods manage</a>'
        jQuery("#navigation-collapse").prepend(managerHtml)
    }
}

function checkUserIsAnAdministrator() {
    var sendData = new Object()

    return jQuery.get(baseUrl + '/check_administrator_permission', sendData)
}