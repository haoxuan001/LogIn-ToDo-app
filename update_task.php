<?php
require_once("functions/init.php");
	
	if($_GET['task_id'] && ['task_id']!= ""){
		$task_id = $_GET['task_id'];
		
		query("UPDATE `task` SET `status` = 'Done' WHERE `task_id` = $task_id") or die(mysqli_errno());
		redirect("admin.php");
	}
?>