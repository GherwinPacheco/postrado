<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../vendor/autoload.php';


$config = $conn->query("SELECT * FROM config WHERE 1 LIMIT 1")->fetch_assoc();

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = $config['mailer_email'];                     //SMTP username
    $mail->Password   = $config['mailer_pass'];                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    $mail->setFrom($config['mailer_email'], 'Postrado');
    
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

function sendMail($mail, $message){
    //Recipients
    $mail->addAddress($message['email'], $message['name']);

    $embeddedImage = isset($message['embeddedImage']) ? $message['embeddedImage'] : '';
    $embeddedImageName = isset($message['embeddedImageName']) ? $message['embeddedImageName'] : '';
    
    //$mail->addEmbeddedImage($embeddedImage, $embeddedImageName);

    //Content
    $mail->isHTML(true);
    $mail->Subject = $message["subject"];

    $mail->Body= $mail->Body = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <style>
                body {
                    font-family: "Calibri", sans-serif;
                    font-size: 16px;
                    line-height: 1.5;
                    margin: 0;
                    padding: 0;
                    background-color: #f4f4f4;
                    color: #333;
                }

                .email-container {
                    width: 100%;
                    max-width: 600px;
                    margin: 0 auto;
                    background-color: #ffffff;
                    padding: 20px;
                    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                }

                .header {
                    text-align: center;
                    margin-bottom: 40px;
                    font-size: 42px;
                    margin: 0;
                    color: #79341e;
                    font-weight: bold;
                }

                .content {
                    margin-bottom: 30px;
                    font-size: 18px;
                    margin-bottom: 20px;
                }

                .content h2 {
                    font-size: 32px;
                    color: #555;
                }


                .footer {
                    font-size: 14px;
                    color: #777;
                    text-align: center;
                }

                .footer p {
                    margin: 5px 0;
                }
            </style>

        </head>
        <body>
            <div class="email-container">
                '.$message["body"].'
            </div>
        </body>
        </html>
    ';
    $mail->AltBody = $message["altBody"];

    $mail->send();
}


/*

 */
