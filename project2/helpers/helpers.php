<?php
date_default_timezone_set('Asia/Kuala_Lumpur');

function get_datetime_now()
{
    date_default_timezone_set('Asia/Kuala_Lumpur');
    return date('Y-m-d H:i:s');
}

function time_ago($datetime)
{
    $timestamp = strtotime($datetime);
    $diff = time() - $timestamp;

    if ($diff < 0) {
        return 'just now';
    }

    if ($diff < 60) {
        return $diff . ' seconds ago';
    } elseif ($diff < 3600) {
        return floor($diff / 60) . ' minutes ago';
    } elseif ($diff < 86400) {
        return floor($diff / 3600) . ' hours ago';
    } elseif ($diff < 604800) {
        return floor($diff / 86400) . ' days ago';
    } elseif ($diff < 2592000) {
        return floor($diff / 604800) . ' weeks ago';
    } elseif ($diff < 31536000) {
        return floor($diff / 2592000) . ' months ago';
    } else {
        return floor($diff / 31536000) . ' years ago';
    }
}

// function send_email($to, $subject, $body)
// {
//     require_once(__DIR__ . '/../vendor/phpmailer/phpmailer/class.phpmailer.php');
//     $mail = new PHPMailer(true);
//     try {
//         $mail->isSMTP();
//         $mail->Host       = 'in-v3.mailjet.com';
//         $mail->SMTPAuth   = true;
//         $mail->Username   = '6ea793c17d863917cf1bb9115e498676';
//         $mail->Password   = 'e15dcc4504a8b1856dca3f66e30ea27e';
//         $mail->SMTPSecure = 'tls';
//         $mail->Port       = 587;

//         $mail->setFrom('thurazaw.web@gmail.com', 'Shang Ya _ Techtonic');
//         $mail->addAddress($to);

//         $mail->isHTML(false);
//         $mail->Subject = $subject;
//         $mail->Body    = $body;

//         // Enable full SMTP debug output
//         $mail->SMTPDebug = 2;
//         $mail->Debugoutput = function($str, $level) {
//             echo "Debug level $level: $str<br>";
//         };

//         $mail->send();
//         return true;
//     } catch (phpmailerException $e) {
//         // Show both the exception message and PHPMailer error info
//         return "Mailer Error: " . $e->getMessage() . " | PHPMailer ErrorInfo: " . $mail->ErrorInfo;
//     }
// }

function send_email($to, $subject, $body)
{
    $apiKey = '6ea793c17d863917cf1bb9115e498676';
    $apiSecret = 'e15dcc4504a8b1856dca3f66e30ea27e';

    $data = [
        'Messages' => [
            [
                'From' => [
                    'Email' => "thurazaw.web@gmail.com",
                    'Name' => "Shang Ya _ Techtonic"
                ],
                'To' => [
                    [
                        'Email' => $to,
                        'Name' => ""
                    ]
                ],
                'Subject' => $subject,
                'TextPart' => $body
            ]
        ]
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.mailjet.com/v3.1/send");
    curl_setopt($ch, CURLOPT_USERPWD, "$apiKey:$apiSecret");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    $response = curl_exec($ch);
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_status == 200) {
        return true;
    } else {
        return "Mailjet API error: $response";
    }
}

function refValues($arr)
{
    if (strnatcmp(phpversion(), '5.3') >= 0) {
        $refs = array();
        foreach ($arr as $key => $value)
            $refs[$key] = &$arr[$key];
        return $refs;
    }
    return $arr;
}
