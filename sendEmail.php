<?php
	// Import PHPMailer classes into the global namespace
	// These must be at the top of your script, not inside a function
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

	require_once "PHPMailer/PHPMailer.php";
	require_once "PHPMailer/SMTP.php";
	require_once "PHPMailer/Exception.php";


	// declare(strict_types=1);
	require __DIR__ . '/dotenv/vendor/autoload.php';

	ini_set('display_errors', '1');
	ini_set('display_startup_errors', '1');
	error_reporting(E_ALL);

	$whoops = new \Whoops\Run;
	$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
	$whoops->register();


	$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ .'/dotenv/vendor');
	$dotenv->load();

	if(isset($_POST['email'])) {

		
		
		date_default_timezone_set("Europe/London");
		$date_time = date("Y/m/d h:i:sa");

		$first_name = $_POST['first_name']; // required
	    $last_name = $_POST['last_name']; // required
	    $from_name = $first_name." ".$last_name;
	    $email_from = $_POST['email']; // required
	    $replyEmail = $email_from;
	    $address1 = $_POST['address1']; // required
	    $address2 = $_POST['address2']; // required
	    $subject = "Moment pre-order_#".$date_time."_".$from_name;
	    $body = "We've got a pre-order from <b>".$from_name.
	    		"</b><br> Reply to the email address: ".$email_from."<br>".
	    		"the desired shipping address should be: <br> address line 1: <br><b>". $address1 . "</b><br>"
	    		."address line 2: <br><b>". $address2 . "</b><br>";

		$mail = new PHPMailer();
		
		echo "From: ".$from_name."<br>";

		//SMTP settings
		$mail -> isSMTP();
		$mail -> Host = "smtp.gmail.com";
		$mail -> SMTPAuth = true;
		$mail -> Username = "s.lin@edu.ciid.dk";
		$mail -> Password = getenv('M_PASS');
		$mail -> Port = 465;
		$mail -> SMTPSecure = "ssl";

		

		//Email settings
		$mail -> isHTML(true);
		$mail -> From = $email_from;
		$mail -> FromName = $first_name." ".$last_name;
		// $mail -> setFrom($email_from, $first_name); //change
		$mail -> addAddress("shu@cookpad.com", "Moment"); //Recipient name is optional //hello@getmoment.today
		// $mail -> addAddress("shu@cookpad.com"); //send to this email
		$mail -> addReplyTo($replyEmail, $from_name);
		$mail -> WordWrap = 50; 
		$mail -> Subject = $subject;
		$mail -> Body = $body;

		console_log($mail);

		if($mail -> send()){
			
			console_log("Email is sent!");
		}else{
			console_log("Something is wrong here:");
			console_log($mail->ErrorInfo);
		}

	}


	function console_log( $data ){
	  echo '<script>';
	  echo 'console.log('. json_encode( $data ) .')';
	  echo '</script>';
	}
?>

<!-- Redirect back to a success page -->
 <?php 

 header("Location: https://getmoment.today/success.html");
 
 ?>

