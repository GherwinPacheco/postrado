function showBestSelling(){
    $.ajax({
        url: "fetch/getProducts.php",
        data: {
            request: 'top_products',
        },
        success: function(data){
            for(prd of data){
                var saleTag = '';
                var priceTag = `
                    <span class="card-text text-secondary">₱ ${numformat(prd.price)}</span><br><br>
                `;
                if(prd.sale > 0){
                    saleTag = `
                        <span class="sale-tag font-italic bg-danger text-white py-1 pl-2 pr-2">${prd.sale}% OFF</span>
                    `;
                    priceTag = `
                        <span class="card-text text-secondary">₱ ${numformat(prd.price)}</span><br>
                        <del class="card-text text-secondary">₱ ${numformat(prd.original_price)}</del>
                    `;
                }

                
                $("#bestSellingDiv").append(`
                    <div class="product-catalog col col-6 col-xl-3 p-0">
                
                        <a class="text-dark" href="./ctr-productView.php?p=${prd.id}">
                        <div class="card shadow m-2">
                            ${saleTag}
                            <img class="card-img-top p-3 rounded" src="img/products/${prd.id}.png?t=${time}" style="aspect-ratio: 1 / 1; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title text-medium">${prd.product_name}</h5>
                                <span class="card-text text-secondary">${prd.category}</span><br>
                                ${priceTag}
                            </div>
                            
                        </div>
                        </a>
                    </div>
                `);
            }
        }
    }); 
}


$(document).ready(function(){
    showBestSelling();
});
