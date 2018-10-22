jQuery(document).ready(function ($) {
    var priceProduct = '#price-product';
    // check attibute name code
    $(priceProduct + ' #code').on('change', function () {
        if($(this).val() != '') {
            $(this).attr('name', 'code');
        } else {
            $(this).attr('name', '');
        }
    });
    // check attibute name name
    $(priceProduct + ' #name').on('change', function () {
        if($(this).val() != '') {
            $(this).attr('name', 'name');
        } else {
            $(this).attr('name', '');
        }
    });
    // check attibute name category
    $(priceProduct + ' #category').on('change', function () {
        if($(this).val() != '') {
            $(this).attr('name', 'category');
        } else {
            $(this).attr('name', '');
        }
    });
    // check attibute name brand
    $(priceProduct + ' #brand').on('change', function () {
        if($(this).val() != '') {
            $(this).attr('name', 'brand');
        } else {
            $(this).attr('name', '');
        }
    });
});