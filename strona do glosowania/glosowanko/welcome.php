<?php

	session_start();
	
	if(!isset($_SESSION['welldone']))
	{
		header('Location: index.php');
		exit();
	}
	else
	{
		unset($_SESSION['welldone']);
	}
	
	if(isset($_SESSION['fr_nick']))unset($_SESSION['fr_nick']);
	if(isset($_SESSION['fr_email']))unset($_SESSION['fr_email']);
	if(isset($_SESSION['fr_pass1']))unset($_SESSION['fr_pass1']);
	if(isset($_SESSION['fr_pass2']))unset($_SESSION['fr_pass2']);
	if(isset($_SESSION['fr_terms']))unset($_SESSION['fr_terms']);
	
	
	
	if(isset($_SESSION['e_nick']))unset($_SESSION['e_nick']);
	if(isset($_SESSION['e_email']))unset($_SESSION['e_email']);
	if(isset($_SESSION['e_pass1']))unset($_SESSION['e_pass1']);
	if(isset($_SESSION['e_pass2']))unset($_SESSION['e_pass2']);
	if(isset($_SESSION['e_bot']))unset($_SESSION['e_bot']);
	
	
	

?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
	<title>Formularz logowania</title>
	<link rel="stylesheet" href="bitto.css">
		<link href="https://fonts.googleapis.com/css?family=Ubuntu&display=swap&subset=latin-ext" rel="stylesheet">
</head>
<body>
	<div class="hero">
	<div class="formbox"><div class="nat">
	Dziękujemy za rejestrację w serwisie! Możesz już zalogować się na swoje konto!
</div>
	<a class="jas" href="index.php">Zaloguj się na swoje konto!</a>
	</div>
	</div>
	</div>

<?php
	if(isset($_SESSION['error']))   echo $_SESSION['error'];
	
?>

</body>
</html>