<?php



/*************** helper functions ***************/

/******** Clear characters function *********/

function clean($string){

	return htmlentities($string);

	}



/******** Redircet function *********/


function redirect($location){
   header("Location: ".$location);
   exit();
 
}

/******** SetMessage function *********/
function set_message($message) {


	if(!empty($message)){


		$_SESSION['message'] = $message;

	}else {

		$message = "";

	}


}





/******** DisplayMessage function *********/


function display_message(){


	if(isset($_SESSION['message'])) {


		echo $_SESSION['message'];

		unset($_SESSION['message']);

	}



}




/******** Encryption function *********/

function token_generator(){

	$token=$_SESSION['token']=md5(uniqid(mt_rand(),true));

		return $token;


}

/******** Validation Errors function *********/


function validation_errors($error_message) {

$error_message = <<<DELIMITER

<div class="alert alert-danger alert-dismissible" role="alert">
  	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  	<strong>Warning!</strong> $error_message
 </div>
DELIMITER;

return $error_message;
		




}



/****************Email exists functions ********************/

function email_exists($email) {

	$sql = "SELECT id FROM users WHERE email = '$email'";

	$result = query($sql);

	if(row_count($result) == 1 ) {

		return true;

	} else {


		return false;

	}



}


/****************User_name exists functions ********************/

function username_exists($user_name) {

	$sql = "SELECT id FROM users WHERE user_name = '$user_name'";

	$result = query($sql);

	if(row_count($result) == 1 ) {

		return true;

	} else {


		return false;

	}



}

/***************** Send Email Function *******************/
function send_email($email, $subject, $message, $header){


return mail($email, $subject, $message, $header);




}




/****************Validation register functions ********************/



function validate_user_registration(){

	$min = 3;

	$max = 12;

	$errors = [];

	


	if($_SERVER['REQUEST_METHOD'] == "POST") {


		 $first_name =  clean( $_POST['first_name']);

		  $last_name =  clean( $_POST['last_name']);

		   $user_name =  clean( $_POST['user_name']);

		    $email =  clean( $_POST['email']);

		     $password =  clean( $_POST['password']);

		      $confirm_password =  clean( $_POST['confirm_password']);
		

		if(strlen($first_name)<$min){

			$errors[] ="The First name  cannot be less than {$min} characters ";
		}

		if(strlen($first_name)>$max){

			$errors[] ="The First name  cannot be no more than {$max} characters ";
		}


		if(strlen($last_name)<$min){

			$errors[] ="The Last name cannot be less than {$min} characters ";
		}


		if(strlen($last_name)>$max){

			$errors[] ="The Last name cannot be more than {$max} characters ";
		}

	
		if(username_exists($user_name)){

			$errors[] = "Sorry, that user_name is already  taken";

		}


		if(strlen($user_name)<$min){

			$errors[]="The User Name cannot be  less than {$min} characters";

		}

		if(strlen($user_name)>$max){

			$errors[] ="The User name cannot be more than {$max} characters ";
		}

	

		if(email_exists($email)){

			$errors[] = "Sorry that email already is registered";

		}

		if(strlen($email)<$min){

			$errors[] ="The Email cannot be less than {$min} characters ";
		}



		if($password !== $confirm_password){

			$errors[] ="The Password is not match ";
		}

		
		if(!empty($errors)) {

			foreach ($errors as $error) {

			echo validation_errors($error);

			
			}

		}else{

			if(register_user($first_name, $last_name,$user_name,$email,$password)){

				set_message("<p class='bg-success text-center'>Please check your email or spam folder for activation link</p>");

				redirect("index.php");

				
			}

		}

	} // post request 



} // function 

/****************Register functions ********************/


function register_user($first_name, $last_name,$user_name,$email,$password){

	$first_name = escape($first_name);
		$last_name= escape($last_name);
			$user_name = escape($user_name);
				$email = escape($email);
					$password = escape($password);


	if(email_exists($email)){

		return false;

	}else if(username_exists($user_name)){

			return false;
	}else{

			$password = md5($password);  
				$validation_code = md5($user_name) . microtime();

				$sql="INSERT INTO users(first_name,last_name,user_name,email,password,validation_code,active) VALUES
				 ('$first_name','$last_name','$user_name','$email','$password','$validation_code',0)";

				 $result= query($sql);

				 confirm($result);

				 $subject= "Active Account";

				 $msg= "Please click the link below to active your Account

				 http://localhost/login/active.php?email=$email&code=$validation_code";

				 $header="From noreply@mywebsite.com";

				 send_email($email, $subject, $msg, $header);

					return true;				

	}

}//register

/****************Activation functions ********************/
	function activate_user(){


	if($_SERVER['REQUEST_METHOD'] == "GET") {

			if(isset($_GET['email'])){

				$email=clean($_GET['email']);

				$validation_code=clean($_GET['code']);

				$sql= "SELECT id FROM users WHERE email='".escape($_GET['email'])."' AND validation_code='" .escape($_GET['code']). "'   ";

				$result= query($sql);

				confirm($result);

				if(row_count($result)==1){
				
	$sql2 = "UPDATE users SET active = '1', validation_code ='0' WHERE email = '".escape($email)."' AND validation_code = '".escape($validation_code)."' ";


			$result2 = query($sql2);
			confirm($result2);

			set_message("<p class='bg-success'>Your account has been activated please login</p>");

			redirect("login.php");



				}else {

			set_message("<p class='bg-danger'>Sorry Your account could not be activated </p>");

			redirect("login.php");


			}

			}


		}




	}//function



