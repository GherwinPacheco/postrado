function submitOtp(){
    var email = $("#email").val();
    var otp = $("#otp").val();
    var otpPromise = new Promise(function(resolve, reject) {
        $.ajax({
            url: "actions/configActions.php",
            type: 'POST',
            data: {
                action: 'check_otp',
                email: email,
                otp: otp
            },
            success: function(data) {
                resolve(data); // Resolve the promise with the data on success
            },
            error: function(xhr, status, error) {
                reject(error); // Reject the promise with the error on failure
            }
        });
    });

    otpPromise.then(function(data) {
        if(data){
            $("#dataForm").submit();
        }
        else{
            alertMessage('The OTP does not match', 'danger');
        }
    })
    .catch(function(error) {
        console.error('Error:', error);
    });

}

function startTimer() {
    var email = $("#email").val();
    newCode(email);

    const resendBtn = document.getElementById('resendOtp');
    const timerDisplay = document.getElementById('timerDisplay');
    let timer = localStorage.getItem('resendTimer') || 30; // Get the saved timer or start at 30 seconds

    resendBtn.disabled = true;

    const countdown = setInterval(() => {
        timer--;
        timerDisplay.innerHTML = `You can resend OTP in <br>${timer} seconds`;
        localStorage.setItem('resendTimer', timer); // Save the remaining time in localStorage

        if (timer <= 0) {
            clearInterval(countdown);
            resendBtn.disabled = false;
            timerDisplay.textContent = "";
            localStorage.removeItem('resendTimer'); // Remove the timer from localStorage when it reaches 0
        }
    }, 1000);
}

window.onload = function () {
    const savedTimer = localStorage.getItem('resendTimer');
    if (savedTimer && savedTimer > 0) {
        startTimer();
    }
};
