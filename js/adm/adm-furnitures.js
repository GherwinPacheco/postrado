var pCount;

function uncheckCheckbox(){
    $(".materialCheckbox").prop('checked', false);
}

function countCheckedMaterial(mode){
    var mCount = $(`#${mode}-materialList input[type=checkbox]:checked`).length;
    $(`#${mode}-materialListHeader`).html(`Product Materials (${mCount})`);
}


function togglePriceSpecsForm(){
    $(".priceSpecsFormCollapse").collapse('hide');
}

function removeProductPrice(id){
    if($('.priceDiv').length > 1){
        $(`#priceDiv${id}`).remove();
        $(`#priceSpecsCollapse${id}`).remove();
    }
}


function resetPrices(){
    pCount = 0;
    $(`.priceCount`).val(0);
    $('#addProduct-priceList').html('');
    $('#editProduct-priceList').html('');
}


function setupProductPrices(product, mode){
    $.get("./fetch/getProducts.php", {
        request: 'product_details',
        product_id: product
    }, function (data){

        for(p of data.prices){
            var priceId = p.id;
            addProductPrice(
                product, 
                {
                    id: priceId, 
                    name: p.price_name, 
                    value: p.price
                }, 
                mode
            );
            
        }
    });
    
}

//reset the price on addProduct and change specs
//re-add the prices on editProduct and change the specs
function changeCategorySpecs(mode){
    var product = $(`#${mode}-productId`).val();
    if(product){
        resetPrices();
        setupProductPrices(product, mode);
    }
    else{
        resetPrices();
        addProductPrice(mode);
    }
}





function setupPriceSpecs(product, price, category, cnum){
    
    $.get("./fetch/getProducts.php", {
            request: 'price_specs_data',
            product_id: product,
            price_id: price,
            category_id: category
        },
        function (data){
            $(`#priceSpecsCollapse${cnum}`).html('');
            if(category){
                for(sp of data){
                    $(`#priceSpecsCollapse${cnum}`).append(`
                        <div class="row pb-2">
                            <input type="hidden" name="specsId${cnum}[]" value="${sp.specs_id}">
                            <div class="col col-6" style="vertical-align: middle">${sp.specs_name}:</div>
                            <div class="col col-6">
                                <input type="text" class="specs-form w-100" name="specsValue${cnum}[]" value="${sp.value}" placeholder="value">
                            </div>
                        </div> 
                    `);
                }
            }
            else{
                $(`#priceSpecsCollapse${cnum}`).html('No category has been set');
            }
        }
    );
}

function getCategoryValue(mode){
    var category = $(`#${mode}-productCategory`).val();
    category = category ? category : '';
    return category;
}

function addProductPrice(x, y ,z){
    pCount++;
    var productId, priceId, name, value, mode = '';
    
    if (arguments.length == 3) { //if argument 1 and 2 are undefined
        product = x;
        priceId = y.id;
        name = y.name;
        value = y.value;
        mode = z;
    } 
    else { //if only argument 3 has value
        productId = '';
        priceId = '';
        name = '';
        value = '';
        mode = x;
    }
    

    $(`#${mode}-priceCount`).val(pCount);

    $(`#${mode}-priceList`).append(`
        <div class="row priceDiv mb-2" id="priceDiv${pCount}">
            <div class="col col-5">
                <input type="text" name="priceName${pCount}" class="form-control" value="${name}" placeholder="Variant Name" required>
            </div>
            <div class="col col-4">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">₱</span>
                    </div>
                    <input type="number" name="price${pCount}" class="form-control" value="${value}" placeholder="0.00" min="1" step="0.01" required>
                </div>
            </div>
            <div class="col col-2">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-white text-primary" data-toggle="collapse" data-target="#priceSpecsCollapse${pCount}" onclick="togglePriceSpecsForm()">
                        <i class="fa-solid fa-chevron-down"></i>
                    </button>
                    <button type="button" class="btn btn-white text-danger" onclick="removeProductPrice(${pCount})">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="collapse priceSpecsFormCollapse border-left pl-3 py-2 ml-3" id="priceSpecsCollapse${pCount}">
            
        </div>
    `);

    var category = getCategoryValue(mode);
    setupPriceSpecs(productId, priceId, category, pCount);
}



