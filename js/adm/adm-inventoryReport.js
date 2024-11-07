function showMore(page){
    $(".showMoreBtn").remove();
    setMaterialUsageList(page+1);
}

function setMaterialUsageList(page){
    
    $.ajax({
        url: "fetch/getReports.php",
        data: {
            request: 'materials_usage',
            limit: 20,
            page: page,
        },
        success: function(data){
            for(mt of data.materials){
                var modeColor = '';
                var modeSymbol = '';
                if(mt.mode === 'add'){
                    modeColor = 'primary';
                    modeSymbol = '+';
                }
                else if(mt.mode === 'deduct'){
                    modeColor = 'danger';
                    modeSymbol = '-';
                }

                var quantityText = `<small class="text-${modeColor}">${modeSymbol + mt.quantity} ${mt.unit_name}</small>`;

                $("#materialsUsage-list").append(`
                    <div class="row w-100 py-1 border-top">
                        <div class="col col-1 d-flex align-items-center">
                            <small class="text-muted">${mt.num}</small>
                        </div>
                        <div class="col col-5 d-flex align-items-center">${mt.material_name}</div>
                        <div class="col col-5 text-right">
                            ${quantityText}
                            <br>
                            <small class="text-muted">${mt.date}</small>
                        </div>
                    </div>
                `);

                
                
                

            }

            if(!data.complete){
                $("#materialsUsage-list").append(`
                    <a role="button" class="showMoreBtn d-block text-center mt-3" onclick="showMore(${page})">Show More</button>
                `);
            }

            
        }
    });
}



function setInStockTable(page){
    
    $.ajax({
        url: "fetch/getReports.php",
        data: {
            request: 'material_list',
            status: 1,
            limit: 5,
            page: page,
        },
        success: function(data){
            $("#inStock-container").html("");
            let tbody = "";
            if(data.materials.length > 0){
                for(mt of data.materials){

                

                    tbody = tbody.concat(`
                        <tr>
                            <th class="text-center">${mt.num}</th>
                            <td>${mt.material_name}</td>
                            <td class="text-center">${mt.quantity}</td>
                            <td class="text-center md-hide">₱ ${numformat(mt.cost)}</td>
                            <td class="text-center"><span class="badge badge-white">${mt.status}</span></td>
                        </tr>    
                    `);
                }
            } 
            else {
                tbody = `
                    <tr>
                        <td colspan="5" class="text-center">No Materials to Show</td>
                    </tr>    
                `;
            }

            $("#inStock-container").html(`
                <h5 class="text-medium mb-3 d-block">In Stock Materials</h5>
                <table class="table" id="inStock-table">
                    <thead>
                        <th>#</th>
                        <th>MATERIAL</th>
                        <th>QUANTITY</th>
                        <th class="md-hide">COST</th>
                        <th>STATUS</th>
                    </thead>
                    <tbody>
                        ${tbody}
                    </tbody>
                </table>
            `);

            
            var pagination = generatePagination(data.total_pages, page, 'setInStockTable');
            $("#inStock-pagination .pagination").html(pagination);
        }
    });
}

function setLowStockTable(page){
    
    $.ajax({
        url: "fetch/getReports.php",
        data: {
            request: 'material_list',
            status: 2,
            limit: 5,
            page: page,
        },
        success: function(data){
            $("#lowStock-container").html("");
            let tbody = "";
            if(data.materials.length > 0){
                for(mt of data.materials){

                

                    tbody = tbody.concat(`
                        <tr>
                            <th class="text-center">${mt.num}</th>
                            <td>${mt.material_name}</td>
                            <td class="text-center">${mt.quantity}</td>
                            <td class="text-center md-hide">₱ ${numformat(mt.cost)}</td>
                            <td class="text-center"><span class="badge badge-secondary">${mt.status}</span></td>
                        </tr>    
                    `);
                }
            } 
            else {
                tbody = `
                    <tr>
                        <td colspan="5" class="text-center">No Materials to Show</td>
                    </tr>    
                `;
            }
            

            $("#lowStock-container").html(`
                <h5 class="text-medium mb-3 d-block">Low Stock Materials</h5>
                <table class="table" id="lowStock-table">
                    <thead>
                        <th>#</th>
                        <th>MATERIAL</th>
                        <th>QUANTITY</th>
                        <th class="md-hide">COST</th>
                        <th>STATUS</th>
                    </thead>
                    <tbody>
                        ${tbody}
                    </tbody>
                </table>
            `);

            
            var pagination = generatePagination(data.total_pages, page, 'setLowStockTable');
            $("#lowStock-pagination .pagination").html(pagination);
        }
    });
}

function setOutOfStockTable(page){
    
    $.ajax({
        url: "fetch/getReports.php",
        data: {
            request: 'material_list',
            status: 3,
            limit: 5,
            page: page,
        },
        success: function(data){
            $("#outOfStock-container").html("");
            let tbody = "";
            if(data.materials.length > 0){
                for(mt of data.materials){

                

                    tbody = tbody.concat(`
                        <tr>
                            <th class="text-center">${mt.num}</th>
                            <td>${mt.material_name}</td>
                            <td class="text-center">${mt.quantity}</td>
                            <td class="text-center md-hide">₱ ${numformat(mt.cost)}</td>
                            <td class="text-center"><span class="badge badge-dark">${mt.status}</span></td>
                        </tr>    
                    `);
                }
            } 
            else {
                tbody = `
                    <tr>
                        <td colspan="5" class="text-center">No Materials to Show</td>
                    </tr>    
                `;
            }
            

            $("#outOfStock-container").html(`
                <h5 class="text-medium mb-3 d-block">Out of Stock Materials</h5>
                <table class="table" id="outOfStock-table">
                    <thead>
                        <th>#</th>
                        <th>MATERIAL</th>
                        <th>QUANTITY</th>
                        <th class="md-hide">COST</th>
                        <th>STATUS</th>
                    </thead>
                    <tbody>
                        ${tbody}
                    </tbody>
                </table>
            `);

            
            var pagination = generatePagination(data.total_pages, page, 'setOutOfStockTable');
            $("#outOfStock-pagination .pagination").html(pagination);
        }
    });
}


function setInventoryOverview(){
    $.ajax({
        url: "fetch/getReports.php",
        type: 'GET',
        data: {
            request: 'inventory_overview'
        },
        dataType: "json",
        success: function(data){
            
            $(".overview-inventoryTotal").html(`₱ ${numformat(data.inventory_total)}`);
            $(".overview-inStocks").html(`${(data.in_stock)} Materials`);
            $(".overview-lowStocks").html(`${data.low_stock} Materials`);
            $(".overview-outOfStocks").html(`${(data.out_of_stock)} Materials`);
            
            
        }
    });
}


$(document).ready(function(){
    setInventoryOverview();
    setInStockTable(1);
    setLowStockTable(1);
    setOutOfStockTable(1);
    setMaterialUsageList(1);
});