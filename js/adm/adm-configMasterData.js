function setDeclineDetails(id){
    $.ajax({
        url: "fetch/getConfig.php",
        data: {
            request: 'decline_reasons',
            id: id
        },
        success: function(data){
            for(decline of data){
                $("#editDecline-reasonId").val(id);
                $("#editDecline-reason").val(decline.reason);
                $("#archiveDecline-reasonId").val(id);
                
            }
        }
    });
}

function setDeclineList(){
    
    $.ajax({
        url: "fetch/getConfig.php",
        data: {
            request: 'decline_reasons'
        },
        success: function(data){
            for(decline of data){
                

                $("#declineReasonList").append(`
                    <div class="row py-3 border-bottom">
                        <div class="col col-0 p-0">
                            ${decline.reason}
                        </div>
                        <div class="col col-2 p-0 text-right">
                            
                            <div class="dropleft">
                                <a type="button" class="text-dark px-2 dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </a>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item" data-toggle="modal" data-target="#editDeclineModal" onclick="setDeclineDetails(${decline.id})">Edit Details</button>
                                    <button class="dropdown-item" data-toggle="modal" data-target="#archiveDeclineModal" onclick="setDeclineDetails(${decline.id})">Archive</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
            }
        }
    });
}




function setCancelDetails(id){
    $.ajax({
        url: "fetch/getConfig.php",
        data: {
            request: 'cancel_reasons',
            id: id
        },
        success: function(data){
            for(cancel of data){
                $("#editCancel-reasonId").val(id);
                $("#editCancel-reason").val(cancel.reason);
                $("#archiveCancel-reasonId").val(id);
                
            }
        }
    });
}

function setCancelList(){
    
    $.ajax({
        url: "fetch/getConfig.php",
        data: {
            request: 'cancel_reasons'
        },
        success: function(data){
            for(cancel of data){
                

                $("#cancelReasonList").append(`
                    <div class="row py-3 border-bottom">
                        <div class="col col-0 p-0">
                            ${cancel.reason}
                        </div>
                        <div class="col col-2 p-0 text-right">
                            
                            <div class="dropleft">
                                <a type="button" class="text-dark px-2 dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </a>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item" data-toggle="modal" data-target="#editCancelModal" onclick="setCancelDetails(${cancel.id})">Edit Details</button>
                                    <button class="dropdown-item" data-toggle="modal" data-target="#archiveCancelModal" onclick="setCancelDetails(${cancel.id})">Archive</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
            }
        }
    });
}




function setWoodDetails(id){
    $.ajax({
        url: "fetch/getConfig.php",
        data: {
            request: 'wood_data',
            id: id
        },
        success: function(data){
            for(wood of data){
                $("#editWood-woodId").val(id);
                $("#editWood-woodName").val(wood.wood_name);
                $("#archiveWood-woodId").val(id);
                $("#archiveWood-woodNameText").val(wood.wood_name);
                
            }
        }
    });
}

function setWoodList(){
    
    $.ajax({
        url: "fetch/getConfig.php",
        data: {
            request: 'wood_data'
        },
        success: function(data){
            for(wood of data){
                

                $("#woodList").append(`
                    <div class="row py-3 border-bottom">
                        <div class="col col-0 p-0">
                            ${wood.wood_name}
                        </div>
                        <div class="col col-2 p-0 text-right">
                            
                            <div class="dropleft">
                                <a type="button" class="text-dark px-2 dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </a>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item" data-toggle="modal" data-target="#editWoodModal" onclick="setWoodDetails(${wood.id})">Edit Details</button>
                                    <button class="dropdown-item" data-toggle="modal" data-target="#archiveWoodModal" onclick="setWoodDetails(${wood.id})">Archive</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
            }
        }
    });
}




function setColorDetails(id){
    $.ajax({
        url: "fetch/getConfig.php",
        data: {
            request: 'colors_data',
            id: id
        },
        success: function(data){
            for(color of data){
                $("#editColor-colorId").val(id);
                $("#archiveColor-colorId").val(color.id);
                $("#editColor-colorName").val(color.color_name);
                $("#editColor-price").val(color.price);
                
            }
        }
    });
}

function setColorList(){
    
    $.ajax({
        url: "fetch/getConfig.php",
        data: {
            request: 'colors_data'
        },
        success: function(data){
            for(color of data){
                

                $("#colorList").append(`
                    <div class="row py-3 border-bottom">
                        <div class="col col-6 p-0">
                            ${color.color_name}
                        </div>
                        <div class="col col-4 p-0 text-right">
                            ₱ ${numformat(color.price)}
                        </div>
                        <div class="col col-2 p-0 text-right">
                            
                            <div class="dropleft">
                                <a type="button" class="text-dark px-2 dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </a>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item" data-toggle="modal" data-target="#editColorModal" onclick="setColorDetails(${color.id})">Edit Details</button>
                                    <button class="dropdown-item" data-toggle="modal" data-target="#archiveColorModal" onclick="setColorDetails(${color.id})">Archive</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
            }
        }
    });
}








function setUnitDetails(id){
    $.ajax({
        url: "fetch/getConfig.php",
        data: {
            request: 'units_data',
            id: id
        },
        success: function(data){
            for(unit of data){
                $("#editUnit-unitId").val(id);
                $("#archiveUnit-unitId").val(unit.id);
                $("#editUnit-unitName").val(unit.unit_name);

            }
        }
    });
}

function setUnitList(){
    
    $.ajax({
        url: "fetch/getConfig.php",
        data: {
            request: 'units_data'
        },
        success: function(data){
            for(unit of data){
                var inUseBadge = unit.material_count > 0 ? `
                    <span class="badge badge-light">In Use</span>
                ` : '';


                $("#unitList").append(`
                    <div class="row py-3 border-bottom">
                        <div class="col col-8 p-0">
                            ${unit.unit_name}
                        </div>
                        <div class="col col-2 p-0 text-center">
                            ${inUseBadge}
                        </div>
                        <div class="col col-2 p-0 text-right">
                            
                            <div class="dropleft">
                                <a type="button" class="text-dark px-2 dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </a>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item" data-toggle="modal" data-target="#editUnitModal" onclick="setUnitDetails(${unit.id})">Edit Details</button>
                                    <button class="dropdown-item" data-toggle="modal" data-target="#archiveUnitModal" onclick="setUnitDetails(${unit.id})" ${unit.material_count > 0 ? 'disabled' : ''}>Archive</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
            }
        }
    });
}












function setCategoryDetails(id){
    $.ajax({
        url: "fetch/getConfig.php",
        data: {
            request: 'categories_data',
            id: id
        },
        success: function(data){
            for(ctg of data){
                $("#editCategory-categoryId").val(id);
                $("#archiveCategory-categoryId").val(ctg.id);
                $("#editCategory-categoryName").val(ctg.category_name);

                for(specs of ctg.specs){
                    addCategorySpecs('editCategory-specsDiv', specs.specs_name);
                }
            }
        }
    });
}


function removeSpecs(elem){
    if($(".specsRow").length > 1){
        $(elem).closest(".specsRow").remove();
    }
}



function addCategorySpecs(container, value){

    var specsValue = value !== undefined ? value : '';

    $(`#${container}`).append(`
        <div class="row specsRow mb-2">
            <div class="col col-9">
                <input type="text" class="form-control" name="specs[]" list="specslist" value="${specsValue}" placeholder="Specs Name" required>
            </div>
            <div class="col col-3">
                <button type="button" class="btn btn-white text-danger" onclick="removeSpecs(this)">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>

          
    `);
}

function setCategoryList(){
    
    $.ajax({
        url: "fetch/getConfig.php",
        data: {
            request: 'categories_data'
        },
        success: function(data){
            for(ctg of data){
                var inUseBadge = ctg.product_count > 0 ? `
                    <span class="badge badge-light">In Use</span>
                ` : '';
                

                $("#categoriesList").append(`
                    <div class="row py-3 border-bottom">
                        <div class="col col-8 p-0">
                            ${ctg.category_name}
                        </div>
                        <div class="col col-2 p-0 text-center">
                            ${inUseBadge}
                        </div>
                        <div class="col col-2 p-0 text-right">
                            
                            <div class="dropleft">
                                <a type="button" class="text-dark px-2 dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </a>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item" data-toggle="modal" data-target="#editCategoryModal" onclick="setCategoryDetails(${ctg.id})">Edit Details</button>
                                    <button class="dropdown-item" data-toggle="modal" data-target="#archiveCategoryModal" onclick="setCategoryDetails(${ctg.id})" ${ctg.product_count > 0 ? 'disabled' : ''}>Archive</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
            }
        }
    });
}


$('.ressetable-modal').on('hidden.bs.modal', function (e) {
    $(".specsDiv").html("");
});


$(document).ready(function(){
    setCategoryList();
    setUnitList();
    setColorList();
    setWoodList();
    setCancelList();
    setDeclineList();

    var specsPromise = new Promise(function(resolve, reject) {
        $.ajax({
            url: "fetch/getConfig.php",
            data: {
                request: 'specs_data'
            },
            method: 'GET',
            success: function(data) {
                resolve(data);
            },
            error: function(error) {
                reject(error);
            }
        });
    });

    // Handling the promise resolution
    specsPromise.then(function(data) {
        // Iterating over the resolved data if it's an array
        if (Array.isArray(data)) {
            data.forEach(function(item) {
                $("#specslist").append(`
                    <option value="${item.specs_name}">${item.specs_name}</option>    
                `);
            });
        } else {
            console.error("Expected an array but got:", data);
        }
    }).catch(function(error) {
        console.error("Error:", error);
    });

});

