const defaultLabels = [
    'Jan', 
    'Feb',
    'Mar', 
    'Apr',
    'May', 
    'Jun',
    'Jul', 
    'Aug',
    'Sep', 
    'Oct',
    'Nov', 
    'Dec'
];

const labelFormat = function(context) {
    // context.dataset.label is the label of the dataset
    // context.raw is the actual value for the data point
    return '₱ ' + numformat(context.raw.toLocaleString());
}



function setProductSalesTable(page){
    
    $.ajax({
        url: "fetch/getReports.php",
        data: {
            request: 'sold_products',
            limit: 10,
            page: page,
        },
        success: function(data){
            $("#productSales-table tbody").html("");
            $("#productSales-table-mobile").html("");
            for(prd of data.products){
                $("#productSales-table tbody").append(`
                    <tr>
                        <th class="text-center">${prd.num}</th>
                        <td>${prd.product_name}</td>
                        <td class="text-center">${prd.quantity}</td>
                        <td class="text-center">₱ ${numformat(prd.total)}</td>
                        <td class="text-center">${prd.date}</td>
                    </tr>    
                `);

                $("#productSales-table-mobile").append(`
                    <div class="row py-2 border-top">
                        <div class="col col-8 d-flex flex-column justify-content-center">
                            <span>${prd.product_name}</span>
                            <small class="text-muted">Quantity:&nbsp;×${prd.quantity}</small>
                        </div>
                        <div class="col col-4 d-flex align-items-center justify-content-center">
                            <span class="text-success text-medium">₱ ${numformat(prd.total)}</span>
                        </div>
                        <div class="col col-12 text-right p-0">
                            <small class="text-muted">${prd.date}</small>
                        </div>
                    </div>
                `);
            }

            


            var pagination = generatePagination(data.total_pages, page, 'setProductSalesTable');
            $("#productSales-pagination .pagination").html(pagination);
        }
    });
}






function generateTopProductsTable(){
    
    $.ajax({
        url: "fetch/getReports.php",
        type: 'GET',
        data: {
            request: 'top_products',
            limit: 10
        },
        dataType: "json",
        success: function(data){
            $("#topProducts-list").html("");
            var num = 1;
            for(prd of data){
                $("#topProducts-list").append(`
                    <div class="row py-2 border-top">
                        <div class="col col-1 text-medium d-flex justify-content-start align-items-center">
                            <span>${num}</span>
                        </div>
                        <div class="col col-6 d-flex justify-content-start align-items-center">
                            <span>${prd.product}</span>
                        </div>
                        <div class="col col-4 text-right d-flex justify-content-end align-items-center">
                            <span>₱ ${numformat(prd.total_sales)}</span>
                        </div>
                    </div> 
                `);


                num++;
            }
            
        }
    });
}






function updateChart(chart, newLabels, newData) {
    // Access the chart instance
    chart.data.labels = newLabels;
    chart.data.datasets[0].data = newData;

    // Update the chart
    chart.update();
}


function generateTopCategoryCharts(){
    $.ajax({
        url: "fetch/getReports.php",
        type: 'GET',
        data: {
            request: 'top_categories',
            limit: 10
        },
        dataType: "json",
        success: function(data){

            //update the chart
            var labels = data.map(row => row.category);
            var totalSales = data.map(row => row.total_sales);
            updateChart(categoryChart, labels, totalSales);
            
        }
    });
}

var categoryChart = new Chart(
    document.getElementById('categoryChart'),
    {
        type: 'doughnut',
        data: {
        labels: defaultLabels,
        datasets: [
            {
            data: [],
            backgroundColor: [
                "#87CEEB",  // Sky Blue
                "#FF7F50",  // Coral
                "#DAA520",  // Goldenrod
                "#32CD32",  // Lime Green
                "#663399",  // Royal Purple
                "#FF6347",  // Tomato Red
                "#40E0D0",  // Turquoise
                "#FA8072",  // Salmon Pink
                "#708090",  // Slate Gray
                "#DC143C"   // Crimson
              ],
            }
        ]
        },
        
        options: {
        plugins:{
            tooltip: {
                callbacks: {
                    label: labelFormat
                }
            },
            legend:{
                position: 'left',
                align: 'start',
                labels: {
                    boxWidth: 20,
                    font:{
                        size: 13
                    }
                }
            }
        }
        }
    }
);




$("#salesGraph-type").change(function(){
    var type = $(this).val();
    if(type === 'monthly'){
        $("#salesGraph-month").prop('readonly', true);
        $("#salesGraph-year").prop('readonly', false);
    }
    else if(type === 'yearly'){
        $("#salesGraph-month").prop('readonly', true);
        $("#salesGraph-year").prop('readonly', true);
    }
    else{
        $("#salesGraph-month").prop('readonly', false);
        $("#salesGraph-year").prop('readonly', false);
    }
});

function generateSalesChart(){
    var type = $("#salesGraph-type").val();
    var month = $("#salesGraph-month").val();
    var year = $("#salesGraph-year").val();
    $.ajax({
        url: "fetch/getReports.php",
        type: 'GET',
        data: {
            request: 'sales_data',
            sales_type: type,
            month: month,
            year: year
        },
        dataType: "json",
        success: function(data){

            //update the chart
            var labels = data.map(row => row.date);
            var totalSales = data.map(row => row.total_sales);
            var productsSold = data.map(row => row.products_sold);
            var totalOrders = data.map(row => row.total_orders);
            updateChart(salesChart, labels, totalSales);


            //update the header
            var typeText = $("#salesGraph-type option:selected").html();
            var monthText = $("#salesGraph-month option:selected").html();
            var yearText = $("#salesGraph-year option:selected").html();
            var headerText = `${typeText} Sales (${monthText} ${yearText})`;
            if(type === 'monthly'){
                headerText = `${typeText} Sales (${yearText})`;
            }
            else if(type === 'yearly'){
                headerText = `${typeText} Sales`;
            }
            $("#salesGraph-header").html(headerText);
            
            
        }
    });
}


var salesChart = new Chart(
    document.getElementById('salesChart'),
    {
        type: 'bar',
        data: {
        labels: defaultLabels,
        
        datasets: [
            {
            data: [],
            backgroundColor: '#198754',
            }
        ]
        },
        options: {
        plugins: {
            tooltip: {
                callbacks: {
                    label: labelFormat
                }
            },
            legend: {
                display: false
            }
        }
        }
    }
);


function setSalesOverview(){
    $.ajax({
        url: "fetch/getReports.php",
        type: 'GET',
        data: {
            request: 'sales_overview'
        },
        dataType: "json",
        success: function(data){
            
            $(".overview-totalSales").html(`₱ ${numformat(data.total_sales)}`);
            $(".overview-productsSold").html(`${data.products_sold} Products`);

            $(".overview-activeOrders").html(`${(data.active_orders)} Orders`);
            $(".overview-cancelledOrders").html(`${data.cancelled_orders} Orders`);
            $(".overview-completeOrders").html(`${(data.completed_orders)} Orders`);
            
            
        }
    });
}


$(document).ready(function(){
    setSalesOverview();
    generateSalesChart();
    generateTopCategoryCharts();
    generateTopProductsTable();
    setProductSalesTable(1)
});