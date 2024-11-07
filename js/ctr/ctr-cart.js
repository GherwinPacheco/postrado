function toggleCartTotalCollapse(){
    if($('#cartList .cartCheckbox:is(:checked)').length > 0){
        $("#cartTotal").collapse('show');
        $("#cartTotalMobile").collapse('show');
    }
    else{
        $("#cartTotal").collapse('hide');
        $("#cartTotalMobile").collapse('hide');

        $("#selectAll").prop('checked', false);
        $("#selectAllMobile").prop('checked', false);
    }
}

function keepCartCheck(selectedCarts){
    for(id of selectedCarts){
        $(`#cartItem-${id} .cartCheckbox`).attr('checked', true);
        $(`#cartItemMobile-${id} .cartCheckbox`).attr('checked', true);
    }
}

function selectAll(elem){
    var check = elem.is(':checked');
    $("#selectAll").prop('checked', check);
    $("#selectAllMobile").prop('checked', check);

    $(`#cartList .cartCheckbox:not(:disabled)`).each(function(){
        $(this).prop('checked', check);
    });

    $(`#cartListMobile .cartCheckbox:not(:disabled)`).each(function(){
        $(this).prop('checked', check);
    });

    updateTotal();
}


$("#selectAll").click(function(){
    selectAll($(this));
});

$("#selectAllMobile").click(function(){
    selectAll($(this));
});




function selectCartItem(id, type){
    //check default list checkbox
    // Check default list checkbox
    if(type === 'default'){
        var checkbox = $(`#cartItem-${id} .cartCheckbox`);
        var isChecked = checkbox.prop('checked');
        checkbox.prop('checked', isChecked);

        checkbox = $(`#cartItemMobile-${id} .cartCheckbox`);
        checkbox.prop('checked', isChecked);
    }
    else{
        var checkbox = $(`#cartItemMobile-${id} .cartCheckbox`);
        var isChecked = checkbox.prop('checked');
        checkbox.prop('checked', isChecked);

        checkbox = $(`#cartItem-${id} .cartCheckbox`);
        checkbox.prop('checked', isChecked);
    }
    


    updateTotal();
}


function minusQuantity(id, type){
    var elemId = type === 'default' ? `cartItem-${id}` : `cartItemMobile-${id}`;
    var quantity = parseInt($(`#${elemId} .productQuantity`).val());

    if(quantity > 1){
        $(`#cartItem-${id} .productQuantity`).val(quantity - 1);
        $(`#cartItemMobile-${id} .productQuantity`).val(quantity - 1);
    }

    updateCart(id, type);
}

function addQuantity(id, type){
    var elemId = type === 'default' ? `cartItem-${id}` : `cartItemMobile-${id}`;
    var quantity = parseInt($(`#${elemId} .productQuantity`).val());

    $(`#cartItem-${id} .productQuantity`).val(quantity + 1);
    $(`#cartItemMobile-${id} .productQuantity`).val(quantity + 1);

    updateCart(id, type);
}




function updateTotal(){
    var checkedCarts = [];
    $(`#cartList .cartCheckbox:is(:checked)`).each(function(){
        checkedCarts.push($(this).val());
    });


    var total = 0;
    for(id of checkedCarts){
        var subtotal = parseFloat($(`#cartItem-${id} .cartItemTotal`).val());
        total = total + subtotal;
    }
    $("#totalOrder").html(`₱ ${numformat(total)}`);
    $("#totalOrderMobile").html(`₱ ${numformat(total)}`);

    toggleCartTotalCollapse();
}

function deleteCart(id){
    var cart_id = id;
    if(id == undefined){
        var checkedCarts = [];
        $(`#cartList .cartCheckbox:is(:checked)`).each(function(){
            checkedCarts.push($(this).val());
        });

        cart_id = JSON.stringify(checkedCarts);
    }
    
    
    $.ajax({
        url: "./actions/cartActions.php",
        type: "POST",
        data: {
            action: 'delete_cart',
            cart_id: cart_id
        },
        dataType: "json",
        success: function (data){
            showCartList();
            getCart();
            updateTotal();
        },
        error: function(data){
            console.log(data)
        }
    });
}


function updateCart(id, type){
    
    var elemId = type === 'default' ? `cartItem-${id}` : `cartItemMobile-${id}`;
    console.log($(`#${elemId} .productVarnish`).html())
    var product_id = $(`#${elemId} .productId`).val();
    var price_id = $(`#${elemId} .productVariant`).val();
    var color_id = $(`#${elemId} .productColor`).val();
    var quantity = $(`#${elemId} .productQuantity`).val();
    var varnish = $(`#${elemId} .productVarnish`).val();

    //return true if cart is checked
    var checkedCarts = [];
    $(`#cartList .cartCheckbox:is(:checked)`).each(function(){
        checkedCarts.push($(this).val());
    });
    $.ajax({
        url: "./actions/cartActions.php",
        type: "POST",
        data: {
            action: 'update_cart',
            cart_id: id,
            product_id: product_id,
            price_id: price_id,
            color_id: color_id,
            quantity: quantity,
            varnish: varnish
        },
        dataType: "json",
        success: function (data){
            showCartList();
            getCart(checkedCarts);
            updateTotal();
            
        }
    });
}




