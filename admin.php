<?php include("includes/header.php") ?>



  <?php include("includes/navi.php") ?>



	<div class="jumbotron">
		<h1 class="text-center"><?php 

		if(logged_in()){

            echo"Welocme you are logged in";



		} else{

			redirect("index.php");
		}

		?></h1>


	</div>
		<body>
		<div class="col-md-3"></div>
	<div class="col-md-6 well">
		<h3 class="text-primary">PHP - Simple To Do List App</h3>
		<hr style="border-top:1px dotted #ccc;"/>
		<div class="col-md-2"></div>
		<div class="col-md-8">
			<center>
				<form method="POST" class="form-inline" action="add_query.php">
					<input type="text" class="form-control" name="task"/>
					<button class="btn btn-primary form-control" name="add">Add Task</button>
				</form>
			</center>
		</div>
		<br /><br /><br />
		<table class="table">
			<thead>
				<tr>
					<th>#</th>
					<th>Task</th>
					<th>Status</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php
              		
					$tasks = query("SELECT * FROM `task` ORDER BY `task_id` ASC");
					$count = 1;
					foreach($tasks as $task){
				?>
				<tr>
					<td><?php echo $count++?></td>
					<td><?php echo $task['task']?></td>
					<td><?php echo $task['status']?></td>
					<td colspan="2">
						<center>
							<?php
								if($task['status'] != "Done"){
									echo 
									'<a href="update_task.php?task_id='.$task['task_id'].'" class="btn btn-success"><span class="glyphicon glyphicon-check"></span></a> |';
								}
							?>
							 <a href="delete_query.php?task_id=<?php echo $task['task_id']?>" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span></a>
						</center>
					</td>
				</tr>
				<?php
					}
				?>
			</tbody>
		</table>
	</div>
	</body>
<?php include("includes/footer.php") ?>