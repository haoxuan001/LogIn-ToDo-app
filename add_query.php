<?php
require_once("functions/init.php");
	if(ISSET($_POST['add'])){
		if($_POST['task'] != ""){
			$task = $_POST['task'];
			// TODO. current login user id
          	$userId = 0;
			query("INSERT INTO task(task, status, user_id) VALUES('$task', 'pending', '$userId')");
			redirect('admin.php');
		}
	}
?>