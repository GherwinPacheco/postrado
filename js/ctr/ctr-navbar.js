$(document).ready(function(){
    setAccountsDiv();
    showCartList();
    showNotifList();
});

function customPriceText(elem, jsonKey, txtContainer){
    var jsonValue = JSON.parse($(elem).val());
    $(`#${txtContainer}`).html(`₱ ${numformat(jsonValue[jsonKey])}`);
}


function orderRedirect(id, orderType, search, orderStatus, notifStatus){
    if(notifStatus === 'unread'){
        $.ajax({
            url: "./actions/notificationActions.php",
            type: "POST",
            data: {
                action: 'mark_read',
                notifId: id
            },
            success: function (data){
            }
        });
    }

    var pageRedirect = orderType === 'normal' ? 'ctr-orders.php' : 'ctr-customRequests.php';
    window.location.href = `./${pageRedirect}?search=${encodeURIComponent(search)}&status=${orderStatus}`;
}






function showCartList(){
    $('#cartListNavbar').html("");
    $.ajax({
        url: "./fetch/getCart.php",
        type: "GET",
        data: {
            request: 'carts_data',
            orderBy: 'date_created',
            orderMethod: 'DESC'
        },
        dataType: "json",
        success: function (data){
            var count = 1;
            if(data.length > 0){
                for(ct of data){
                    $('#cartListNavbar').append(`
                        <a href="./ctr-productView.php?p=${ct.product_id}" class="list-item text-dark">
                        <div class="row pt-1 pb-3">
                            <div class="col col-3 d-flex justify-content-center align-items-center">
                                <img class="rounded" src="./img/products/${ct.product_id}.png?t=${time}" alt="" style="width: 70px; height: 70px">
                            </div>
                            <div class="col col-9 pt-3 d-flex justify-content-start align-items-center">
                                <div>
                                    <span class="text-medium">${ct.product_name}</span><br>
                                    <span class="text-muted">₱ ${numformat(ct.total)}</span><br>
                                    <span class="text-muted">Qty: ${ct.quantity}</span>&emsp;
                                    <span class="text-muted" title="₱ ${numformat(ct.varnish_total)}">${ct.varnish === "1" ? "Varnished&emsp;" : ""}</span>
                                    <span class="text-muted" title="₱ ${numformat(ct.color_total)}">${ct.color_name ? `Color: ${ct.color_name}` : ""}</span>
                                </div>
                                
                            </div>
                        </div>    
                        </a>
                    `); 

                    if(count >= 5){
                        break;
                    }
                    count++;

                }

                $("#viewAllCartBtn").removeClass("hide");


                $(".cartCount").html(`
                    <span class="navBtnNum d-inline-block rounded-circle bg-danger text-white">${data.length}</span>
                `);
            }
            else{
                $('#cartListNavbar').html(`
                    <div class="d-flex justify-content-center align-items-center flex-column" style="height: 300px">
                        <img src="./img/no_cart.svg" style="width: 100px; height: 100px">
                        <h5 class="text-medium">The Cart is Empty</h5>
                        <br>
                        <a href="./ctr-catalog.php" class="btn btn-dblue">Browse Items Now</a>
                    </div>    
                `);
            }
            

            
        }
    });

    
}


