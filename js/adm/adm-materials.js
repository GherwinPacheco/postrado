function setupMaterialForm(material, mode){
    $.get("./fetch/getMaterials.php", {
        request: 'material_details',
        material_id: material
    }, function (data){
        $(`#${mode}-materialId`).val(data.id);
        $(`#${mode}-materialName`).val(data.material_name);
        $(`#${mode}-materialNameText`).html(data.material_name);
        $(`#${mode}-materialNameText`).html(data.material_name);
        $(`#${mode}-cost`).val(data.cost);
        $(`#${mode}-quantity`).val(data.quantity);
        $(`#${mode}-unit`).val(data.unit);
        $(`#${mode}-minimumQty`).val(data.minimum_qty);
    });
}



function viewMaterialData(material){
    $.get("./fetch/getMaterials.php", {
        request: 'material_details',
        material_id: material
    }, function (data){
        var status = '';
        if(data.status == 1){
            status = 'In Stock';
        } else if(data.status == 2){
            status = 'Low Stock';
        } else if(data.status == 3){
            status = 'Out of Stock';
        }

        $("#viewMaterial-materialName").html(data.material_name);
        $("#viewMaterial-quantity").html(`${data.quantity} ${data.unit_name}`);
        $("#viewMaterial-minimumQty").html(`${data.minimum_qty} ${data.unit_name}`);
        $("#viewMaterial-cost").html(`₱ ${data.cost}`);
        $("#viewMaterial-status").html(status);

        $("#viewMaterial-dateCreated").html(data.date_created);
        $("#viewMaterial-timeCreated").html(data.time_created);
        $("#viewMaterial-addedBy").html(data.username);
    });
}



function materialsTableHTML(data){
    $("#materials-table tbody").html('');
    $("#materials-table-mobile").html('');

    if(data.length > 0){
        for(mt of data){
            var materialId = mt.id;
            var statusColor = '';
            var statusText = '';
    
            if(mt.status == 1){
                statusColor = 'white';
                statusText = 'In Stock';
            }
            else if(mt.status == 2){
                statusColor = 'secondary';
                statusText = 'Low Stock';
            }
            else if(mt.status == 3){
                statusColor = 'dark';
                statusText = 'Out of Stock';
            }
    
            var dropdownHTML = `
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#viewMaterialModal" onclick="viewMaterialData(${materialId})">View Material</a>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#editMaterialModal" onclick="setupMaterialForm(${materialId}, 'editMaterial')">Edit Details</a>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#setStockModal" onclick="setupMaterialForm(${materialId}, 'setStock')">Set Stocks</a>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#archiveMaterialModal" onclick="setupMaterialForm(${materialId}, 'archiveMaterial')">Archive Material</a>
            `;
    
    
            $("#materials-table tbody").append(`
                <tr>
                    <th class="text-center">
                        ${mt.num}
                    </th>
                    <td class="text-center">
                        ${mt.material_name}
                    </td>
                    <td class="text-center">
                        ${mt.quantity} ${mt.unit_name}
                    </td>
                    <td class="text-center">
                        ₱ ${numformat(mt.cost)}
                    </td>
                    <th class="text-center">
                        <span class="badge badge-pill badge-${statusColor}">${statusText}</span>
                    </th>
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
    
            $("#materials-table-mobile").append(`
                <div class="row border-bottom py-2">
                    <div class="col col-1 d-flex align-items-center justify-content-center">
                        <b>${mt.num}</b>
                    </div>
                    <div class="col col-3 d-flex align-items-center">
                        ${mt.material_name}
                    </div>
                    <div class="col col-3 d-flex align-items-center justify-content-end">
                        ${mt.quantity} ${mt.unit_name}
                    </div>
                    <div class="col col-3 d-flex align-items-center justify-content-center">
                        <span class="badge badge-pill badge-${statusColor}">${statusText}</span>
                    </div>
                    <div class="col col-1 d-flex align-items-center justify-content-end">
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
        $("#materials-table tbody").html(`
            <tr>
                <td colspan="7" class="text-center">
                    No Results
                </td>
            </tr>
        `);

        $("#materials-table-mobile").html(`
            <p class="text-center">No Results</p>
        `);
    }

    
}



//displays the table of products
function setupMaterialsTable(page){
    var limit = 10;
    var search = $("#search").val();
    var status = $("#material-status").val();

    //for default table
    $.get("./fetch/getMaterials.php", {
        request: 'materials_data',
        limit: limit,
        search: search,
        status: status,
        archived: 0,
        orderBy: 'date_created',
        orderMethod: 'desc',
        page: page
    },
        function (data){
            materialsTableHTML(data.materials);

            //setup pagination of table
            $("#materials-table-pagination .pagination").html(
                generatePagination(data.total_pages, page, 'setupMaterialsTable')
            );
        }
    );

}


$('.ressetable-modal').on('hidden.bs.modal', function (e) {
    $(".modal-form").trigger('reset');
});

$("#search").on("input", function(){
    setupMaterialsTable(1);
});

$("#material-status").change(function(){
    setupMaterialsTable(1);
});


$(document).ready(function(){
    setupMaterialsTable(1);
});


