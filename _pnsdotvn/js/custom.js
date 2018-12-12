function loadItem(processPage, arrDIVContainer, param) {
    $.ajax({
        url: processPage, data: param, dataType: "HTML", success: function (html) {
            $("#" + arrDIVContainer).html(html);
            $("#" + arrDIVContainer).fadeIn(500)
        }
    })
}

function loadModule(processPage, arrayDIVContainer, param) {
    $("#" + arrayDIVContainer).hide(10);
    $("#" + arrayDIVContainer).load(processPage, param, function callback(data, status) {
        $("#" + arrayDIVContainer).fadeIn(500)
    })
}

function getModule(processPage, arrayDIVContainer, param, type) {
    $(arrayDIVContainer).html("<td colspan='12' style='background-color:#EBF1F6;text-align:right;padding:6px'>Please wait...</td>");
    $.get(processPage, param, function (data, status) {
        if (status == "success") {
            $(arrayDIVContainer).html(data)
        }
    }, type)
}

jQuery(document).ready(function ($) {
    var $wholesalePrice = $('#wholesale-price');
    $wholesalePrice.on('input', function () {
        $('.wholesale-price').css('display', 'none');
    });
    $(document).on('click', '#add-wholesale', function () {
        var productId = $(this).data('id');
        var quantityFrom = $('#quantity-from option:selected').val();
        var quantityTo = $('#quantity-to option:selected').val();

        if($wholesalePrice.val() == 0 || !$.isNumeric($wholesalePrice.val())) {
            $('.wholesale-price').css('display', 'block');
        }
        $.ajax({
            url: 'wholesale.php',
            type: 'get',
            data: {
                'product_id': productId,
                'quantity_from': quantityFrom,
                'quantity_to': quantityTo,
                'wholesale_price': $wholesalePrice.val()
            },
            dataType: 'json',
            success: function (result, status) {
                if (result.wholesale.length > 0) {
                    $('div#wholesale-data table').html('');
                    var html = '<tr>';
                    html += '<th>Số lượng</th>';
                    html += '<th>Số lượng</th>';
                    html += '<th></th>';
                    html += '</tr>';
                    $.each(result.wholesale, function (i, item) {
                        html += '<tr class="content-sale">';
                        html += '<td>' + item.quantity_from + ' -> ' + item.quantity_to + '</td>';
                        html += '<td>' + item.wholesale_price + '</td>';
                        html += '<td><a href="javascript:void(0);" class="delete-whole-sale" data-sid="' + item.id + '" data-id="'+result.product_id+'">Xóa</a></td>';
                        html += '</tr>';
                    })
                    $('div#wholesale-data table').append(html);
                } else {
                    $('div#wholesale-data table').html('');
                }
                $('#quantity-from').val(0);
                $('#quantity-to').val(0);
                $wholesalePrice.val('');
            }
        });
    });
    $(document).on('click', '.delete-whole-sale', function () {
        $.ajax({
            url: 'wholesale.php',
            type: 'get',
            data: {
                'sid': $(this).data('sid'),
                'product_id': $(this).data('id'),
                'action': 'delete'
            },
            dataType: 'json',
            success: function (result, status) {
                if (result.wholesale.length > 0) {
                    $('div#wholesale-data table').html('');
                    var html = '<tr>';
                    html += '<th>Số lượng</th>';
                    html += '<th>Số lượng</th>';
                    html += '<th></th>';
                    html += '</tr>';
                    $.each(result.wholesale, function (i, item) {
                        html += '<tr class="content-sale">';
                        html += '<td>' + item.quantity_from + ' -> ' + item.quantity_to + '</td>';
                        html += '<td>' + item.wholesale_price + '</td>';
                        html += '<td><a href="javascript:void(0);" class="delete-whole-sale" data-sid="' + item.id + '" data-id="'+result.product_id+'">Xóa</a></td>';
                        html += '</tr>';
                    })
                    $('div#wholesale-data table').html(html);
                } else {
                    $('div#wholesale-data table').html('');
                }
                $('#quantity-from').val(0);
                $('#quantity-to').val(0);
                $wholesalePrice.val('');
            }
        });
    });
    $('.delete-doc').on('click', function () {
        var $this = $(this)
        $.ajax({
            url: 'wholesale.php',
            type: 'get',
            data: {
                'product_id': $this.data('pid'),
                'pos': $this.data('position'),
                'action': 'delete-doc'
            },
            dataType: 'json',
            success: function (result) {
                if(result.message == 'success') {
                    $this.parent().parent().remove();
                }
            }
        });
    });
    if($('#type').length) {
        $('.content-label').hide();
        $('.content').hide();
        var renderCkeditor = function(type) {
            if(type == 'ckeditor') {
                var editor = CKEDITOR.replace(type, {
                    language: 'vi',
                    toolbar: 'Default',
                    height: '300px',
                    width: '100%',
                    filebrowserBrowseUrl: '../../plugins/editors2/ckfinder/ckfinder.html',
                    filebrowserImageBrowseUrl: '../../plugins/editors2/ckfinder/ckfinder.html?type=Images',
                    filebrowserFlashBrowseUrl: '../../plugins/editors2/ckfinder/ckfinder.html?type=Flash',
                    filebrowserImageUploadUrl: '../../plugins/editors2/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
                    filebrowserFlashUploadUrl: '../../plugins/editors2/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
                });
                CKFinder.setupCKEditor(editor, '../');
            } else {
                CKEDITOR.instances['ckeditor'].destroy()
                $('#ckeditor').hide();
            }
        };
        $('#type').on('change', function () {
            $('.content-label').show();
            if($(this).val() != '') {
                $('.content').hide();
                $('.content').removeAttr('name');
                $('#' + $(this).val()).show();
                $('#' + $(this).val()).attr('name', 'content');
            } else {
                $('.content-label').hide();
            }
            renderCkeditor($(this).val());
        });
        $("form#frm").validate({
            rules: {
                name: "required",
                position: "required",
                type: "required"
            },
            // Specify validation error messages
            messages: {
                name: "Vui lòng nhập tên",
                position: "Vui lòng nhập vị trí",
                type: "Vui lòng chọn type"
            },
            // Make sure the form is submitted to the destination defined
            // in the "action" attribute of the form when valid
            submitHandler: function (form) {
                form.submit();
            }
        });
    }
    $('.checkAll').on('click', function () {
        if($(this).is(':checked')) {
            $('form#frm input[name="id[]"]').remove();
            $(".checkDelete").each(function () {
                $("form#frm").append('<input type="hidden" value="'+$(this).val()+'" name="id[]">');
            });
        } else {
            $('form#frm input[name="id[]"]').remove();
        }
    });
    $(".checkDelete").on('click', function () {
        if($(this).is(':checked')) {
            $("form#frm").append('<input type="hidden" value="'+$(this).val()+'" name="id[]">');
        }
    });
    $('.deleleAction a').on('click', function () {
        $("form#frm").append('<input type="hidden" value="delete" name="delete">');
        $('form#frm').submit();
    });
    $('.quonte-price').on('change', function() {
        if($(this).val() == '') {
            $(this).parent().find('label').addClass('error').removeClass('hidden');
        } else {
            $(this).parent().find('label').addClass('hidden').removeClass('error');
        }
    });
    $('.quonte-total').on('change', function() {
        if($(this).val() == '') {
            $(this).parent().find('label').addClass('error').removeClass('hidden');
        } else {
            $(this).parent().find('label').addClass('hidden').removeClass('error');
        }
    });
    $('#exportPdf').on('click', function() {
        var priceEmpty = false;
        var dataPrice = [];
        $('.quonte-price').each(function() {
            if($(this).val() == '') {
                $(this).parent().find('label').addClass('error').removeClass('hidden');
                priceEmpty = true;
            } else {
                var item = {};
                item.id = $(this).data('id');
                item.price = $(this).val();
                dataPrice.push(item);
            }
        });
        if(priceEmpty == true) {
            return false;
        }
        var totalEmpty = false;
        var dataTotal = [];
        $('.quonte-total').each(function() {
            if($(this).val() == '') {
                $(this).parent().find('label').addClass('error').removeClass('hidden');
                totalEmpty = true;
            } else {
                var item = {};
                item.id = $(this).data('id');
                item.total = $(this).val();
                dataTotal.push(item);
            }
        });
        if(totalEmpty == true) {
            return false;
        }
        var dataQty = [];
        $('.qty').each(function() {
            var item = {};
            item.id = $(this).data('id');
            item.qty = $(this).val();
            dataQty.push(item);
        });
        var dataName = [];
        $('.name').each(function() {
            var item = {};
            item.id = $(this).data('id');
            item.name = $(this).val();
            dataName.push(item);
        });
        var dataImg = [];
        $('.img').each(function() {
            var item = {};
            item.id = $(this).data('id');
            item.img = $(this).val();
            dataImg.push(item);
        });
        $.ajax({
            url: 'print-pdf.php',
            type: 'post',
            data: {
                "price" : dataPrice,
                "total": dataTotal,
                "quantity": dataQty,
                "image": dataImg,
                "name": dataName,
                "tax": $('#tax').val()
            },
            dataType: 'json',
            success: function (result, status) {
                
            }
        });
    });
    

});