function setupDefaultList(data){
    $("#cartList tbody").html('');
    for(cart of data){

        //setup the selection for variants
        var variantSelection = ``;
        if(cart.product_variants.length > 1){
            variantOptions = '';
            for(variant of cart.product_variants){
                variantOptions =  variantOptions.concat(`
                    <option value="${variant.id}">${variant.price_name}</option> 
                `);                
            }
            variantSelection = `
                <select class="productVariant form-control" name="productPrice" style="width: 150px" onchange="updateCart(${cart.id}, 'default')">
                    ${variantOptions}
                </select>
            `;
        }
        else{
            variantSelection = `<input type="hidden" class="productVariant" name="productPrice" value="${cart.product_variants[0].id}">`;
        }

        //setup the selection for colors
        var colorSelection = ``;
        colorOptions = '';
        for(color of cart.colors){
            colorOptions = colorOptions.concat(`
                <option value="${color.id}">${color.color_name}</option> 
            `);

            colorSelection = `
                <select class="productColor form-control d-inline-block mr-2" name="productColor" style="width: 150px" onchange="updateCart(${cart.id}, 'default')">
                    <option value="0">None</option> 
                    ${colorOptions}
                </select>
            `;
        }
        
        var unavailableDiv = cart.product_status == 0 ? `
            <div class="bg-dark d-flex align-items-center" style="height: 100%; width: 100%; opacity: 0.5; position: absolute;">
                <h6 class="text-white text-medium text-center w-100" style="opacity: 1">Unavailable</h6>
            </div>
        ` : '';

        var cartHTML = `
            <tr id="cartItem-${cart.id}" class="cartItem">
                <td>
                    <input type="checkbox" class="cartCheckbox" name="cartId[]" value="${cart.id}" onclick="selectCartItem(${cart.id}, 'default')">
                    <input type="hidden" class="productId" name="productId" value="${cart.product_id}">
                    
                    <div class="cart-image ml-3 rounded border d-inline-block" style="position: relative">
                        ${unavailableDiv}
                        <img src="./img/products/${cart.product_id}.png?t=${time}" alt="" style="width: 100%; height: 100%">
                    </div>
                    
                </td>
                <td>
                    <a class="cart-link" href="./ctr-productView.php?p=${cart.product_id}">
                        <h5 class="text-medium text-dark">${cart.product_name}</h5>
                    </a>
                    <span class="text-muted">₱ ${numformat(cart.variant_price)}</span>
                    ${variantSelection}
                </td>
                <th>
                    <div class="input-group mb-3 mx-auto" style="width: 100px">
                        <div class="input-group-prepend">
                            <button type="button" class="btn text-secondary" onclick="minusQuantity(${cart.id}, 'default')">
                                <i class="fa-solid fa-minus"></i>
                            </button>
                        </div>
                        <input type="number" class="productQuantity bg-white border-0 form-control text-center text-medium px-0" min="1" value="${cart.quantity}" required readonly>
                        <div class="input-group-prepend">
                            <button type="button" class="btn text-secondary" onclick="addQuantity(${cart.id}, 'default')">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </td>
                <td>        
                    <select class="productVarnish form-control mb-2 d-inline-block mr-2" name="productVarnish" style="width: 150px" onchange="updateCart(${cart.id}, 'default')">
                        <option value="1">Varnished</option>
                        <option value="0">No Varnish</option>
                    </select>
                    <span class="varnishPriceText text-muted">${cart.varnish === '1' ? `₱ ${numformat(cart.varnish_price)}` : ''}</span>

                    <br>

                    ${colorSelection}
                    <span class="colorPriceText text-muted">${cart.selected_color !== '0' ? `₱ ${numformat(cart.color_price)}` : ''}</span>
                </td>
                <td class="text-center">
                    <input type="hidden" class="cartItemTotal" name="cartItemTotal" value="${cart.total}">
                    <span class="cartItemTotalText">₱ ${numformat(cart.total)}</span>
                </td>
                <td class="text-center">
                    <button type="button" class="deleteBtn btn" onclick="deleteCart(${cart.id})">
                        <i class="fa-regular fa-trash-can"></i>
                    </button>
                </td>
            </tr>
        `;
        $("#cartList tbody").append(cartHTML);

        //set the values of the cart options
        $(`#cartItem-${cart.id} .productVariant`).val(cart.selected_variant);
        $(`#cartItem-${cart.id} .productVarnish`).val(cart.varnish);
        $(`#cartItem-${cart.id} .productColor`).val(cart.selected_color);
        var disabledProduct = (cart.product_status == 0);
        $(`#cartItem-${cart.id} .cartCheckbox`).attr('disabled', disabledProduct);
    }
    
}




