var d = new Date();
let time = d.getTime();


function checkEmail(email) {
    return new Promise(function(resolve, reject) {
        $.ajax({
            url: "fetch/getConfig.php",
            type: 'GET',
            data: {
                request: 'users_data',
                email: email
            },
            success: function(data) {
                // Check if data is not empty
                if (data.length > 0) {
                    resolve(data); // Resolve with true if there is data
                } else {
                    resolve(false); // Resolve with false if there is no data
                }
            },
            error: function(xhr, status, error) {
                reject(error); // Reject the promise with the error on failure
            }
        });
    });
}



function showPassword(elem) {
    var x = $(`#${elem}`);
    if (x.attr("type") === "password") {
        x.attr("type", "text");
    } else {
        x.attr("type", "password");
    }
  }


function newCode(email) {
    return new Promise(function(resolve, reject) {
        $.ajax({
            url: "actions/configActions.php",
            type: 'POST',
            data: {
                action: 'new_code',
                email: email
            },
            success: function(data) {
                resolve(data); // Resolve the promise with the data on success
            },
            error: function(xhr, status, error) {
                reject(error); // Reject the promise with the error on failure
            }
        });
    });
}


function setAccountDetails(){
    $.get("./fetch/getUserDetails.php", function (data){
        $("#userDetails-userImg").attr("src", `./img/profiles/${data.id}.png`);
        $("#userDetails-userId").val(data.id);
        $("#userDetails-username").val(data.username);
        $("#userDetails-email").val(data.email);
        $("#userDetails-firstname").val(data.first_name);
        $("#userDetails-lastname").val(data.last_name);
        $("#userDetails-suffix").val(data.suffix);
        $("#userDetails-address").val(data.home_address);
        $("#userDetails-contact").val(data.contact);
    });
}


//show image when input file uploaded
function showImg(input, id) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
        $(`#${id}`).attr('src', e.target.result);
        };
        
        reader.readAsDataURL(input.files[0]);
    }
    else{
        $(`#${id}`).attr('src', './img/products/product_default.svg');
    }
}


function showMultipleImages(input, id) {
    const files = input.files;
    const container = $(`#${id}`);

    // Clear previous content
    container.empty();

    if (files && files.length > 0) {
        Array.from(files).forEach((file) => {
            const reader = new FileReader();

            reader.onload = function (e) {
                // Append each image with a unique ID
                container.append(`
                    <img class="border" src="${e.target.result}" alt="Custom Image" style="width: 150px; margin: 5px;">
                `);
            };

            reader.readAsDataURL(file);
        });
    } else {
        // Display default content if no files are selected
        container.html(`
            <p class="text-center m-0 p-0">Upload Images</p>
        `);
    }
}




function formatDate(inputDate, format) {
    // Create a new Date object from the input string

    if(inputDate){
        const [year, month, day] = inputDate.split('-');
        const date = new Date(year, month - 1, day); // Months are 0-based in JavaScript Date

        // Array of month names
        const monthNames = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        // Extract parts of the date
        const monthName = monthNames[date.getMonth()];
        const dayOfMonth = date.getDate();
        const yearFull = date.getFullYear();

        // Replace placeholders in the format string
        let formattedDate = format
            .replace('F', monthName)
            .replace('d', dayOfMonth)
            .replace('Y', yearFull);

        return formattedDate;
    }
    
}

function alertMessage(txt, res){
    $("#alert-text").html(txt);
    $("#alert-message").removeClass('alert-success');
    $("#alert-message").removeClass('alert-primary');
    $("#alert-message").removeClass('alert-warning');
    $("#alert-message").removeClass('alert-danger');
    
    $("#alert-message").addClass(`alert-${res}`);
    $("#alert-message").addClass('show');
    setTimeout(
        function() {
            $("#alert-message").removeClass('show');
        }, 3000
    );
}

function titleCase(str) {
    return str.toLowerCase().split(' ').map(function (word) {
        return (word.charAt(0).toUpperCase() + word.slice(1));
    }).join(' ');
}


function roundNum(x){
    x = parseFloat(x);
    return parseFloat(x.toFixed(2));
}

function numformat(x) {
    var num = parseFloat(x);
    return num.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}


function numcomma(x) {
    var num = parseFloat(x);
    
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}



//function for generating paginations
function generatePagination(totalPages, page, someFunc){
    var output = '';
    if(totalPages > 1){
        

        //first button
        output = output.concat(`
            <li class="page-item ${(page == 1 ? "disabled" : "")}">
                <a class="page-link" onclick="${someFunc}(${page - 1})">Previous</a>
            </li>
        `);

        
        var fNum = page < 3 ? 1 : page - 2;
        var lNum = page > 3 ? page + 2 : (totalPages > 5 ? 5 : totalPages);
        
        if(page >= (totalPages - 2)){
            fNum = totalPages - 4;
            lNum = totalPages;
        }
        

        for(var x = fNum; x <= lNum; x++){
            if(x < 1){continue;}
            output = output.concat(`
                <li class="page-item ${(x == page ? "active": "")}">
                    <a class="page-link" onclick="${someFunc}(${x})">${x}</a>
                </li>
            `);
        }

        //last button
        output = output.concat(`
            <li class="page-item ${(page == totalPages ? "disabled" : "")}">
                <a class="page-link" onclick="${someFunc}(${(page+1)})">Next</a>
            </li>
        `);

        
    }

    return output;
}

//prevent mysql chars on input
$("textarea").keydown(function(e){
    switch(e.keyCode) {
        case 13:    //prevent next line on textarea
            //e.preventDefault();
            break;
        default:
            //
    }
});


function updateStatus(){
    $.post("./actions/productActions.php", {
        action: 'update_status'
    }, function (data){
        
    });
}



$('.ressetable-modal').on('hidden.bs.modal', function (e) {
    $(".modal-form").trigger('reset');
});


$(document).ready(function(){
    updateStatus();
});


