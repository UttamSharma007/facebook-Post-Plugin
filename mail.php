<?php
if(isset($_POST['fb_submit']))
			{
			echo "gfdhj";
			$postRequest = new FacebookRequest($sess, 'POST', '/152293635238142/feed', array('message' => 'My first post using my facebook app.'));
			$postResponse = $postRequest->execute();
			$posting = $postResponse->getGraphObject();
			echo $posting->getProperty('id');
			}
?>