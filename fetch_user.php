<?php

//fetch_user.php

include('database_connection.php');

session_start();

$query = "SELECT * FROM chat_message WHERE (to_user_id='0' AND status = '1') OR (from_user_id = '".$_SESSION['user_id']."') OR (assigned_to ='".$_SESSION['user_id']."')";

$statement = mysqli_query($connect,$query);





$output = '
<table class="table table-bordered table-striped">
	<tr>
		<th width="70%">Username</td>
		
		<th width="10%">Action</td>
	</tr>
';
$box = array();
while($row = mysqli_fetch_array($statement)){
	
	if(!in_array($row['from_user_id'],$box)){
	$box[] = $row['from_user_id'];
	$status = '';
	$current_timestamp = strtotime(date("Y-m-d H:i:s") . '- 10 second');
	$current_timestamp = date('Y-m-d H:i:s', $current_timestamp);

	$output .= '
	<tr>
		<td>'.$row['from_user_id'].' '.count_unseen_message($row['from_user_id']).' </td>
		
		<td><button type="button" class="btn btn-info btn-xs start_chat" data-touserid="'.$row['from_user_id'].'" data-tousername="'.$row['from_user_id'].'">Start Chat</button></td>
	</tr>
	';
}
}

$output .= '</table>';

echo $output;

?>