//onclick function of Add Product Button
$("#add-furniture-btn").click(function(){
    resetPrices();
    addProductPrice('addProduct');
});




function setupProductForm(product, mode){
    $.get("./fetch/getProducts.php", {
        request: 'product_details',
        product_id: product
    }, function (data){
            var d = new Date();
            
            $(`#${mode}-productId`).val(data.id);
            $(`#${mode}-productName`).val(data.product_name);
            $(`#${mode}-productNameText`).html(data.product_name);
            $(`#${mode}-productCategory`).val(data.category);
            $(`#${mode}-productionDuration`).val(data.production_duration);
            $(`#${mode}-productImg`).attr("src", `./img/products/${data.id}.png?t=${time}`);
            $(`#${mode}-description`).val(data.description);
            $(`#${mode}-sale`).val(data.sale);

            var productId = data.id;

            resetPrices();

            for(p of data.prices){
                var priceId = p.id;
                addProductPrice(
                    productId, 
                    {
                        id: priceId, 
                        name: p.price_name, 
                        value: p.price
                    }, 
                    mode
                );
            }

            uncheckCheckbox();
            for(m of data.materials){
                $(`#${mode}-materialList input[value=${m.material_id}]`).prop("checked", true);
                countCheckedMaterial(mode);
            }
    });
}




//toggle prices on viewProductModal
function togglePriceSpecsDetails(){
    $("#viewProduct-priceList .collapse").collapse('hide');
}



//get the data of products
function viewProductsData(product){
    $.get("./fetch/getProducts.php", {
        request: 'product_details',
        product_id: product
    }, function (data){

        var pricesHTML = '';
        for(p of data.prices){

            var specsHTML = '';
            for(sp of p.price_specs){
                if(sp.value !== ''){
                    specsHTML = specsHTML.concat(`
                        <li>
                            <span class="d-inline-block" style="min-width: 50%">
                                ${sp.specs_name}:
                            </span>
                            <span>
                                ${sp.value}
                            </span>
                        </li>    
                    `);
                }
                if(specsHTML == ''){
                    specsHTML = 'No Specifications Specified'
                }
            }


            pricesHTML = pricesHTML.concat(`
                <li>
                    <span class="d-inline-block text-left" style="min-width: 80px">${p.price_name}</span>
                    <span class="d-inline-block text-left" style="min-width: 80px">₱ ${numformat(p.price)}</span>
                    <a id="viewProduct-prize${p.id}" class="price-view ml-3 text-italic" data-toggle="collapse" href="#viewProduct-prize${p.id}-collapse" onclick="togglePriceSpecsDetails()">
                        View
                    </a>
                    <div class="collapse mb-3" id="viewProduct-prize${p.id}-collapse">
                        <ul>
                            ${specsHTML}
                        </ul>
                    </div>
                </li>    
            `);
        }

        var materialsHTML = '';
        if(data.materials.length > 0){
            for(m of data.materials){
                var statusHTML = '';
                if(m.status == 2){
                    statusHTML = '&emsp;<span class="badge badge-secondary">Low Stocks</span>';
                }
                else if(m.status == 3){
                    statusHTML = '&emsp;<span class="badge badge-dark">Out of Stock</span>';
                }
                materialsHTML = materialsHTML.concat(`
                    <li>${m.material_name}${statusHTML}</li>
                `);
            }
        }
        else{
            materialsHTML = 'No Materials Selected';
        }
        
        var d = new Date();
        let time = d.getTime();

        $("#viewProduct-productName").html(data.product_name);
        $("#viewProduct-category").html(titleCase(data.category_name));
        $("#viewProduct-productImg").attr("src", `./img/products/${data.id}.png?t=${time}`);
        $("#viewProduct-productDescription").html(`&emsp;${data.description}`);
        $("#viewProduct-priceList").html(pricesHTML);
        $("#viewProduct-materialList").html(materialsHTML);

        $("#viewProduct-dateCreated").html(data.date_created);
        $("#viewProduct-timeCreated").html(data.time_created);
        $("#viewProduct-addedBy").html(data.username);
    });
}




