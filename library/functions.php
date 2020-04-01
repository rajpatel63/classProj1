<?php

function checkAccount($zone)
{
    // zone is either 'user' or 'admin', anything else is considered 
    // 'none' or publicly accessible

    /// we start the session in checkAccount to make sure it's called.
    session_start();
}

function getDBConnection()
{
    $user = "rpatel63";
    $conn = mysqli_connect("localhost",$user,$user,$user);

    // Check connection and shutdown if broken
    if (mysqli_connect_errno()) {
	die("<b>Failed to connect to MySQL: " . mysqli_connect_error() . "</b>");
    }

    return $conn;
}

?>

