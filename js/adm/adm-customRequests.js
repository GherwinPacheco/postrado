$("#declineCustom-selectedReason").change(function(){
    if($("#declineCustom-selectedReason").val() === 'others'){
        $("#declineCustom-cancelDetails").prop("required", true);
    }
    else{
        $("#declineCustom-cancelDetails").prop("required", false);
    }
});

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
                $("#viewCustom-description").html(item.description);
                $("#viewCustom-varnish").html(item.varnish == 1 ? `Varnished<br>(₱ ${numformat(item.varnish_price)})` : 'No Varnish');
                $("#viewCustom-color").html(item.color_id != 0 ? `${item.color_name}` : 'No Color');
                $("#viewCustom-colorPrice").html(item.color_id != 0 ? `(₱ ${numformat(item.color_price)})` : '');
                $("#viewCustom-dateCreated").html(formatDate(item.date_created, "F d, Y"));
                $("#viewCustom-timeCreated").html(item.time_created);
                $("#viewCustom-addedBy").html(item.username);
                $("#viewCustom-cancelDetails").html(item.cancel_details);
                
                if($("#status").val() === 'declined'){
                    $("#viewCustom-cancelDetailsDiv").css("display", "block");
                }
                else{
                    $("#viewCustom-cancelDetailsDiv").css("display", "none");
                }

                $("#approveCustom-customId").val(id);
                $("#approveCustom-productNameText").html(item.product_name);

                $("#declineCustom-customId").val(id);

                if($("#declineCustom-selectedReason").val() === 'others'){
                    $("#declineCustom-cancelDetails").prop("required", true);
                }
                else{
                    $("#declineCustom-cancelDetails").prop("required", false);
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

            var dropdownHTML = '';
            if(status == 'admin_pending'){
                dropdownHTML = `
                    <a class="dropdown-item" role="button" data-toggle="modal" data-target="#viewCustomModal" onclick="setCustomDetais(${item.id})">View Details</a>
                    <a class="dropdown-item" role="button" data-toggle="modal" data-target="#approveCustomModal" onclick="setCustomDetais(${item.id})">Approve</a>
                    <a class="dropdown-item" role="button" data-toggle="modal" data-target="#declineCustomModal" onclick="setCustomDetais(${item.id})">Decline</a>
                `;
            }
            else{
                dropdownHTML = `
                    <a class="dropdown-item" role="button" data-toggle="modal" data-target="#viewCustomModal" onclick="setCustomDetais(${item.id})">View Details</a>
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