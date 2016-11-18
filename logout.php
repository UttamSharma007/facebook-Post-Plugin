<?php
session_start();
session_destroy(); 
update_option('current_session', NULL); 
unset($sess); unset($_SESSION['fb_token']);
header("location:");
?>
