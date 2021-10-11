// Ajax queries
$.ajaxSetup({
    headers: {
        'X-CSRF-Token': $('meta[name="_token"]').attr('content')
    }
});

$('.main-wrapper').scroll(function() {
    if ($(this).scrollTop() > 135) {
        $('.page-actions').addClass('fixed');
    } else {
        $('.page-actions').removeClass('fixed');
    }
});


$(document).on('keydown', 'input', function(event){
    $(this).parents('.item-row').addClass('changed');
});

$(document).on('change', 'select', function(event){
    $(this).parents('.item-row').addClass('changed');
});

$('.save-upload').on('click', function(event){
    $btnOption = $(this).data();

    $('.item-row').each(function(key, value){
        if ($('.item-row').eq(key).hasClass('changed')) {

            $obj = $('.item-row').eq(key);
            var id = $obj.attr('data-id');
            var catID = $('.cat-id').val();

            $name = $('.item-row').eq(key).find('.input-name');
            var dataName = {};
            $name.each(function(k, v){
                lang = $name.eq(k).attr('data-lang');
                var val = $name.eq(k).val();
                dataName[lang] = val;
            });

            $body = $('.item-row').eq(key).find('.input-body');
            var dataBody = {};
            $body.each(function(k, v){
                lang = $body.eq(k).attr('data-lang');
                var val = $body.eq(k).val();
                dataBody[lang] = val;
            });

            $props = $('.item-row').eq(key).find('.prop-input');
            var dataProp = {};
            $props.each(function(k, v){
                var val = $props.eq(k).val();
                var data = $props.eq(k).attr('data-id');
                dataProp[data] = val;
            });

            $propsText = $('.item-row').eq(key).find('.input-prop-text');
            var dataPropText = {};
            $propsText.each(function(k, v){
                var val = $propsText.eq(k).val();
                var data = $propsText.eq(k).attr('data-id');
                dataPropText[data] = val;
            });

            // console.log(dataPropText);
            $brand = $('.item-row').eq(key).find('.input-brand_id').val();
            $promo = $('.item-row').eq(key).find('.input-promo_id').val();
            $price = $('.item-row').eq(key).find('.input-price').val();
            $price_lei = $('.item-row').eq(key).find('.input-price_lei').val();
            $discount = $('.item-row').eq(key).find('.input-discount').val();
            $code = $('.item-row').eq(key).find('.input-code').val();
            $stock = $('.item-row').eq(key).find('.input-stock').val();
            $video = $('.item-row').eq(key).find('.input-video').val();
            $set = $('.item-row').eq(key).find('.input-set').val();

            $brand = $('.item-row').eq(key).find('.input-brand_id').val();
            $set = $('.item-row').eq(key).find('.input-set_id').val();
            $promo = $('.item-row').eq(key).find('.input-promo_id').val();

            // console.log($set);

            $.ajax({
                type: "POST",
                url: '/back/save-products',
                data: {
                    id: id,
                    catID : catID,
                    name: JSON.stringify(dataName),
                    body: JSON.stringify(dataBody),
                    brand: $brand,
                    promo: $promo,
                    price: $price,
                    price_lei: $price_lei,
                    discount: $discount,
                    code: $code,
                    stock: $stock,
                    video: $video,
                    set: JSON.stringify($set),
                    props: JSON.stringify(dataProp),
                    propsText: JSON.stringify(dataPropText),
                },
                beforeSend: function(){
                    $('#loading-image').show();
                },
                complete: function(){
                   $('#loading-image').hide();
                },
                success: function(data) {
                    if (data != 'false') {
                        if (typeof id == 'undefined') {
                            $('.ajax-response').html(data);
                            if ($btnOption == "redirect-cat") {
                                $('.category-select').val();
                            }
                        }
                    }
                }
            });
        }else{

        }

    });

    $('.item-row').removeClass('changed');
    $id = $(this).attr('data-id');

})


$(document).on('click', '.save-images-btn', function(e){
        e.preventDefault();
        var formData = new FormData($(this).parents('form')[0]);
        var id = $(this).attr('data');

        $.ajax({
            url: '/back/upload-files',
            type: 'POST',
            xhr: function() {
                var myXhr = $.ajaxSettings.xhr();
                return myXhr;
            },
            beforeSend: function(){
                $('#loading-image').show();
            },
            complete: function(){
               $('#loading-image').hide();
            },
            success: function (data) {
                $('.images-live-update' + id).html(data);
            },
            data: formData,
            cache: false,
            contentType: false,
            processData: false
        });
        return false;
});

