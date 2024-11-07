function setCustomDetais(id) {
    $.ajax({
        url: "fetch/getCustoms.php",
        data: {
            request: 'customs_data',
            custom_id: id
        },
        dataType: 'json', // Ensure the data type is set to JSON
        success: function(data) {
            

            for (item of data) {
                var img = '';
                var isFirst = true;
                for (let i = 1; i <= item.image_count; i++) {
                    img += `
                        <div class="carousel-item ${isFirst ? 'active' : ''}">
                            <img src="./img/custom/${item.id}_${i}.png?t=${time}" class="d-block w-100" alt="">
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
                $("#viewCustom-description").html(item.description);
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

            }

            
        }
    });
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

            var dropdownHTML = `
                <a class="dropdown-item" role="button" data-toggle="modal" data-target="#viewCustomModal" onclick="setCustomDetais(${item.id})">View Details</a>
            `;

            if(item.product_price > 0){
                dropdownHTML = `
                    <a class="dropdown-item" role="button" data-toggle="modal" data-target="#updatedViewCustomModal" onclick="setCustomDetais(${item.id})">View Details</a>
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
                    <td class="text-center price-col">${item.product_price != null ? `₱ ${numformat(item.product_price)}` : 'No Value'}</td>
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



            if(status === 'admin_pending'){
                $(".price-col").css("display", "none");
            }
            else{
                $(".price-col").css("display", "table-cell");
            }


            
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
    var user = $("#userId").val() != undefined ? $("#userId").val() : 'none';
    $.ajax({
        url: "fetch/getCustoms.php",
        data: {
            request: 'customs_data',
            status: status,
            orderBy: 'date_created',
            orderMethod: 'DESC',
            search: search,
            user: user,
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
    setStatus(statusGET);
    setCustomList(1);
    
});