function productsTableHTML(data){
    $("#furnitures-table tbody").html('');
    $("#furniture-table-mobile").html('');

    if(data.length > 0){
        for(prod of data){
            var productId = prod.id;
            var priceText = '';
            var priceTextMobile = '';
            for(p of prod.prices){
                priceText = priceText.concat(`₱ ${numformat(p.price)}<br>`);
                priceTextMobile = priceTextMobile.concat(`₱ ${numformat(p.price)}&emsp;`);
            }
    
            var statusColor = prod.status == 1 ? 'primary' : 'dark';
            var statusText = prod.status == 1 ? 'Available' : 'Unavailable';
    
            var dropdownHTML = `
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#viewProductModal" onclick="viewProductsData(${productId})">View Furniture</a>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#editProductModal" onclick="setupProductForm(${productId}, 'editProduct')">Edit Details</a>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#setSaleModal" onclick="setupProductForm(${productId}, 'setSale')">Set Sale</a>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#archiveProductModal" onclick="setupProductForm(${productId}, 'archiveProduct')">Archive Furniture</a>
            `;
    
            $("#furnitures-table tbody").append(`
                <tr>
                    <th class="text-center">
                        ${prod.num}
                    </th>
                    <td class="text-center">
                        <img src="./img/products/${productId}.png?t=${time}" alt="" style="">
                    </td>
                    <td class="text-center">
                        ${prod.product_name}
                    </td>
                    <td class="text-center">${prod.category_name}</td>
                    <td class="text-center">
                        ${priceText}
                    </td>
                    <th class="text-center"><span class="badge badge-pill badge-${statusColor}">${statusText}</span></th>
                    <th class="text-center">
                        <div class="btn-group dropleft">
                            <button type="button" class="btn btn-white dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <div class="dropdown-menu">
                                ${dropdownHTML}
                            </div>
                        </div>
                    </th>
                </tr>
            `);
    
            $("#furniture-table-mobile").append(`
                <div class="row border-bottom py-3">
                    <div class="col col-3">
                        <img class="" src="./img/products/${productId}.png?t=${time}" alt="">
                    </div>
                    <div class="col col-7">
                        <div class="row">
                            <div class="col col-7 vertical-middle">
                                ${prod.product_name}
                            </div>
                            <div class="col col-5 text-center">
                                <span class="badge badge-pill badge-${statusColor}">${statusText}</span>
                            </div>
                            <div class="col col-12 text-secondary">
                                ${prod.category_name}
                            </div>
                            <div class="col col-12 text-secondary">
                                ${priceTextMobile}
                            </div>
                        </div>
                    </div>
                    <div class="col col-2">
                        <div class="btn-group dropleft">
                            <button type="button" class="btn btn-white dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <div class="dropdown-menu">
                                ${dropdownHTML}
                            </div>
                        </div>
                    </div>
                </div>
            `);
        }
    }
    else{
        $("#furnitures-table tbody").html(`
            <tr>
                <td colspan="7" class="text-center">
                    No Results
                </td>
            </tr>    
        `);


        $("#furniture-table-mobile").html(`
            <p class="text-center">No Results</p>
        `);
    }
    
}


//displays the table of products
function setupProductsTable(page){
    var limit = 10;
    var search = $("#search").val();
    var status = $("#furniture-status").val();
    var category = $("#furniture-category").val();

    //for default table
    $.get("./fetch/getProducts.php", {
        request: 'products_data',
        limit: limit,
        search: search,
        category: category,
        status: status,
        archived: 0,
        orderBy: 'date_created',
        orderMethod: 'desc',
        page: page
    },
        function (data){
            productsTableHTML(data.products);

            //generate pagination
            $("#furniture-table-pagination .pagination").html(
                generatePagination(data.total_pages, page, 'setupProductsTable')
            );
        }
    );
    
}


$('.ressetable-modal').on('hidden.bs.modal', function (e) {
    resetPrices();
    uncheckCheckbox();
    countCheckedMaterial('addProduct');
    countCheckedMaterial('editProduct');
});

$("#search").on("input", function(){
    setupProductsTable(1);
});

$("#furniture-status").change(function(){
    setupProductsTable(1);
});


$("#furniture-category").change(function(){
    setupProductsTable(1);
});


$(document).ready(function(){
    setupProductsTable(1);
    pCount = 0;
});


