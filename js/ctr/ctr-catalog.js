$("#category").change(function(){
    $("#product-catalog").html('');
    setupProductsList(1);
});

$("#search").on('input', function(){
    $("#product-catalog").html('');
    setupProductsList(1);
});

function setupProductsList(page){
    var category = $("#category").val();
    var search = $("#search").val();
    var limit = 20;
    $.get("./fetch/getProducts.php", {
        request: "products-catalog",
        limit: limit,
        category: category,
        page: page,
        search: search
    },
    function (data){
        $("#product-catalog").append(data.catalog);
        $("#btn-div").html(data.nextBtn);
    });
}




$(document).ready(function(){
    setupProductsList(1);
});