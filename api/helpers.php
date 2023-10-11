<?php
function sendGCM($title, $message, $token) {


    $url = 'https://fcm.googleapis.com/fcm/send';

    $fields = '{
        "to": "'.$token.'",
        "notification": {
            "sound": "default",
            "body": "'.$message.'",
            "content_available": true,
            "title": "'.$title.'" ,
            "imageUrl": "https://reparteat.com/template/images/logo_green.png"
            }
    }';

    $headers = array (
            'Authorization: key=' . "AAAAvCDXxG4:APA91bERaoHDyaqfE83CVLshesHp5ZiDMNpZ5EX0_1QhNlEb_Rso-1YgoKVI--QPNTwEskGoURrAYJIfzP0fVjhGNtmIbhs0LTvwOiaPxD217L2sebZlPzBgrV-dvFRe40v84cVdGzw7",
            'Content-Type: application/json'
    );

    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_POST, true );
    curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

    $result = curl_exec ( $ch );
    //echo $result;
    curl_close ( $ch );
}
