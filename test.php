<?php 
add_shortcode('Facebook-Plugin-Post-1', 'FacebookPluginPost_testimonial_form');
//require_once __DIR__ . '/lib/Facebook/autoload.php';
add_action( 'init', 'process_response' );

function process_response() {
     if( isset( $_POST['fb'] ) )
      {
	//1.Stat Session
	 session_start();

	//check if users wants to logout
	 if(isset($_REQUEST['logout'])){
	 	unset($_SESSION['fb_token']);
	 }
	
	//2.Use app id,secret and redirect url 
	$app_id = '1192023424214476';
	$app_secret = '643d15709986e9e1d46499a53073e9cc';
	$redirect_url='http://localhost/projects/wp-content/plugins/FacebookPluginPost/FacebookPluginPost';

	//3.Initialize application, create helper object and get fb sess
	 FacebookSession::setDefaultApplication($app_id,$app_secret);
	 $helper = new FacebookRedirectLoginHelper($redirect_url);
	 $sess = $helper->getSessionFromRedirect();

	 //check if facebook session exists
	if(isset($_SESSION['fb_token'])){
		$sess = new FacebookSession($_SESSION['fb_token']);
		try{
			$sess->Validate($app_id,$app_secret);
		}catch(FacebookAuthorizationException $e){
			print_r($e);
		}
	}

	$loggedin = false;
	//get email as well with user permission
	$login_url = $helper->getLoginUrl(array('scope' => 'email'));
	//logout
	$logout = 'http://localhost/projects/facebook-plugin/';

	//4. if fb sess exists echo name 
	 	if(isset($sess)){
	 		//store the token in the php session
	 		$_SESSION['fb_token']=$sess->getToken();
	 		//create request object,execute and capture response
	 		$request = new FacebookRequest($sess,'GET','/me?fields=id,name,email');
			// from response get graph object
			$response = $request->execute();
			$graph = $response->getGraphObject(GraphUser::classname());
			// use graph object methods to get user details
			$id = $graph->getId();
			$name= $graph->getName();
			$email = $graph->getProperty('email');
			$image = 'https://graph.facebook.com/'.$id.'/picture?width=300';
			$loggedin  = true;
			//$headers = 'From: Uttam sharma <uttams@bsf.io>';
			//mail( $email, 'Successfully Login', 'Thank you for login.', $headers );
	}
?>
		<?php if(!$loggedin){ ?>
	    <!--<h1>Login!</h1>
	    <p>Welcome, Please login to your facebook account.</p>
	    <a href="<?php //echo $login_url; ?>"><button class="btn btn-primary">Login with facebook</button></a>-->
    	<?php }else { ?>
    	<img src="<?php echo $image; ?>" alt="<?php echo $name; ?>" class="img-thumbnail">
	    	<h1>hi <b><?php echo $name; ?></b></h1>
	    	<p>you have successfully logged in via facebook :) and your email is 
	    		<code><?php echo $email ; ?></code></p><br>
	    		<form action="" method="POST">
		<label>Your Message</label>
		<textarea name="content"></textarea>
		<input class="btn btn-primary" type="submit" name="publish" value="Post on Facebook">
	    		</form><br />
	    	<a href="<?php echo $logout; ?>">
	    		<button class="btn btn-primary">Logout</button>
	    	</a>	
	    	<?php
     }
 }