$(document).on('click', '.save-subproduct-images', function(e){
        e.preventDefault();
        var formData = new FormData($(this).parents('form')[0]);
        var id = $(this).attr('data');

        $.ajax({
            url: '/back/upload-subproduct-files',
            type: 'POST',
            xhr: function() {
                var myXhr = $.ajaxSettings.xhr();
                return myXhr;
            },
            beforeSend: function(){
                $('#loading-image').show();
            },
            complete: function(){
               $('#loading-image').hide();
            },
            success: function (data) {
                $('.response-sub-images' + id).html(data);
            },
            data: formData,
            cache: false,
            contentType: false,
            processData: false
        });
        return false;
});

$(document).on('click', '.submitSubproducts', function(e){
        e.preventDefault();
        var form = $(this).parents('form').serialize();
        var productId = $(this).attr('data-product');
        var _this = $(this);
        $.ajax({
            type: "POST",
            url: '/back/autoupload/subproducts',
            data: { form: form, productId : productId},
            beforeSend: function(){
                $('#loading-image').show();
            },
            complete: function(){
               $('#loading-image').hide();
            },
            success: function(data) {
                if (data != 'false') {
                    var message = "Schimbarile au fost efectuate.";
                    _this.prev('.message-sub').text(message);
                }
            }
        });
        return false;
});

$(document).on('click', '.submitSetProduct', function(e){
        e.preventDefault();
        var form = $(this).parents('form').serialize();
        var productId = $(this).attr('data-product');
        var _this = $(this);

        $.ajax({
            type: "POST",
            url: '/back/autoupload/submitSetProduct',
            data: { form: form, productId : productId},
            beforeSend: function(){
                $('#loading-image').show();
            },
            complete: function(){
               $('#loading-image').hide();
            },
            success: function(data) {
                if (data != 'false') {
                    var message = "Schimbarile au fost efectuate.";
                    _this.prev('.message-sub').text(message);
                }
            }
        });
        return false;
});

$(document).on('click', '.submitCollectionProduct', function(e){
        e.preventDefault();
        var form = $(this).parents('form').serialize();
        var productId = $(this).attr('data-product');
        var _this = $(this);

        $.ajax({
            type: "POST",
            url: '/back/autoupload/submitCollectionProduct',
            data: { form: form, productId : productId},
            beforeSend: function(){
                $('#loading-image').show();
            },
            complete: function(){
               $('#loading-image').hide();
            },
            success: function(data) {
                if (data != 'false') {
                    var message = "Schimbarile au fost efectuate.";
                    _this.prev('.message-sub').text(message);
                }
            }
        });
        return false;
});

$(document).on('click', '.load-more', function(e){
        var url = $(this).attr('data-next');
        var catID = $('.cat-id').val();

        $.ajax({
            type: "POST",
            url: url,
            data: {'category' : catID },
            beforeSend: function(){
                $('#loading-image').show();
            },
            complete: function(){
               $('#loading-image').hide();
            },
            success: function(data) {
                $('.new-row').before(data.html);
                $('.load-more').attr('data-next', data.url);

                if (data.lastItem == 'true') {
                    $('.load-more').remove();
                }
            }
        });
        return false;
});

$(document).on('click', '.subprod-action', function(e){
        var catID = $('.cat-id').val();
        var productId =  $(this).data('product');
        var target = $(this).data('target');

        $.ajax({
            type: "POST",
            url: '/back/autoupload/subproducts/update',
            data: { catID : catID, productId : productId, target : target},
            beforeSend: function(){
                $('#loading-image').show();
            },
            complete: function(){
               $('#loading-image').hide();
            },
            success: function(data) {
                $(target).html(data);
            }
        });
        return false;
});



$(document).on('click', '.subproducts-btn', function(e){
    $('.message-sub').text('');
});

$(document).click(function() {
    $('.message-sub').text('');
});

window.onbeforeunload = function() {
    $('.item-row').each(function(key, value){
        if ($('.item-row').eq(key).hasClass('changed')) {
            var Ans = confirm("Are you sure you want change page!");
            // console.log('mkdm');
            // return "Schimarile nu au fost salvate";
        };
    });
    // return "Schimarile nu au fost salvate";

   //if we return nothing here (just calling return;) then there will be no pop-up question at all
   //return;
};
