<?php
$my_verify_token ="ASGARDIAN007";
$challenge = $_GET['hub_challenge'];
$verify_token = $_GET['hub_verify_token'];
if($my_verify_token === $verify_token){
    echo $challenge;
    exit;

    //https://7d45529a29e8.ngrok.iochat-application-using-php-ajax-jquery/index.php
}

$access_token = "EAAK18hngcAsBAGyex2nbuk3WKomV9BZAQiTyj56JgtHNCL0ukhZCopZC8VCrTtRsyDhy7zfXaOhs5JyF5L3z3rrNDAMZAuihGsixYZBtr2IVJhhju8nInsrmDhDl8b21yk3ZANyZCwZAVZA3BjYkg1ZCTr3SSqKAvewmOJzqEhmnfN36LDZCHvoHU6D";

$response = file_get_contents("php://input");
$response2 = json_decode($response,true);
$message = $response2['entry'][0]['messaging'][0]['message']['text'];
$sender = $response2['entry'][0]['messaging'][0]['sender']['id'];

//file_put_contents("test.txt",$sender);
include_once('database_connection.php');
$preque = "SELECT * FROM chat_message WHERE from_user_id ='$sender' AND assigned_to !='' LIMIT 1";
$prestatement = mysqli_query($connect,$preque);
$result = mysqli_fetch_array($prestatement);
if(mysqli_num_rows($prestatement) == 1){
    $query = "
INSERT INTO chat_message (from_user_id, chat_message, status, assigned_to) values('$sender', '$message','1',".$result['assigned_to'].")";
$statement = mysqli_query($connect, $query);
}else{
$query = "
INSERT INTO chat_message (from_user_id, chat_message, status) values('$sender', '$message','1')";
$statement = mysqli_query($connect, $query);
}

