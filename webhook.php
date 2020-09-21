<?php
$my_verify_token ="ASGARDIAN007";
$challenge = $_GET['hub_challenge'];
$verify_token = $_GET['hub_verify_token'];
if($my_verify_token === $verify_token){
    echo $challenge;
    exit;

   
}

$access_token = "your-facebook-token";

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

