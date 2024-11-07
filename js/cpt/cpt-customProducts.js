
function setCustomDetails(id){
    $.ajax({
        url: "fetch/getCustoms.php",
        data: {
            request: 'customs_data',
            custom_id: id
        },
        dataType: 'json', // Ensure the data type is set to JSON
        success: function(data) {
            for (item of data) {

                $("#updateCustom-specsDiv").html("");

                //view details modal
                var img = '';
                var isFirst = true;
                for (let i = 1; i <= item.image_count; i++) {
                    img += `
                        <div class="carousel-item ${isFirst ? 'active' : ''}">
                            <img src="./img/custom/${item.id}_${i}.png" class="d-block w-100" alt="">
                        </div>
                    `;
                    isFirst = false;
                }
                $("#viewCustom-carouselDiv").html(`
                    <div id="carouselExampleFade" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        ${img}
                    </div>
                    <button class="carousel-control-prev" type="button" data-target="#carouselExampleFade" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-target="#carouselExampleFade" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </button>
                    </div>
                `);
    
                // Initialize the carousel manually if needed
                $('#carouselExampleFade').carousel();
    
                $("#viewCustom-productName").html(item.product_name);
                $("#viewCustom-category").html(item.category_name);
                $("#viewCustom-description").html(item.description);
                $("#viewCustom-quantity").html(`×${item.quantity}`);
                $("#viewCustom-woodType").html(item.wood_name);
                $("#viewCustom-varnish").html(item.varnish == 1 ? `Varnished<br>(₱ ${numformat(item.varnish_price)})` : 'No Varnish');
                $("#viewCustom-color").html(item.color_id != 0 ? `${item.color_name}` : 'No Color');
                $("#viewCustom-colorPrice").html(item.color_id != 0 ? `(₱ ${numformat(item.color_price)})` : '');
                $("#viewCustom-serviceOption").html(item.pickup_method);
                $("#viewCustom-dateCreated").html(formatDate(item.date_created, "F d, Y"));
                $("#viewCustom-timeCreated").html(item.time_created);
                $("#viewCustom-addedBy").html(item.username);

                $("#updatedViewCustom-productName").html(item.product_name);
                $("#updatedViewCustom-category").html(item.category_name);
                $("#updatedViewCustom-description").html(item.description);
                $("#updatedViewCustom-quantity").html(`×${item.quantity}`);
                $("#updatedViewCustom-wood").html(item.wood_name);
                $("#updatedViewCustom-description").html(item.description);
                $("#updatedViewCustom-varnish").html(item.varnish == 1 ? `Varnished<br>(₱ ${numformat(item.varnish_price)})` : 'No Varnish');
                $("#updatedViewCustom-color").html(item.color_id != 0 ? `${item.color_name}` : 'No Color');
                $("#updatedViewCustom-colorPrice").html(item.color_id != 0 ? `(₱ ${numformat(item.color_price)})` : '');
                $("#updatedViewCustom-serviceOption").html(item.pickup_method);
                $("#updatedViewCustom-dateCreated").html(formatDate(item.date_created, "F d, Y"));
                $("#updatedViewCustom-timeCreated").html(item.time_created);
                $("#updatedViewCustom-customer").html(item.username);
                $("#updatedViewCustom-deadline").html(formatDate(item.completion_date, 'F d, Y'));
                $("#updatedViewCustom-specsDiv").html('');
                $("#updatedViewCustom-carpenterSketch").html('');
                $("#updatedViewCustom-referenceImage").html('');

                for(sp of item.specs){
                    $("#updatedViewCustom-specsDiv").append(`
                        <div class="col col-6 text-muted">- ${sp.specs_name}:</div>
                        <div class="col col-6">${sp.specs_value}</div>
                    `);
                }

                for(let i = 1; i <= item.image_count; i++){
                    $("#updatedViewCustom-carpenterSketch").append(`
                        <img class="w-100 mb-2" src="./img/custom/${item.id}_sketch_${i}.png?t=${time}" />
                    `);
                }

                for(let i = 1; i <= item.sketch_count; i++){
                    $("#updatedViewCustom-referenceImage").append(`
                        <img class="w-100 mb-2" src="./img/custom/${item.id}_${i}.png?t=${time}" />
                    `);
                }





                //update details modal
                $("#updateCustom-customId").val(item.id);
                $("#updateCustom-productName").html(`Custom_${item.category_name}#${item.id}`);
                
                $("#updateCustom-category").html(item.category_name);
                $("#updateCustom-quantity").html(`×${item.quantity}`);
                $("#updateCustom-woodType").html(item.wood_name);
                $("#updateCustom-varnish").html(item.varnish == 1 ? 'Varnished' : 'No varnish');
                $("#updateCustom-color").html(item.color_id != 0 ? `${item.color_name}` : 'No Color');
                $("#updateCustom-dateCreated").html(formatDate(item.date_created, 'F d, Y'));
                $("#updateCustom-addedBy").html(item.username);

                $("#updateCustom-price").val(item.product_price);
                $("#updateCustom-downPayment").val(item.down_payment);
                $("#updateCustom-completionDate").val(item.completion_date);
                

                if(item.sketch_count > 0){
                    $("#updateCustom-imageContainer").html("");
                    $("#updateCustom-imageFile").prop("required", false);
                    for(i = 0; i < item.sketch_count; i++){
                        var source = `./img/custom/${item.id}_sketch_${i+1}.png?t=${time}`;
                        $("#updateCustom-imageContainer").append(`
                            <img class="border" src="${source}" alt="Custom Image" style="width: 150px; margin: 5px;">
                        `);
                    }
                }
                
                
                if(item.specs.length > 0){
                    for(specs of item.specs){
                        
                        addCustomSpecs('updateCustom-specsDiv', specs.specs_name, specs.specs_value);
                    }
                }
                else{
                    addCustomSpecs('updateCustom-specsDiv');
                }







                //add to production modal
                $("#setProduction-customId").val(item.id);
                $("#setProduction-productName").html(item.product_name);
                $("#setProduction-category").html(item.category_name);
                $("#setProduction-varnish").html(item.varnish == 1 ? `Varnished<br>(₱ ${numformat(item.varnish_price)})` : 'No Varnish');
                $("#setProduction-color").html(item.color_id != 0 ? `${item.color_name}` : 'No Color');
                $("#setProduction-woodType").html(item.wood_name);
                $("#setProduction-productPrice").html(`₱ ${numformat(item.product_price)}`);
                $("#setProduction-quantity").html(`×${item.quantity}`);
                $("#setProduction-serviceMethod").html(`${item.pickup_method} (₱ ${numformat(item.service_fee)})`);
                $("#setProduction-additionalsDiv").html(`
                    ${(item.varnish == 1 || item.color_id != 0) ? `
                        <span class=" d-block text-medium">Additionals</span>
                        <ul>
                        
                            ${item.varnish == 1 ? `
                                <li class="text-muted">
                                    Varnished (₱ ${numformat(item.varnish_price)})
                                </li>` : ''}

                            ${item.color_id != 0 ? `
                                <li class="text-muted">
                                    WoodStain Color: ${item.color_name} (₱ ${numformat(item.color_price)})
                                </li>` : ''}

                            
                        </ul>
                    ` : '' }
                    
                `);

                var subtotal = (
                    parseFloat(item.product_price) + 
                    parseFloat(item.color_price) + 
                    parseFloat(item.varnish_price)
                );
                subtotal = (subtotal * parseFloat(item.quantity)) + parseFloat(item.service_fee);
                var minDownPayment = subtotal * (item.down_percent / 100);

                var totalBalance = subtotal - parseFloat(item.down_payment);


                $("#setProduction-subtotal").html(`₱ ${numformat(subtotal)}`);
                $("#setProduction-minimumDownPayment").html(`₱ ${numformat(minDownPayment)}`);
                $("#setProduction-downPayment").val(item.down_payment);
                $("#setProduction-downPayment").attr('min', minDownPayment);
                $("#setProduction-totalBalance").html(`₱ ${numformat(totalBalance)}`);

                $("#setProduction-customerName").html(item.username);
                $("#setProduction-address").html(item.home_address);
                $("#setProduction-contact").html(item.contact);
            }

            
        }
    });
}


