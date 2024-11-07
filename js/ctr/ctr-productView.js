$(document).ready(function(){
    //
});

function addToCart(){
    event.preventDefault();
    
    var product_id = $("#addToCart-productId").val();
    var price_id = $("#addToCart-priceId").val();
    var color_id = getColorValue().id;
    var quantity = $("#addToCart-quantity").val();
    var varnish = parseInt($("#addToCart-varnish").val()) > 0 ? 1 : 0;
    
    $.ajax({
        url: "./actions/cartActions.php",
        type: "POST",
        data: {
            action: 'add_cart',
            product_id: product_id,
            price_id: price_id,
            color_id: color_id,
            quantity: quantity,
            varnish: varnish
        },
        dataType: "json",
        success: function (data){
            $("#addToCartModal").modal('hide');
            alertMessage(data.message, data.res);
            showCartList();
        }
    });
}

function getColorValue(){
    var id = 0;
    var price = 0;
    if($("#addToCart-color").val() && $("#addToCart-color").val() !== '0'){
        var colorJSON = JSON.parse($("#addToCart-color").val());
        
        id = colorJSON.id;
        price = colorJSON.price;
    }

    return {
        id: id,
        price: price
    };
}

function setVariantActive(id){
    $(".variantBtn").removeClass("btn-dblue");
    $(".variantBtn").addClass("btn-white");

    $(`#variantBtn${id}`).removeClass("btn-white");
    $(`#variantBtn${id}`).addClass("btn-dblue");

    $(`#addToCart-variantBtn${id}`).removeClass("btn-white");
    $(`#addToCart-variantBtn${id}`).addClass("btn-dblue");
}

function minusQuantity(){
    var quantity = parseInt($("#addToCart-quantity").val());
    if(quantity > 1){
        $("#addToCart-quantity").val(quantity - 1);
    }

    updateTotal();
}

function addQuantity(){
    var quantity = parseInt($("#addToCart-quantity").val());
    $("#addToCart-quantity").val(quantity + 1);

    updateTotal();
}

function updateTotal(){
    var price = parseFloat($("#addToCart-price").val());
    var quantity = 0;
    var varnish = 0;
    var color = parseInt(getColorValue().price);
    var colorText = color > 0 ? `₱ ${numformat(color)}` : '';

    $("#varnishAddText").html('');
    $("#colorAddText").html('');

    if($("#addToCart-quantity").val() && $("#addToCart-quantity").val() !== '0'){
        quantity = parseInt($("#addToCart-quantity").val());
    }
    else{
        $("#addToCart-quantity").val(1);
        quantity = 1;
    }
    if($("#addToCart-varnish").val() > 0){
        varnish = parseFloat($("#addToCart-varnish").val());
        $("#varnishAddText").html(`₱ ${numformat(varnish)}`);
    }


    $("#addToCart-colorId").val(color);
    $("#colorAddText").html(colorText);

    var total = (price * quantity) + varnish + color;
    

    $("#addToCart-totalText").html(`₱ ${numformat(total)}`);
}

function setVariantDetails(id){
    setVariantActive(id);

    $.get("./fetch/getProducts.php", {
        request: 'price_details',
        price_id: id
    }, function(data){
        $("#addToCart-priceId").val(id);

        if(data.price == data.sale_price){
            $("#price").html(`₱ ${numformat(data.price)}`);

            $("#addToCart-priceText").html(`₱ ${numformat(data.price)}`);
            $("#addToCart-price").val(data.price);
        }
        else{
            $("#price").html(`₱ ${numformat(data.sale_price)}`);
            $("#price2").html(`₱ ${numformat(data.price)}`);

            $("#addToCart-priceText").html(`₱ ${numformat(data.sale_price)}`);
            $("#addToCart-price").val(data.sale_price);
        }

        $("#productSpecsDiv ul").html("");
        for(specs of data.price_specs){
            if(specs.value !== ""){
                $("#productSpecsDiv ul").append(`
                    <li>${specs.specs_name}:&emsp;${specs.value}</li>    
                `);
            }
        }
        updateTotal();
    });

    
} 

