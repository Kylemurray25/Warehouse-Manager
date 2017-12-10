<?php 
	session_start(); 

	if (!isset($_SESSION['username'])) {
		$_SESSION['msg'] = "You must log in first";
		header('location: login.php');
	}

	if (isset($_GET['logout'])) {
		session_destroy();
		unset($_SESSION['username']);
		header("location: login.php");
	}

     if ($_SESSION['message'] == "sales") {
        header("location: salesEmployee.php");
     }

    if ($_SESSION['message'] == "manager") {
        header("location: manager.php");
    }

    if ($_SESSION['message'] == "warehouse") {
        header("location: warehouseEmployee.php");
    }
    

?>