function removeCustomSpecs(elem){
    if($(".specsRow").length > 1){
        $(elem).closest('.specsRow').remove();
    }
    
}


function addCustomSpecs(id, specsName, specsValue){
    if(specsName == undefined){
        specsName = '';
    }
    if(specsValue == undefined){
        specsValue = '';
    }

    $(`#${id}`).append(`
        <div class="row specsRow mb-2">
            <div class="col col-5">
                <input type="text" name="specsName[]" class="form-control" value="${specsName}" placeholder="Specs Name" required>
            </div>
            <div class="col col-5">
                <input type="text" name="specsValue[]" class="form-control" value="${specsValue}" placeholder="Specs Value" required>
            </div>
            <div class="col col-2">
                <button type="button" class="btn btn-white text-danger" onclick="removeCustomSpecs(this)">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>    
    `);
}


function setStatus(status){
    $(".statusBtn").removeClass('btn-white');
    $(".statusBtn").removeClass('btn-dblue');

    $(".statusBtn").addClass('btn-white');
    $(`#btn-${status}`).removeClass('btn-white');
    $(`#btn-${status}`).addClass('btn-dblue');
    $("#status").val(status);

    setCustomList(1);
}



function customListHTML(data, status){
    $("#custom-list tbody").html("");
    $("#custom-list-mobile").html("");
    
    
    

    if(data.length > 0){
        for(item of data){

            var dropdownHTML = '';
            if(status == 'pending'){
                dropdownHTML = `
                    <a class="dropdown-item" role="button" data-toggle="modal" data-target="#viewCustomModal" onclick="setCustomDetails(${item.id})">View Details</a>
                    <a class="dropdown-item" role="button" data-toggle="modal" data-target="#updateCustomModal" onclick="setCustomDetails(${item.id})">Update Details</a>
                    
                `;
            }
            else if(status == 'updated'){
                dropdownHTML = `
                    <a class="dropdown-item" role="button" data-toggle="modal" data-target="#updatedViewCustomModal" onclick="setCustomDetails(${item.id})">View Details</a>
                    <a class="dropdown-item" role="button" data-toggle="modal" data-target="#updateCustomModal" onclick="setCustomDetails(${item.id})">Update Details</a>
                    <a class="dropdown-item" role="button" data-toggle="modal" data-target="#setProductionModal" onclick="setCustomDetails(${item.id})">Add to Production</a>
                `;
            }
            else{
                dropdownHTML = `
                    <a class="dropdown-item" role="button" data-toggle="modal" data-target="#viewCustomModal" onclick="setCustomDetails(${item.id})">View Details</a>
                `;
            }

            
            var varnish = item.varnish == 1 ? `
                <small class="text-muted">₱ ${numformat(item.varnish_price)}</small><br>
                Varnished
            ` : 'No Varnish';
            var varnishMobile = item.varnish == 1 ? `
                <small class="text-muted">Varnished&emsp;₱ ${numformat(item.varnish_price)}</small><br>
            ` : '';
            var color = item.color_price > 0 ? `
                <small class="text-muted">₱ ${numformat(item.color_price)}</small><br>
                ${item.color_name}
            ` : 'None';
            var colorMobile = item.color_price > 0 ? `
                <small class="text-muted">Woodstain Color: ${item.color_name}&emsp;₱ ${numformat(item.color_price)}</small><br>
            ` : 'None';


            $("#custom-list tbody").append(`
                <tr>
                    <th class="text-center">${item.num}</th>
                    <td>
                        <img src="./img/custom/${item.id}_1.png?t=${time}" style="width: 80px; height: 80px">
                        &emsp;
                        ${item.product_name}
                    </td>
                    <td class="text-center">${item.username}</td>
                    <td class="text-center">${item.wood_name}</td>
                    <td class="text-center">${item.product_price != null ? `₱ ${numformat(item.product_price)}` : 'No Value'}</td>
                    <td class="text-center">${varnish}</td>
                    <td class="text-center">${color}</td>
                    <td>
                        <div class="dropleft">
                            <button type="button" class="btn btn-white dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <div class="dropdown-menu">
                                ${dropdownHTML}
                            </div>
                        </div>
                    </td>
                </tr>    
            `);

            $("#custom-list-mobile").append(`
                <div class="row pt-1 pb-3 mb-3 border-bottom">
                    <div class="col col-3">
                        <img class="border rounded" src="./img/custom/${item.id}_1.png?t=${time}" style="width: 100px; height: 100px">
                    </div>

                    <div class="col col-7">
                        <h5 class="text-medium p-0 m-0">${item.product_name}</h5>
                        <span class="d-block text-muted">${item.product_price != null ? `₱ ${numformat(item.product_price)}` : ''}</span>

                        ${varnishMobile}
                        ${colorMobile}
                    </div>

                    <div class="col col-2 text-right">
                        <div class="dropleft">
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
        $("#custom-list tbody").append(`
            <tr>
                <td colspan="7" class="text-center">No Result</th>
            </tr>
        `);

        $("#custom-list-mobile").html(`
            <div class="text-center vertical-middle">
                No Results
            </div>    
        `);
    }
    
    
}



function setCustomList(page){
    var search = $("#search").val();
    var status = $("#status").val();
    $.ajax({
        url: "fetch/getCustoms.php",
        data: {
            request: 'customs_data',
            status: status,
            orderBy: 'date_created',
            orderMethod: 'DESC',
            search: search,
            limit: 10,
            page: page,
        },
        success: function(data){
            customListHTML(data.customs, status);
            var pagination = generatePagination(data.total_pages, page, 'setCustomList');
            $("#customs-list-pagination .pagination").html(pagination);
        }
    });
}

$("#search").on("input", function(){
    setCustomList(1);
});









$('.ressetable-modal').on('hidden.bs.modal', function (e) {
    $("#updateCustom-specsDiv").html("");
    $("#updateCustom-imageContainer").html(`
        <p class="text-center m-0 p-0">Upload Sketches</p>
    `);
});

$(document).ready(function(){
    setCustomList(1);
});