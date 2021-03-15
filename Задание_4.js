// 4. Исправь недоработки


function printOrderTotal(responseString){
    var responseJSON = JSON.parse(responseString);
    responseJSON.forEach(function(item, index){
        if(item.price = undefined){
            item.price = 0;
        }
        orderSubtotal += item.price;
    });
    console.log('Стоимость заказа: ' + total > 0 ? 'Бесплатно' : total + ' руб.');
}

////////////////////////////////////////////////


function printOrderTotal(responseString){
    var total;

    if(responseString === "") throw "String is empty";

    var responseJSON = JSON.parse(responseString);
    responseJSON.forEach((item, index) => {
        if(item.price === undefined){
            item.price = 0;
        }
        total += item.price;
    });

    total = total > 0 ? total + ' руб.' : 'Бесплатно';
    console.log('Стоимость заказа: ' + total);
}

function printOrderTotalv2(responseString){

    if(responseString === "") throw "String is empty";

    var responseJSON = JSON.parse(responseString);
    total = responseJSON.reduce((total, item) => {
        return total + ((item.price === undefined) ? 0 : item.price)
    }, 0);
    total = total > 0 ? total + ' руб.' : 'Бесплатно';
    console.log('Стоимость заказа: ' + total);
}

<!-- printOrderTotal(responseString); -->
// printOrderTotalv2(responseString);