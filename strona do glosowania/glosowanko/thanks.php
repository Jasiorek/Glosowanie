<?php

	session_start();
  
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
	<title>Dziękujemy</title>
	<link rel="stylesheet" href="bitto.css">
		<link href="https://fonts.googleapis.com/css?family=Ubuntu&display=swap&subset=latin-ext" rel="stylesheet">
</head>
<body>
	<div class="hero">
	<div class="formbox"><div class="nat">
	Dziękujemy za Twój głos! 
	
	
	<?php $kandydat = $_POST['kandydat']; 
	

		
	echo $kandydat;
	

	?>
</div>
	<a class="jas" href="logout.php">Wyloguj się!</a>
	</div>
	</div>
	</div>

<?php
	if(isset($_SESSION['error']))   echo $_SESSION['error'];
	
?>

</body>
</html>