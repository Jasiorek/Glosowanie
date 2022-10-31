<?php

	session_start();
	
	if((!isset($_POST['login'])) || (!isset($_POST['password'])))
	{
		header('Location: index.php');
		exit();
	}

	require_once "base.php";
    mysqli_report(MYSQLI_REPORT_STRICT);
	
	try
	{
	
	$connection = new mysqli($host, $db_user, $db_password, $db_name);
	
	if($connection->connect_errno!=0)
	{
		throw new Exception(mysqli_connect_errno());
	}
	else
	{
	   $login = $_POST['login'];
	   $password = $_POST['password'];
	   
	   $login = htmlentities($login, ENT_QUOTES, "UTF-8");
	   
	   
	   if($result = $connection->query(sprintf("SELECT * FROM uzytkownicy WHERE user='%s'", 
	   mysqli_real_escape_string($connection,$login))))
	   {
		   $numberofusers = $result->num_rows;
		   if($numberofusers>0)
			 {
			    $row = $result->fetch_assoc();
						 
						 if(password_verify($password, $row['pass']))
						 {
					   $_SESSION['loggedin'] = true;
					   
					   $_SESSION['id'] = $row['id'];
					   $_SESSION['user'] = $row['user'];
					   $_SESSION['drewno'] = $row['drewno'];
					   $_SESSION['kamien'] = $row['kamien'];
					   $_SESSION['zboze'] = $row['zboze'];
					   $_SESSION['email'] = $row['email'];
					   $_SESSION['dnipremium'] = $row['dnipremium'];
								
					   unset($_SESSION['error']);
					   $result->free_result();
					   header('Location: vote.php');
		    	}
				else {
			   $_SESSION['error'] = '<span id="dod" style="color:red;text-align:center;position:relative;left:100px;bottom:-170px; -webkit-user-select: none;  
  -moz-user-select: none;    
  -ms-user-select: none;      
  user-select: none;">Nieprawidłowy login lub hasło!</span>';
			   header('Location: index.php');
			  
		   }
				
				

		   } else {
			   $_SESSION['error'] = '<span id="dod" style="color:red; text-align:center;position:relative;left:100px;bottom:-170px; -webkit-user-select: none;  
  -moz-user-select: none;    
  -ms-user-select: none;      
  user-select: none;">Nieprawidłowy login lub hasło!</span>';
			   header('Location: index.php');
			  
		   }
		   
	   }
	   else
	   {
		   throw new Exception($connection->error);
	   }
	  
	   $connection->close();
	}
}
catch(Exception $e)
{
		{
		echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o wizytę w innym terminie!</span>';
		//echo '<br />Informacja developerska: '.$e;
	}
}
	

	




?>