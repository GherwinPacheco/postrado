function setupMaterialForm(id){
    $.get("./fetch/getMaterials.php", {
        request: 'material_details',
        material_id: id
    }, function (data){
        $(`#restoreMaterial-materialId`).val(id);
        $(`#restoreMaterial-materialNameText`).html(data.material_name);
    });
}



function materialsTableHTML(data){
    $("#materials-table tbody").html('');

    if(data.length > 0){
        for(mt of data){
            var materialId = mt.id;
            
            var dropdownHTML = `
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#restoreMaterialModal" onclick="setupMaterialForm(${materialId})">Restore</a>
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
                    <td class="text-center md-hide">
                        ₱ ${numformat(mt.cost)}
                    </td>
                    <td class="text-center">
                        ${formatDate(mt.date_archived, 'F d, Y')}
                    </td>
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

    }

    
}



//displays the table of products
function setupMaterialsTable(page){
    var limit = 10;
    var search = $("#search").val();

    //for default table
    $.get("./fetch/getMaterials.php", {
        request: 'materials_data',
        limit: limit,
        search: search,
        archived: 1,
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


$(document).ready(function(){
    setupMaterialsTable(1);
});


