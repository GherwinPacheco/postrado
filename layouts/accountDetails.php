
<!-- User Details -->
<form class="modal-form" method="post" action="actions/configActions.php" enctype="multipart/form-data">
    <div class="modal fade ressetable-modal" id="userDetailsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-medium" id="exampleModalLongTitle">Account Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="userDetails-userId" name="userId">
                <img id="userDetails-userImg" class="userImgModal float-right rounded-circle" src="./img/profiles/default_user.png" alt="">
                <div class="w-50">
                    <label for="userDetails-imageFile">Profile Image</label>
                    <input type="file" name="imageFile" id="userDetails-imageFile" accept="image/*"  onchange="showImg(this, 'userDetails-userImg')">
                </div><br>
                
                <hr>
                

                <div class="row mb-3">
                    <div class="col">
                        <label for="userDetails-username">Username<span class="text-danger">*</span></label>
                        <input type="text" name="username" id="userDetails-username" class="form-control" required>
                    </div>
                    <div class="col">
                        <label for="userDetails-email">Email<span class="text-danger">*</span></label>
                        <input type="email" name="email" id="userDetails-email" class="form-control" required>
                        <span class="text-danger" id="email-error"></span>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="">Full Name<span class="text-danger">*</span></label>
                    <div class="d-flex mb-3">
                        <input type="text" name="firstname" id="userDetails-firstname" class="form-control" placeholder="First Name" required>
                        <input type="text" name="lastname" id="userDetails-lastname" class="form-control" placeholder="Last Name" required>
                        <select class="form-control" name="suffix" id="userDetails-suffix" style="width: 100px">
                            <option value="">Suffix</option>
                            <option value="Jr.">Jr.</option>
                            <option value="Sr.">Sr.</option>
                            <option value="II">II</option>
                            <option value="III">III</option>
                            <option value="IV">IV</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label for="userDetails-address">Address<span class="text-danger">*</span></label>
                        <input type="text" name="address" id="userDetails-address" class="form-control" required>
                    </div>
                    <div class="col">
                        <label for="userDetails-contact">Contact #<span class="text-danger">*</span></label>
                        <input class="form-control" type="tel" id="userDetails-contact" name="contact" placeholder="09123456789" pattern="09\d{9}"  required>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col">
                        <label for="userDetails-newPassword">New Password</label>
                        <input type="password" 
                            name="newPassword" 
                            id="userDetails-newPassword" 
                            class="form-control passwordForm" 
                            pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}" 
                            title="Must contain at least one number, one uppercase, one lowercase letter, one special character, and at least 8 or more characters"
                            autocomplete="new-password"/>
                            <span class="text-danger" id="password-error"></span>
                            <input type="checkbox" class="mb-2" onclick="showPassword('userDetails-newPassword');showPassword('userDetails-confirmPassword')">&emsp;Show Password
                            
                    </div>
                    <div class="col">
                        <label for="userDetails-confirmPassword">Confirm Password</label>
                        <input class="form-control passwordForm" 
                            type="password" 
                            id="userDetails-confirmPassword" 
                            name="confirmPassword"
                            pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}" 
                            title="Must contain at least one number, one uppercase, one lowercase letter, one special character, and at least 8 or more characters"
                            autocomplete="new-password"/>
                        
                    </div>
                </div>
                <small class="d-block text-muted mb-3">Leave blank if not changing your password</small>

                <hr>

                <div class="row">
                    <div class="col">
                        <label for="userDetails-password">Password<span class="text-danger">*</span></label>
                        <input type="password" name="password" id="userDetails-password" class="form-control" autocomplete="new-password" required>
                        <input type="checkbox" class="mb-2" onclick="showPassword('userDetails-password')">&emsp;Show Password
                    </div>
                    <div class="col"></div>
                </div>
                <small class="d-block text-muted mb-3">Enter your current password to apply changes</small>

                
                
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="saveBtn" name="action" value="update_account">Save Changes</button>
            </div>
            </div>
        </div>
    </div>
</form>

<script>
    var emailExist = false;
    var passwordMismatch = false;


    function goBtn(){
        if(emailExist == false && passwordMismatch == false){
            $("#saveBtn").attr('disabled', false);
        }
        else{
            $("#saveBtn").attr('disabled', true);
        }
    }

    $("#userDetails-email").on("input", function(){
    var email = $(this).val();
    checkEmail(email)
        .then(data => {
            if( data && email !== '' ){
                if(data.email !== email)
                //$("#saveBtn").attr('disabled', true);
                emailExist = true;
                $("#email-error").html('Email is already in use.');
            }
            else{
                //$("#saveBtn").attr('disabled', false);
                emailExist = false;
                $("#email-error").html('');
            }
            goBtn();
        })
        .catch(error => {
            console.error('Error:', error); // Handle error here
        });
    
});

$(".passwordForm").on("input", function(){
    var password = $("#userDetails-newPassword").val();
    var confirmPassword = $("#userDetails-confirmPassword").val();
    console.log(password + ' ' + confirmPassword)
    if(password !== confirmPassword){
        //$("#saveBtn").attr('disabled', true);
        passwordMismatch = true;
        $("#password-error").html('Password does not match');
    }
    else{
        //$("#saveBtn").attr('disabled', false);
        passwordMismatch = false;
        $("#password-error").html('');
    }
    goBtn();
});
</script>