function setupMobileList(data){
    $("#cartListMobile").html('');

    for(cart of data){

        //setup the selection for variants
        var variantSelection = ``;
        if(cart.product_variants.length > 1){
            variantOptions = '';
            for(variant of cart.product_variants){
                variantOptions =  variantOptions.concat(`
                    <option value="${variant.id}">${variant.price_name}</option> 
                `);                
            }
            variantSelection = `
                <select class="productVariant border-0 text-muted p-0" name="productPrice" onchange="updateCart(${cart.id}, 'mobile')">
                    ${variantOptions}
                </select>
            `;
        }
        else{
            variantSelection = `<input type="hidden" class="productVariant" name="productPrice" value="${cart.product_variants[0].id}">`;
        }
        



        //setup the selection for colors
        var colorSelection = ``;
        colorOptions = '';
        for(color of cart.colors){
            colorOptions = colorOptions.concat(`
                <option value="${color.id}">${color.color_name}</option> 
            `);

            colorSelection = `
                <select class="productColor border-0 text-muted p-0" name="productColor" onchange="updateCart(${cart.id}, 'mobile')">
                    <option value="0">None</option> 
                    ${colorOptions}
                </select>
            `;
        }

        var unavailableDiv = cart.product_status == 0 ? `
            <div class="bg-dark d-flex align-items-center" style="height: 100%; width: 100%; opacity: 0.5; position: absolute;">
                <h6 class="text-white text-medium text-center w-100" style="opacity: 1">Unavailable</h6>
            </div>
        ` : '';

        var cartHTML = `
            <div id="cartItemMobile-${cart.id}" class="cartItemMobile row border-bottom py-3" onclick="">
                <div class="col col-6">
                    <input type="checkbox" class="cartCheckbox d-inline-block mr-2" name="cartId[]" value="${cart.id}" onclick="selectCartItem(${cart.id}, 'mobile')">
                </div>
                <div class="col col-6 d-flex align-items-center justify-content-end">
                    <button type="button" class="deleteBtn btn" onclick="deleteCart(${cart.id})">
                        <i class="fa-regular fa-trash-can"></i>
                    </button>
                </div>
                
                <div class="col col-3">
                    <input type="hidden" class="productId" name="productId" value="${cart.product_id}">
                    
                    <div class="cart-image rounded border" style="position: relative">
                        ${unavailableDiv}
                        <img src="./img/products/${cart.product_id}.png?t=${time}" alt="" style="width: 100%; height: 100%">
                    </div>
                </div>
                <div class="col col-9 pb-2">
                    <div class="row pb-2">
                        <div class="col col-6">
                            <a class="cart-link" href="./ctr-productView.php?p=${cart.product_id}">
                                <span class="text-medium text-dark">${cart.product_name}</span>
                            </a>
                        </div>
                        <div class="col col-6">
                            <span class="text-muted">₱ ${numformat(cart.variant_price)}</span>
                        </div>
                        <div class="col col-12 pb-2">
                            ${variantSelection}
                        </div>
                        <div class="col col-12 pb-2">
                            <span class="text-muted d-inline-block">&nbsp;Qty:&nbsp;</span>
                            <button type="button" class="text-muted border-0" onclick="minusQuantity(${cart.id}, 'mobile')"><i class="fa-solid fa-minus"></i></button>
                            <input type="number" class="productQuantity bg-transparent text-muted border-0 text-center" value="${cart.quantity}" style="width: 20px; overflow: visible" readonly required>
                            <button type="button" class="text-muted border-0" onclick="addQuantity(${cart.id}, 'mobile')"><i class="fa-solid fa-plus"></i></button>
                        </div>
                        <div class="col col-5">
                            <select class="productVarnish border-0 text-muted p-0" name="productVarnish" onchange="updateCart(${cart.id}, 'mobile')">
                                <option value="1">Varnished</option>
                                <option value="0">No Varnish</option>
                            </select>
                        </div>
                        <div class="col col-7">
                            ${colorSelection}
                        </div>
                    </div>

                    
                    
                    

                </div>
                <div class="col col-12">
                    <input type="hidden" class="cartItemTotal" name="cartItemTotal" value="${cart.total}">
                    <span class="cartItemTotalText float-right text-medium">Total:&emsp;<span class="text-primary">₱ ${numformat(cart.total)}</span></span>
                </div>
            </div>
        `;

        $("#cartListMobile").append(cartHTML);

        //set the values of the cart options
        $(`#cartItemMobile-${cart.id} .productVariant`).val(cart.selected_variant);
        $(`#cartItemMobile-${cart.id} .productVarnish`).val(cart.varnish);
        $(`#cartItemMobile-${cart.id} .productColor`).val(cart.selected_color);
        var disabledProduct = (cart.product_status == 0);
        $(`#cartItemMobile-${cart.id} .cartCheckbox`).attr('disabled', disabledProduct);
    }

    
}

function getCart(selectedCarts){
    $.ajax({
        url: "./fetch/getCart.php",
        type: "GET",
        data: {
            request: 'carts_data',
            orderBy: 'date_created',
            orderMethod: 'DESC'
        },
        dataType: "json",
        success: function (data){
            setupDefaultList(data);
            setupMobileList(data);
            if(selectedCarts !== undefined){
                keepCartCheck(selectedCarts);
            }
            updateTotal();

            //data.cart_list_mobile
        }
    });
}



$(document).ready(function(){
    getCart();
});