
function setType(type){
    $(".typeBtn").removeClass('btn-white');
    $(".typeBtn").removeClass('btn-dblue');

    $(".typeBtn").addClass('btn-white');
    $(`#btn-${type}`).removeClass('btn-white');
    $(`#btn-${type}`).addClass('btn-dblue');
    $("#type").val(type);

    setProductionList(1);
}


function productionListHTML(data){
    $("#production-list tbody").html("");
    $("#production-list-mobile").html("");
    
    

    if(data.length > 0){
        for(item of data){

            var imgSrc = `./img/products/${item.product_id}.png?t=${time}`; //normal product
            if(item.type === 'custom'){
                imgSrc = `./img/custom/${item.product_id}_1.png?t=${time}`; //custom product
            }

            $("#production-list tbody").append(`
                <tr>
                    <th class="text-center">${item.num}</th>
                    <td class="text-center">ORD-${item.order_id}</th>
                    <td>
                        <img src="${imgSrc}" style="width: 80px; height: 80px">
                    </td>
                    <td>
                        ${item.product_name}
                    </td>
                    <td class="text-center">${item.customer}</td>
                    <td class="text-center">${item.category}</td>
                    <td class="text-center">${item.deadline}</td>
                    <td>
                        <a href="./cpt-productionDetails.php?id=${item.id}" class="btn btn-white text-primary">
                            <i class="fa-solid fa-circle-info"></i>
                        </a>
                    </td>
                </tr>
            `);

            $("#production-list-mobile").append(`
                <div class="row pt-1 pb-3 mb-3 border-bottom">
                    <div class="col col-3">
                        <img class="border rounded" src="${imgSrc}" style="width: 100px; height: 100px">
                    </div>

                    <div class="col col-7">
                        <h5 class="text-medium p-0 m-0">${item.product_name}</h5>
                        
                        <span class="d-block text-muted">Customer:&emsp;${item.customer}</span>
                        <span class="d-block text-muted">Deadline:&emsp;${item.deadline}</span>
                    </div>

                    <div class="col col-2">
                        <a href="./cpt-productionDetails.php?id=${item.id}" class="btn btn-white text-primary">
                            <i class="fa-solid fa-circle-info"></i>
                        </a>
                    </div>

                    
                </div>
            `);


            
        }
    }
    else{
        $("#production-list tbody").append(`
            <tr>
                <td colspan="9" class="text-center">No Result</th>
            </tr>
        `);

        $("#production-list-mobile").html(`
            <div class="text-center vertical-middle">
                No Results
            </div>    
        `);
    }
    
    
}


function setProductionList(page){
    

    var search = $("#search").val();
    var type = $("#type").val();
    $.ajax({
        url: "fetch/getProductions.php",
        data: {
            request: 'production_data',
            orderBy: 'date_approved',
            orderMethod: 'DESC',
            search: search,
            type: type,
            limit: 10,
            page: page,
        },
        success: function(data){
            productionListHTML(data.productions);
            var pagination = generatePagination(data.total_pages, page, 'setProductionList');
            $("#production-list-pagination .pagination").html(pagination);
        }
    });
}

$("#search").on("input", function(){
    setProductionList(1);
});

$(document).ready(function(){
    setProductionList(1);
});