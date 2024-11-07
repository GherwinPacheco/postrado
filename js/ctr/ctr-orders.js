$("#cancelOrder-selectedReason").change(function(){
    if($("#cancelOrder-selectedReason").val() === 'others'){
        $("#cancelOrder-cancelDetails").prop("required", true);
    }
    else{
        $("#cancelOrder-cancelDetails").prop("required", false);
    }
});

function cancelOrder(id){
    $("#cancelOrder-orderId").val(id);
    $("#cancelOrder-orderIdText").html(`ORD-${id}`);

    if($("#cancelOrder-selectedReason").val() === 'others'){
        $("#cancelOrder-cancelDetails").prop("required", true);
    }
    else{
        $("#cancelOrder-cancelDetails").prop("required", false);
    }
}

function setScreenshotModal(id){
    $("#uploadScreenshot-orderId").val(id);
    $("#uploadScreenshot-screenshotImg").attr("src", `./img/payments/${id}.png?t=${time}`);
    $("#viewScreenshot-screenshotImg").attr("src", `./img/payments/${id}.png?t=${time}`);
}

function setOrderDetails(id){
    $.ajax({
        url: "fetch/getOrders.php",
        data: {
            request: 'orders_data',
            order_id: id
        },
        success: function(data){
            for(order of data){
                var deadlineText = '';

                deadlineText = 'Deadline';
                $("#payment-header-modal").html('Settled Down Payment');
                $("#total-header-mobile").html('Total Payment');

                if(order.order_status === 'Complete'){
                    deadlineText = 'Date Completed';
                    $("#payment-header-modal").html('Received Payment');
                }
                else if(order.order_status === 'Ready'){
                    $("#total-header-mobile").html('Balance to Pay');
                    deadlineText = 'Arrival';
                }
                else if(order.order_status === 'Preparing'){
                    deadlineText = 'Expected Arrival';
                }

                var paymentStatusHTML = '';
                if(order.order_status !== 'Complete'){
                    if(order.paid_amount > 0 && order.paid_amount < order.down_payment){
                        paymentStatusHTML = `
                            <span class="badge badge-secondary">Unpaid</span>
                        `;
                    }
                    else if(order.paid_amount >= order.total){
                        
                        paymentStatusHTML = `
                            <span class="badge badge-success">Paid</span>
                        `;
                    }
                    else if(order.paid_amount >= order.down_payment && order.paid_amount < order.total){
                        paymentStatusHTML = `
                            <span class="badge badge-primary">Partially Paid</span>
                        `;
                    }
                }

                var cancelText = '';
                if(order.order_status === 'Declined'){
                    cancelText = 'Decline Reasons';
                }
                else if(order.order_status === 'Cancelled'){
                    cancelText = 'Reason for Cancelling';
                }

                $("#viewDetails-orderId").html(`ORD-${id}`);
                $("#viewDetails-status").html(`${order.order_status}`);
                $("#viewDetails-deadlineText").html(`${deadlineText}`);
                $("#viewDetails-deadline").html(`${order.order_status === 'Complete' ? formatDate(order.date_completed, 'F d, Y') : formatDate(order.completion_date, 'F d, Y')}`);
                $("#viewDetails-username").html(`${order.username}`);
                $("#viewDetails-homeAddress").html(`${order.home_address}`);
                $("#viewDetails-contact").html(`${order.contact}`);
                $("#viewDetails-paymentMethod").html(`${order.payment_method}`);
                $("#viewDetails-pickupMethod").html(`${order.pickup_method}`);
                $("#viewDetails-pickupMethod").append(order.service_fee > 0 ? `&emsp;<small class="text-muted">(₱ ${numformat(order.service_fee)})</small>` : ``);

                var orderItemsHTML = '';
                for(item of order.order_items){
                    var varnish = item.varnish_price > 0 ? `<li>Varnish:&emsp;₱ ${numformat(item.varnish_price)}</li>` : '';
                    var color = item.color_price > 0 ? `<li>Woodstain Color:&emsp;${item.color_name} ₱ ${numformat(item.color_price)}</li>` : '';
                    var variant = item.variant_name ? 
                        `<li>Variant:&emsp;${item.variant_name} ₱ ${numformat(item.variant_price)}</li>` :
                        `<li>Product Price:&emsp;₱ ${numformat(item.variant_price)}</li>`;
                    

                    var orderItemsHTML = orderItemsHTML.concat(`
                        <li class="mb-2">
                            <span class="text-medium">&emsp;${item.product_name}<span>
                            <ul class="text-muted">
                                <li>Qty: ×${item.quantity}</li>
                                ${variant}
                                ${varnish}
                                ${color}
                                <li>Subtotal: ₱ ${numformat(item.item_total)}</li>
                            </ul>
                        </li>    
                    `);
                    
                }

                var totalValue = order.order_status === 'Ready' ? order.total - order.paid_amount : order.total;
                $("#viewDetails-itemList").html(orderItemsHTML);
                $("#viewDetails-paidAmount").html(`₱ ${numformat(order.paid_amount)}&emsp;${paymentStatusHTML}`);
                $("#viewDetails-total").html(`₱ ${numformat(totalValue)}`);
                $("#viewDetails-cancelText").html(cancelText);
                $("#viewDetails-reason").html(order.cancel_details);
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

    setOrderList(1);
}


function orderListHTML(data, status){
    $("#orders-list tbody").html("");
    $("#orders-list-mobile").html("");

    var deadlineText = '';

    deadlineText = 'Deadline';
    $("#deadline-header").html('DEADLINE');
    $("#total-header").html('TOTAL');
    $("#payment-header").html('DOWN PAYMENT');

    if(status === 'complete'){
        deadlineText = 'Date Completed';
        $("#deadline-header").html('DATE COMPLETED');
        $("#payment-header").html('RECEIVED PAYMENT');
    }
    else if(status === 'ready'){
        deadlineText = 'Arrival';
        $("#total-header").html('BALANCE');
    }
    else if(status === 'preparing'){
        deadlineText = 'Expected Arrival';
    }


    

    if(data.length > 0){
        for(order of data){

            

            var dropdownHTML = '';
            var paymentGcashBtn = order.payment_method === 'Gcash' ? `
                <a class="dropdown-item" role="button" data-toggle="modal" data-target="#uploadScreenshotModal" onclick="setScreenshotModal(${order.id})">Upload Payment Screenshot</a>
                <a class="dropdown-item" role="button" data-toggle="modal" data-target="#viewScreenshotModal" onclick="setScreenshotModal(${order.id})">View Payment Screenshot</a>
            ` : '';
            if(status === 'pending'){
                dropdownHTML = `
                    <a class="dropdown-item" role="button" data-toggle="modal" data-target="#viewDetailsModal" onclick="setOrderDetails(${order.id})">View Details</a>
                    ${paymentGcashBtn}
                    ${order.paid_amount <= 0 ? `<a class="dropdown-item" role="button" data-toggle="modal" data-target="#cancelOrderModal" onclick="cancelOrder(${order.id})">Cancel Order</a>` : ''}
                `;
            }
            else if(status === 'complete' || status === 'declined' || status === 'cancelled'){
                dropdownHTML = `
                    <a class="dropdown-item" role="button" data-toggle="modal" data-target="#viewDetailsModal" onclick="setOrderDetails(${order.id})">View Details</a>
                `;
            }
            else {
                dropdownHTML = `
                    <a class="dropdown-item" role="button" data-toggle="modal" data-target="#viewDetailsModal" onclick="setOrderDetails(${order.id})">View Details</a>
                    ${paymentGcashBtn}
                `;
            }


            var paymentStatusHTML = '';
            if(status !== 'complete'){
                if(order.paid_amount > 0 && order.paid_amount < order.down_payment){
                    paymentStatusHTML = `
                        <span class="badge badge-secondary">Unpaid</span><br>
                    `;
                }
                else if(order.paid_amount >= order.total){
                    
                    paymentStatusHTML = `
                        <span class="badge badge-success">Paid</span><br>
                    `;
                }
                else if(order.paid_amount >= order.down_payment && order.paid_amount < order.total){
                    paymentStatusHTML = `
                        <span class="badge badge-primary">Partially Paid</span><br>
                    `;
                }
            }


            var itemsHTML = '';
            var itemCount = 1;
            for(item of order.order_items){
                itemsHTML = itemsHTML.concat(`
                    <small class="text-muted d-block">
                        - ${item.product_name} (${item.variant_name})
                    </small>
                    
                `);

                if(itemCount > 3){
                    itemsHTML = itemsHTML.concat(`
                        <small class="text-muted d-block">
                            (${order.order_items.length - itemCount} more item)
                        </small>
                    `);
                    break;
                }
                itemCount++;

            }
            
            
    
            var totalValue = order.order_status === 'Ready' ? order.total - order.paid_amount : order.total;
    
            $("#orders-list tbody").append(`
                <tr id="order-${order.id}">
                    <th class="text-medium text-center">${order.num}</th>
                    <td class="text-medium text-center">ORD-${order.id}</td>
                    <td class="text-center text-medium ${order.payment_method === 'Cash' ? 'text-success' : 'text-primary'}">${order.payment_method}</td>
                    <td class="text-center">${order.pickup_method}</td>
                    <td class="text-center">
                        ${paymentStatusHTML}₱ ${numformat(order.paid_amount)}
                    </td>
                    <td class="text-center">
                        ₱ ${numformat(totalValue)}
                    </td>
                    <td class="text-center">${formatDate((status === 'complete' ? order.date_completed : order.completion_date), 'F d, Y')}</td>
                    <td class="text-center">
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

            $("#orders-list-mobile").append(`
                <div class="row pt-1 pb-3 mb-3 border-bottom">
                    <div class="col col-7">
                        <span>#ORD-${order.id}</span>
                        &nbsp;
                        <span class="text-medium text-info">${order.order_status}</span>
                        ${itemsHTML}
                    </div>
                    <div class="col col-4 d-flex align-items-center justify-content-center flex-column w-100">
                        <small class="text-medium ${order.payment_method === 'Cash' ? 'text-success' : 'text-primary'}">${order.payment_method} Payment</small>
                        <small class="text-muted text-dark">${order.pickup_method}</small>
                    </div>
                    <div class="col col-1">
                        <div class="dropleft text-right">
                            <button type="button" class="btn btn-white dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <div class="dropdown-menu">
                                ${dropdownHTML}
                            </div>
                        </div>
                    </div>
                    

                    <div class="col col-6 mt-3">
                        <small class="text-muted">
                            ${deadlineText}:&nbsp;
                            <span class="text-medium">${formatDate((status === 'complete' ? order.date_completed : order.completion_date), 'F d')}</span>
                        </small>
                    </div>

                    <div class="col col-6 mt-3 text-right text-medium">
                        ${status === 'ready' ? 'Balance' : 'Total'}
                        &emsp;
                        <span class="text-primary">
                            ₱ ${numformat(totalValue)}
                            &emsp;
                            ${paymentStatusHTML}
                        </span>
                    </div>
                    
                    
                </div>  
            `);
    
        }
    }
    else{
        $("#orders-list tbody").append(`
            <tr>
                <td colspan="8" class="text-center">No Result</th>
            </tr>
        `);

        $("#orders-list-mobile").html(`
            <div class="text-center vertical-middle">
                No Results
            </div>    
        `);
    }
    
}



function setOrderList(page){
    var search = $("#search").val();
    var status = $("#status").val();
    var user = $("#userId").val() != undefined ? $("#userId").val() : 'none';
    $.ajax({
        url: "fetch/getOrders.php",
        data: {
            request: 'orders_data',
            status: status,
            orderBy: 'date_created',
            orderMethod: 'DESC',
            limit: 10,
            page: page,
            user: user,
            search: search
        },
        success: function(data){
            orderListHTML(data.orders, status);
            var pagination = generatePagination(data.total_pages, page, 'setOrderList');
            $("#orders-list-pagination .pagination").html(pagination);
        }
    });
}

$("#search").on('input', function(){
    setOrderList(1);
    
});


$(document).ready(function(){
    setStatus(statusGET);
});
