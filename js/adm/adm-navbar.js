$(document).ready(function(){
    //set the account buttons when the page loaded
    setAccountsBtn();
});

//toggle sidebar on clicking sidebar btn
$("#sidebar-btn").click(function(){
    toggleSidebar();
});

//expand sidebar when options is clicked
$("#sidebar").click(function(){
    showSidebar();
});


//hide other collapse when an option is clicked
$(".list-group-item").click(function(){
    $("#sidebar .collapse").collapse("hide");
});



function toggleSidebar(){
    var sidebar = $("#sidebar");
    var mainDiv = $("#main-div");

    $('#sidebar .collapse').collapse('hide');
    if(sidebar.css("width") === "60px"){
        showSidebar();
    }
    else{
        hideSidebar();
    }
    
}

function showSidebar(){
    var sidebar = $("#sidebar");
    var mainDiv = $("#main-div");

    if(sidebar.css("width") !== '200px'){
        sidebar.css("width", "200px");
        //var mainNewWidth = parseInt(mainDiv.css("width")) - 200;
        //mainDiv.css("width", `${mainNewWidth}px`);
        mainDiv.css("margin-left", "230px");
        mainDiv.css("padding-right", "30px");
    }
    
}

function hideSidebar(){
    var sidebar = $("#sidebar");
    var mainDiv = $("#main-div");
    sidebar.css("width", "60px");
    //var mainNewWidth = parseInt(mainDiv.css("width")) + 200;
    //mainDiv.css("width", `${mainNewWidth}px`);
    mainDiv.css("margin-left", "70px");
    mainDiv.css("padding-right", "15px");
}



function setAccountsBtn(){
    $.get("./fetch/getUserDetails.php", function (data){
        $("#nav-user").html(`
            <div class="btn-group dropdown p-0 d-inline">
                <button class="btn dropdown border-circle pl-0" id="user-btn" type="button" data-toggle="dropdown" aria-expanded="false">
                    <img class="rounded-circle" src="./img/profiles/${data.id}.png?t=${time}" style="width: 40px; height: 40px">&emsp;${data.username}
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">Account Details</a>
                    <a class="dropdown-item" href="./logout.php">Logout</a>
                </div>
            </div>
        `);

        $("#sidebar-user").html(`
            <button class="btn dropdown border-circle" id="user-btn" type="button" data-toggle="dropdown" aria-expanded="false" onclick="showSidebar()">
                <img class="rounded-circle" src="./img/profiles/${data.id}.png?t=${time}" style="width: 35px; height: 35px; margin-right: 25px;">${data.username}
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="#">Account Details</a>
                <a class="dropdown-item" href="./logout.php">Logout</a>
            </div>
        `);
    });
}



function setAccountsBtn(){
    $.get("./fetch/getUserDetails.php", function (data){
        $("#nav-user").html(`
            <div class="btn-group dropdown p-0 d-inline">
                <button class="btn dropdown border-circle pl-0" id="user-btn" type="button" data-toggle="dropdown" aria-expanded="false">
                    <img class="rounded-circle" src="./img/profiles/${data.id}.png?t=${time}" style="width: 40px; height: 40px">&emsp;${data.username}
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" role="button" data-toggle="modal" data-target="#userDetailsModal" onclick="setAccountDetails()">Account Details</a>
                    <a class="dropdown-item" href="./logout.php">Logout</a>
                </div>
            </div>
        `);

        $("#sidebar-user").html(`
            <button class="btn dropdown border-circle" id="user-btn" type="button" data-toggle="dropdown" aria-expanded="false" onclick="showSidebar()">
                <img class="rounded-circle" src="./img/profiles/${data.id}.png?t=${time}" style="width: 35px; height: 35px; margin-right: 25px;">${data.username}
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" role="button" data-toggle="modal" data-target="#userDetailsModal" onclick="setAccountDetails()">Account Details</a>
                <a class="dropdown-item" href="./logout.php">Logout</a>
            </div>
        `);
    });
}



