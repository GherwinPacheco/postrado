function setFormDetails(id){
    $.ajax({
        url: "fetch/getConfig.php",
        data: {
            request: 'users_data',
            id: id
        },
        success: function(data){
            for(user of data){
                $("#changeRole-userId").val(id);
                $("#changeRole-role").val(user.role);

                $("#deactivateAccount-userId").val(id);
                $("#activateAccount-userId").val(id);
            }
        }
    });
    
}

function accountsListHTML(data){
    $("#accounts-list tbody").html("");
    $("#accounts-list-mobile").html("");

    
    if(data.length > 0){
        for(user of data){

            var bgStyle = '';
            if(user.status == 0){
                bgStyle = 'background-color: #f2f2f2';
            }

            var dropdownHTML = user.status == 1 ? 
                `<a class="dropdown-item"  role="button" data-toggle="modal" data-target="#deactivateAccountModal" onclick="setFormDetails(${user.id})">Deactivate Account</a>` :
                `<a class="dropdown-item"  role="button" data-toggle="modal" data-target="#activateAccountModal" onclick="setFormDetails(${user.id})">Activate Account</a>`;
            

            var roleText = '';
            if(user.role == 1){
                roleText = 'Admin';
            } else if(user.role == 2){
                roleText = 'Carpenter';
            } else if(user.role == 3){
                roleText = 'Customer';
            }

            $("#accounts-list tbody").append(`
                <tr style="${bgStyle}">
                    <th>${user.num}</th>
                    <td><img class="rounded-circle" src="./img/profiles/${user.id}.png?t=${time}" style="width: 40px; height: 40px;"></td>
                    <td>${user.username}</td>
                    <td>${user.first_name} ${user.last_name} ${user.suffix}</td>
                    <td>${user.email}</td>
                    <td>${roleText}</td>
                    <td>
                        <div class="dropleft">
                            <button type="button" class="btn btn-white dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item"  role="button" data-toggle="modal" data-target="#changeRoleModal" onclick="setFormDetails(${user.id})">Change Role</a>
                                ${dropdownHTML}
                            </div>
                        </div>
                    </td>
                </tr>
            `);


            $("#accounts-list-mobile").append(`
                <div class="row pt-1 pb-3 mb-3 border-bottom" style="${bgStyle}">
                    <div class="col col-2">
                        <img class="rounded-circle" src="./img/profiles/${user.id}.png?t=${time}" style="width: 50px; height: 50px;">
                    </div>   
                    <div class="col col-8">
                        <small class="d-block text-medium">${user.username}</small>
                        <small class="d-block text-muted">${user.email}</small>
                        <small class="d-block text-muted">${roleText}</small>
                    </div>    
                    <div class="col col-2 text-center">
                        <div class="dropleft">
                            <button type="button" class="btn btn-white dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" role="button" data-toggle="modal" data-target="#changeRoleModal" onclick="setFormDetails(${user.id})">Change Role</a>
                                ${dropdownHTML}
                            </div>
                        </div>
                    </div>   
                </div>   
            `);
    
        }
    }
    else{
        $("#accounts-list tbody").append(`
            <tr>
                <td colspan="7" class="text-center">No Result</th>
            </tr>
        `);

        $("#accounts-list-mobile").html(`
            <div class="text-center vertical-middle">
                No Results
            </div>    
        `);
    }
    
}



function setAccountsList(page){
    
    $.ajax({
        url: "fetch/getConfig.php",
        data: {
            request: 'users_data',
            limit: 10,
            page: page
        },
        success: function(data){
            accountsListHTML(data.users);
            var pagination = generatePagination(data.total_pages, page, 'setAccountsList');
            $("#accounts-list-pagination .pagination").html(pagination);
        }
    });
}


$(document).ready(function(){
    setAccountsList(1);
});


