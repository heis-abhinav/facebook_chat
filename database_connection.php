<?php

//database_connection.php

$connect = mysqli_connect('host','username','password','db',port);

date_default_timezone_set('Asia/Kolkata');

function fetch_user_last_activity($user_id)

{
	global $connect;
	$query = "
	SELECT * FROM login_details 
	WHERE user_id = '$user_id' 
	ORDER BY last_activity DESC 
	LIMIT 1
	";
	$statement =mysqli_query($connect,$query) or die(mysqli_error($connect));

	$result =mysqli_fetch_array($statement);
	
		return $result['last_activity'];
	
}

function fetch_user_chat_history($from_user_id, $to_user_id)
{
	global $connect;
	$query = "
	SELECT * FROM chat_message 
	WHERE (from_user_id = '".$from_user_id."' 
	AND to_user_id = '".$to_user_id."') 
	OR (from_user_id = '".$to_user_id."') 
	ORDER BY timestamp ASC
	";
	$statement = mysqli_query($connect,$query);
	
	//$result = mysqli_fetch_array($statement);
	$output = '<ul class="list-unstyled">';
	while($row = mysqli_fetch_array($statement)){
		$user_name = '';
		$dynamic_background = '';
		$chat_message = '';
		if($row["from_user_id"] == $from_user_id)
		{
			$chat_message = $row['chat_message'];
				$user_name = '<b class="text-success">You</b>';
			

			$dynamic_background = 'background-color:#ffe6e6;';
		}
		else
		{
		
				$chat_message = $row["chat_message"];
			
			$user_name = '<b class="text-danger">'.get_user_name($row['from_user_id']).'</b>';
			$dynamic_background = 'background-color:#ffffe6;';
		}
		$output .= '
		<li style="border-bottom:1px dotted #ccc;padding-top:8px; padding-left:8px; padding-right:8px;'.$dynamic_background.'">
			<p>'.$user_name.' - '.$chat_message.'
				<div align="right">
					- <small><em>'.$row['timestamp'].'</em></small>
				</div>
			</p>
		</li>
		';
	}
	$output .= '</ul>';
	$query = "
	UPDATE chat_message 
	SET status = '0' 
	WHERE from_user_id = '".$to_user_id."'
	AND status = '1'";
	
	$statement = mysqli_query($connect,$query);
	$query2 = "UPDATE chat_message SET assigned_to =".$from_user_id." WHERE from_user_id =".$to_user_id;
	$statement2 = mysqli_query($connect,$query2);
	return $output;
}

function get_user_name($user_id)
{
	global $connect;
	$query = "SELECT username FROM login WHERE user_id = '$user_id'";
	$statement = mysqli_query($connect,$query);
	
	$result = mysqli_fetch_array($statement);
	
		return $result['username'];
	
}

function count_unseen_message($from_user_id)
{
	global $connect;
	$query = "
	SELECT * FROM chat_message 
	WHERE from_user_id = '$from_user_id' 
	
	AND status = '1'
	";
	$statement = mysqli_query($connect,$query);
	
	$count = mysqli_num_rows($statement);
	$output = '';
	if($count > 0)
	{
		$output = '<span class="label label-success">'.$count.'</span>';
	}
	return $output;
}


