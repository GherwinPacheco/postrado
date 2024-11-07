let bypassSubmit = false;

$('#createAccount-form').on('submit', function(event) {
    if (bypassSubmit) {
        return true; // Allow the form to submit if bypassSubmit is true
    }
    
    event.preventDefault(); // Prevent form submission by default
    
    let isValid = true;
    
    // Loop through each input field in the form
    $('#createAccount-form input').each(function() {
        if (!this.checkValidity()) {
            isValid = false;
            return false; // Exit loop if an invalid input is found
        }
    });
    
    var password = $("#password").val();
    var confirmPassword = $("#confirmPassword").val();

    var passwordMatch = password === confirmPassword;

    if (isValid) {
        if(passwordMatch){
            var email = $("#email").val();
            newCode(email)
                .then(function(data) {
                    bypassSubmit = true; // Set the flag to true to bypass preventDefault on the next submit
                    $('#createAccount-form').submit(); // Trigger form submission
                })
                .catch(function(error) {
                    alertMessage('Failed to send OTP to the email', 'danger');
                });
        }
        else{
            alertMessage('The password you have entered does not match', 'danger');
        }
        
    }
});




var emailExist = false;
var passwordMismatch = false;


function goBtn(){
    if(emailExist == false && passwordMismatch == false){
        $("#register-btn").attr('disabled', false);
    }
    else{
        $("#register-btn").attr('disabled', true);
    }
}



$("#email").on("input", function(){
    var email = $(this).val();
    checkEmail(email)
        .then(hasData => {
            if( hasData && email !== '' ){
                //$("#register-btn").attr('disabled', true);
                emailExist = true;
                $("#email-error").html('Email is already in use.');
            }
            else{
                //$("#register-btn").attr('disabled', false);
                emailExist = false;
                $("#email-error").html('');
            }
            goBtn()
        })
        .catch(error => {
            console.error('Error:', error); // Handle error here
        });
    
});


$(".passwordForm").on("input", function(){
    var password = $("#create-password").val();
    var confirmPassword = $("#create-password-confirm").val();
    if(password !== confirmPassword){
        //$("#register-btn").attr('disabled', true);
        passwordMismatch = true;
        $("#password-error").html('Password does not match');
    }
    else{
        //$("#register-btn").attr('disabled', false);
        passwordMismatch = false;
        $("#password-error").html('');
    }
    goBtn()
});