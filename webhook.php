<?php
$my_verify_token ="ASGARDIAN007";
$challenge = $_GET['hub_challenge'];
$verify_token = $_GET['hub_verify_token'];
if($my_verify_token === $verify_token){
    echo $challenge;
    exit;

    //https://7d45529a29e8.ngrok.iochat-application-using-php-ajax-jquery/index.php
}

$access_token = "EAAK18hngcAsBAKZCQvs2NnDLlrgBfOZBVQftpHANfe9TVT79ygemQM2lYU4TdaZCCNyZA8gGZB2QJZAE4GEsD9l62hcdk3ullnnDxtTBePYrQuYY9bsxke03zXBfsiMyD2JwOFMdco1SwNB1e5vJyijVOuYu4IFcGrg12kgFcwNhQuBF8mSQWS";

$response = file_get_contents("php://input");
$response2 = json_decode($response,true);
$message = $response2['entry'][0]['messaging'][0]['message']['text'];
$sender = $response2['entry'][0]['messaging'][0]['sender']['id'];
file_put_contents("test.txt",$sender);
include_once('database_connection.php');
$prequery = "
SELECT * FROM chat_message WHERE from_user_id ='$sender' 
ORDER BY timestamp ASC 
LIMIT 1";
$prestatement = mysqli_query($connect,$prequery);
$result = mysqli_fetch_array($prestatement);
if(mysqli_num_rows($result) == 1){
    $query = "
    INSERT INTO chat_message (from_user_id, chat_message, status, assigned_to) values('$sender', '$message','1', '".$result['assigned_to']."')";
    $statement = mysqli_query($connect, $query);
}else{
    $query = "
    INSERT INTO chat_message (from_user_id, chat_message, status) values('$sender', '$message','1')";
    $statement = mysqli_query($connect, $query);

}