function showNotifList(){
    $('#cartListNavbar').html("");
    //for unread notifications
    $.ajax({
        url: "./fetch/getNotifications.php",
        type: "GET",
        data: {
            request: 'order_notifs',
            orderBy: 'date_created',
            orderMethod: 'DESC',
            limit: 20
        },
        dataType: "json",
        success: function (data){
            
            if(data.length > 0){
                var unreadCount = 0;

                for(nt of data){
                    var source = `./img/notifications/order_${nt.order_status}.png?t=${time}`;

                    var unreadBackground = '';
                    if(nt.notif_status === 'unread'){
                        unreadCount++;
                        unreadBackground = 'background-color: #e6f2ff;';
                    }

                    var notifTitle = nt.order_id ? `ORD-${nt.order_id}` : nt.custom_name;

                    var orderStatus = '';
                    var orderType = '';
                    var orderSearch = '';
                    if(nt.order_id){
                        orderStatus = nt.order_status;
                        orderType = 'normal';
                        orderSearch = `ORD-${nt.order_id}`;
                    }
                    else{
                        orderType = 'custom';
                        orderSearch = nt.custom_name;
                        if(nt.order_status === 'custom_pending'){
                            orderStatus = 'admin_pending';
                        } else if(nt.order_status === 'custom_approved'){
                            orderStatus = 'pending';
                        } else if(nt.order_status === 'custom_updated'){
                            orderStatus = 'updated';
                        } else if(nt.order_status === 'custom_declined'){
                            orderStatus = 'declined';
                        }
                    }

                    $("#notifListNavbar").append(`
                        <div class="row py-2" onclick="orderRedirect('${nt.id}', '${orderType}', '${orderSearch}', '${orderStatus}', '${nt.notif_status}')" style="${unreadBackground}">
                            <div class="col col-2 d-flex justify-content-center align-items-center">
                                <img class="rounded" src="${source}" alt="" style="width: 50px; height: 50px">
                            </div>
                            <div class="col col-10">
                                <span class="text-medium">${notifTitle}</span><br>
                                <span class="text-muted">${nt.message}</span><br>
                                <small class="text-muted d-block text-right">${formatDate(nt.date_created, 'F d, Y')}</small>
                            </div>
                            
                        </div>
                    `); 

                }


                $(".notifCount").html(`
                    <span class="navBtnNum d-inline-block rounded-circle bg-danger text-white">${unreadCount > 0 ? unreadCount : ''}</span>
                `);
            }
            

            
        }
    });

    

    
}

