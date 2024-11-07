function setupProductForm(id){
    $.get("./fetch/getProducts.php", {
        request: 'product_details',
        product_id: id
    }, function (data){
        $("#restoreProduct-productId").val(id);
        $("#restoreProduct-productNameText").html(data.product_name);
    });
}





function productsTableHTML(data){
    $("#furnitures-table tbody").html('');

    if(data.length > 0){
        for(prod of data){
            var productId = prod.id;
            var priceText = '';
            var priceTextMobile = '';
            for(p of prod.prices){
                priceText = priceText.concat(`₱ ${numformat(p.price)}<br>`);
                priceTextMobile = priceTextMobile.concat(`₱ ${numformat(p.price)}&emsp;`);
            }
    
            var dropdownHTML = `
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#restoreProductModal" onclick="setupProductForm(${productId})">Restore</a>
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
                    <td class="text-center md-hide">
                        ${priceText}
                    </td>
                    <td class="text-center">
                        ${formatDate(prod.date_archived, 'F d, Y')}
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
        $("#furnitures-table tbody").html(`
            <tr>
                <td colspan="7" class="text-center">
                    No Results
                </td>
            </tr>    
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
        archived: 1,
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

$("#search").on("input", function(){
    setupProductsTable(1);
});


$(document).ready(function(){
    setupProductsTable(1);
    pCount = 0;
});


