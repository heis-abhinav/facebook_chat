<?php

//insert_chat.php

include('database_connection.php');

session_start();
$access_token = "EAAK18hngcAsBAGyex2nbuk3WKomV9BZAQiTyj56JgtHNCL0ukhZCopZC8VCrTtRsyDhy7zfXaOhs5JyF5L3z3rrNDAMZAuihGsixYZBtr2IVJhhju8nInsrmDhDl8b21yk3ZANyZCwZAVZA3BjYkg1ZCTr3SSqKAvewmOJzqEhmnfN36LDZCHvoHU6D";


$query = " 
INSERT INTO chat_message 
(to_user_id, from_user_id, chat_message, status, assigned_to) 
VALUES ('".$_POST['to_user_id']."', '".$_SESSION['user_id']."', '".$_POST['chat_message']."', '1', 'self')
";

$statement = mysqli_query($connect,$query);
$reply_message =
'{
  "messaging_type": "RESPONSE",
  "recipient": {
    "id": "'.$_POST["to_user_id"].'"
  },
  "message": {
    "text": "'.$_POST["chat_message"].'"
  }
}';
send_reply($access_token,$reply_message);
function send_reply($access_token='',$reply=''){
    $url ="https://graph.facebook.com/v8.0/me/messages?access_token=".$access_token;
    $ch = curl_init();
    $headers = array("Content-type: application/json");
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
    curl_setopt($ch,CURLOPT_POST,1);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$reply);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    $st = curl_exec($ch);
    $result = json_decode($st,true);

}


echo fetch_user_chat_history($_SESSION['user_id'], $_POST['to_user_id']);
$query = "
UPDATE chat_message 
SET status = '0' 
WHERE from_user_id = '".$_POST['to_user_id']."'
AND status = '1'";

$statement = mysqli_query($connect,$query);
$query2 = "UPDATE chat_message SET assigned_to =".$_SESSION['user_id']." WHERE from_user_id =".$_POST['to_user_id'];
$statement2 = mysqli_query($connect,$query2);


?>