/****************Validate user login functions ********************/

function validate_user_login(){

		$min = 3;

		$max = 12;

		$errors = [];


		if ($_SERVER['REQUEST_METHOD'] == "POST"){

			
		    $email =  clean( $_POST['email']);

		    $password =  clean( $_POST['password']);

		    $remember = isset($_POST['remember']);


		    if(empty($email)){

			$errors[]="Email field can nont be empty";

		    }

		      if(empty($password)){

			$errors[]="Password field can nont be empty";


			}	


		
		if(!empty($errors)) {

				foreach ($errors as $error) {

				echo validation_errors($error);

				
				}


			} else {


				if(login_user($email, $password,$remember)) {
					redirect("admin.php");


				} else {


				echo validation_errors("Your password are not correct");		



				}



			}


		}//if

	}//function



/**************** user login functions ********************/

	function login_user($email,$password,$remember){

			if (session_status() == PHP_SESSION_NONE) {

	        session_start();
	    }
	 

		$sql = "SELECT password, id FROM users WHERE email = '".escape($email)."' ";

		$result=query($sql);

		confirm($result);
		
		if(row_count($result)==1){


			$row=fetch_array($result);

			 	$db_password = $row['password'];

			 	if(md5($password)== $db_password){


			 		if($remember == "on"){

			 			setcookie('email', $email, time() + 86400);//86400sec == 1 day
			 		}
		
				$_SESSION['email'] = $email;



				return true;

			} else {


				return false;
			}

			return true;

		} else {

			return false;

		}

	}//function


/****************loggedin functions ********************/
function logged_in(){


	if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

	if(isset($_SESSION['email'])|| isset($_COOKIE['email'])){


		return true;

	} else {


		return false;
	}




}	// functions



/****************Recover password functions ********************/


	

function recover_password() {


	if($_SERVER['REQUEST_METHOD'] == "POST") {

		if(isset($_SESSION['token']) && $_POST['token'] === $_SESSION['token']) {

			$email = clean($_POST['email']);


			if(email_exists($email)) {


			$validation_code = md5($email + microtime());


			setcookie('temp_access_code', $validation_code, time()+ 900);


			$sql = "UPDATE users SET validation_code = '".escape($validation_code)."' WHERE email = '".escape($email)."'";
			$result = query($sql);



			$subject = "Please reset your password";
			$message =  " Here is your password rest code {$validation_code}

			Click here to reset your password http://edwincodecollege.com/login_app/code.php?email=$email&code=$validation_code

			";

			$headers = "From: noreply@hyg.com";





			send_email($email, $subject, $message, $headers);




			set_message("<p class='bg-success text-center'>Please check your email or spam folder for a password reset code</p>");

			redirect("index.php");


			} else {


				echo validation_errors("This emails does not exist");


			}



		} else {


			redirect("index.php");

		}




		// token checks


			if(isset($_POST['cancel_submit'])) {

						redirect("login.php");


					}//cancel



	} // post request





} // functions




/****************Code validation functions ********************/



function validate_code () {


	if(isset($_COOKIE['temp_access_code'])) {

			if(!isset($_GET['email']) && !isset($_GET['code'])) {

				redirect("index.php");


			} else if (empty($_GET['email']) || empty($_GET['code'])) {

				redirect("index.php");


			} else {



				if(isset($_POST['code'])) {

					$email = clean($_GET['email']);

					$validation_code = clean($_POST['code']);

					$sql = "SELECT id FROM users WHERE validation_code = '".escape($validation_code)."' AND email = '".escape($email)."'";
					$result = query($sql);

					if(row_count($result) == 1) {

						setcookie('temp_access_code', $validation_code, time()+ 300);

						redirect("reset.php?email=$email&code=$validation_code");


					} else {



						echo validation_errors("Sorry wrong validation code");

					}
		




				}



			}








	} else {

		set_message("<p class='bg-danger text-center'>Sorry your validation cookie was expired</p>");

		redirect("recover.php");


	}







}//function





/****************password reset function ********************/


function password_reset() {

	if(isset($_COOKIE['temp_access_code'])) {


		if(isset($_GET['email']) && isset($_GET['code'])) {



			if(isset($_SESSION['token']) && isset($_POST['token'])) {


				if($_POST['token'] === $_SESSION['token']) {


					if($_POST['password']=== $_POST['confirm_password'])  { 


						$updated_password = md5($_POST['password']);


						$sql = "UPDATE users SET password = '".escape($updated_password)."', validation_code = 0 WHERE email = '".escape($_GET['email'])."'";
						query($sql);



						set_message("<p class='bg-success text-center'>You passwords has been updated, please login</p>");

						redirect("login.php");
						

						} else {

							echo validation_errors("Password fields don't match");


						}


				  }

	

			} 



		} 


	}else {


		set_message("<p class='bg-danger text-center'>Sorry your time has expired</p>");

		redirect("recover.php");




		}


}
?>