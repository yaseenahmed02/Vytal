<?php
session_start();

// Unset all session variables
$_SESSION = array();

// Delete the session cookie
if (isset($_COOKIE[session_name()])) {
	setcookie(session_name(), '', time() - 86400, '/');
}

// Destroy the session
session_destroy();

// Redirect the user to the index.html page
header('Location: index.html');


exit(); // Make sure to exit after redirection
