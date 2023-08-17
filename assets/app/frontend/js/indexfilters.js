$(document).ready(function () {
    $("#filtro").on("submit", function (e){
        e.preventDefault();
        let real_estate = document.getElementById('header').textContent;
        console.log(real_estate);
        let price = findFilters1('moneyIdInput', 'moneyId');
        let zone = findFilters1('zoneIdInput', 'zoneId');
        let bedrooms = findFilters1('bedroomsIdInput', 'bedroomsId');
        const route = Routing.generate('app_filterby', {real_estate: real_estate, area: 'default', price: price, zone: zone, bedrooms: bedrooms});
        const data = new FormData(this);
        $.ajax({
            url: route,
            data: data,
            processData: false,
            contentType: false,
            type: 'POST',
            success: function (data) {
                if (data.status === 'ok') {
                    // Ocultar todas las propiedades
                    $('.max-w-sm').hide();
                    for (let i = 0; i < data.filter.length; i++) {
                        var id ="#property-"+data.filter[i].propertyId;
                        $(id).show();
                    }

                    $("#filtro").trigger('reset');
                } else {
                    console.log(data.filter);
                    alert('Error');
                    console.log('algo falla');
                }
            }
        });
        return false;
    });
});


// function findFilters (input, datalistID) {
//     var element_input = document.getElementById(input);
//     var element_datalist = document.getElementById(datalistID);
//     var opSelected = element_datalist.querySelector(`[value="${element_input.value}"]`);
//     var value = opSelected.getAttribute('data-value');
//     console.log(value);
//     return value;
// }

function findFilters1 (input, datalistID) {
    //var element_input = document.getElementById(input);
    var element_datalist = document.getElementById(datalistID);
    let selected = element_datalist.value;

    return selected;
}