function showOrderList(){
    $('#orderListNavbar').html("");
    var user = $("#userId").val() != undefined ? $("#userId").val() : 'none';
    
    $.ajax({
        url: "./fetch/getOrders.php",
        type: "GET",
        data: {
            request: 'orders_data',
            orderBy: 'date_created',
            orderMethod: 'DESC',
            user: user
        },
        dataType: "json",
        success: function (data){
            var count = 1;
            
            if(data.length > 0){
                for(order of data){
                    var deadlineText = '';
                    if(order.order_status === 'Complete'){
                        deadlineText = 'Date Completed';
                    }
                    else if(order.order_status === 'Preparing'){
                        deadlineText = 'Expected Arrival';
                    }
                    else if(order.order_status === 'Ready'){
                        deadlineText = 'Arrival';
                    }
                    else{
                        deadlineText = 'Deadline';
                    }
                    
                    var paymentStatusHTML = '';
                    if(order.paid_amount < order.down_payment){
                        paymentStatusHTML = `
                            <span class="badge badge-secondary">Unpaid</span>
                        `;
                    }
                    else if(order.paid_amount == order.total){
                        paymentStatusHTML = `
                            <span class="badge badge-success">Paid</span>
                        `;
                    }
                    else if(order.paid_amount >= order.down_payment || order.paid_amount < order.total){
                        paymentStatusHTML = `
                            <span class="badge badge-primary">Partially Paid</span>
                        `;
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

                    $('#orderListNavbar').append(`
                        <a href="./ctr-orderView.php?p=${order.id}" class="list-item text-dark">
                        <div class="row pt-1 pb-3 mb-3 border-bottom">
                            <div class="col col-8">
                                <span>#ORD-${order.id}</span>
                                &nbsp;
                                <span class="text-medium text-info">${order.order_status}</span>
                                ${itemsHTML}
                            </div>
                            <div class="col col-4 d-flex align-items-center justify-content-center flex-column w-100">
                                <small class="text-medium ${order.payment_method === 'Cash' ? 'text-success' : 'text-primary'}">${order.payment_method} Payment</small>
                                <small class="text-muted text-dark">${order.pickup_method}</small>
                            </div>
                            
                            <div class="col col-6 mt-3">
                                <small class="text-muted">
                                    ${deadlineText}:&nbsp;
                                    <span class="text-medium">${formatDate((order.order_status === 'complete' ? order.date_completed : order.completion_date), 'F d')}</span>
                                </small>
                            </div>

                            <div class="col col-6 mt-3 text-right text-medium">
                                <span class="text-primary">₱ ${numformat(order.total)}&emsp;${paymentStatusHTML}</span>
                            </div>
                            
                            
                        </div>    
                        </a>
                    `); 

                    if(count >= 5){
                        break;
                    }
                    count++;

                }

                $('#orderListNavbar').append(`
                    <div class="d-flex justify-content-center align-items-center">
                        <a href="./ctr-orders.php">View All</a>
                    </div>
                `); 

                $(".orderCount").html(`
                    <span class="navBtnNum d-inline-block rounded-circle bg-danger text-white">${data.length}</span>
                `);
            }
            else{
                $('#orderListNavbar').html(`
                    <div class="d-flex justify-content-center align-items-center flex-column" style="height: 300px">
                        <img src="./img/no_cart.svg" style="width: 100px; height: 100px">
                        <h5 class="text-medium">You have no ongoing orders</h5>
                        <br>
                        <a href="./ctr-catalog.php" class="btn btn-dblue">Browse Items Now</a>
                    </div>    
                `);
            }
            

            
        }
    });

    
}




$("#addCustomModal").on("hidden.bs.modal", function(e){
    $("#addCustom-imageContainer").html(`
        <p class="text-center m-0 p-0">Upload Images</p>
    `);
})




$('.navbar .dropdown-menu').on("click.bs.dropdown", function (e) {
    e.stopPropagation();
    //e.preventDefault();                             
});



function setAccountsDiv(){
    $.ajax({
        url: "./fetch/getUserDetails.php",
        dataType: "json",
        success: function (data){
            if(data){
                accountButton(data);
            }
        },
        complete: function(){
            if($("#nav-user").is(':empty')){
                signInButton();
            }
        }
    }
    
);

}

function accountButton(data){

    $("#nav-user").html(`
        <div class="btn-group dropleft p-0 d-inline">
            <button class="btn dropdown border-circle pl-0" id="user-btn" type="button" data-toggle="dropdown" aria-expanded="false">
                <img class="rounded-circle" src="./img/profiles/${data.id}.png?t=${time}" style="width: 50px; height: 50px">
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" role="button" data-toggle="modal" data-target="#termsModal">Terms and Conditions</a>
                <a class="dropdown-item" role="button" data-toggle="modal" data-target="#userDetailsModal" onclick="setAccountDetails()">Account Details</a>
                <a class="dropdown-item" href="./logout.php">Logout</a>
            </div>
        </div>
    `);

    $("#bottomnav-user").html(`
        <div class="dropup p-0 m-0">
            <button class="btn dropdown border-circle p-0" id="user-btn" type="button" data-toggle="dropdown" aria-expanded="false">
                <img class="rounded-circle" src="./img/profiles/${data.id}.png?t=${time}" style="width: 45px; height: 45px">
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" role="button" data-toggle="modal" data-target="#termsModal">Terms and Conditions</a>
                <a class="dropdown-item" role="button" data-toggle="modal" data-target="#userDetailsModal" onclick="setAccountDetails()">Account Details</a>
                <a class="dropdown-item" href="./logout.php">Logout</a>
            </div>
        </div>
    `);
}

    function signInButton(){
    const btn = `
        <a href="login.php" class="btn btn-white text-dbrown text-medium p-0" id="signup-btn">
            <i class="fa-solid fa-user"></i><br>
            Sign In
        </a>
    `;
    $("#nav-user").html(btn);
    $("#bottomnav-user").html(btn);
}
