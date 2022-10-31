<?php
session_start();

if(!isset($_SESSION['loggedin']))
{
	header('Location: index.php');
	exit();
}


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
	
	<?php

		echo"<div class='hero'><div class='formbox2'>Witaj ".$_SESSION['user'].'!'.
								"<div class='socialicons'>
				<div id='fb'><a href='https://www.facebook.com/iiilotarnow/' target='blank'><img src='fb.png'></a></div>
				<div id='go'><a href='http://www.iii-lo.tarnow.pl/' target='blank'><img src='iii.png'></a></div>
				<div id='tw'><a href='https://www.facebook.com/iiilotarnow/' target='blank'><img src='tw.png'></a></div>
				<div id='log'><a href='logout.php'><img src='1.png'></a></div>
			</div><fieldset><h6>Proszę wybrać kandydata!</h6><form action='thanks.php' method='post' class='botijo'>
			<label><div id='nup0'><input type='radio' value='1' name='kandydat'></input>Kandydat#</div></label>
			<label><div id='nup1'><input type='radio' value='2' name='kandydat'></input>Kandydat#</div></label>
			<label><div id='nup2'><input type='radio' value='3' name='kandydat'></input>Kandydat#</div></label>
			<button type='submit' id='nup0'>Zagłosuj</button>
			</form></fieldset>
			
			
			
			</div>";
		
			
	
		
		
			

	?>
			<script>

				var n0 = document.getElementById("nup0");
				var n1 = document.getElementById("nup1");
				var n2 = document.getElementById("nup2");
				
				if(n0).checked
				{
					alert("lol0");
				}else if(n1).checked
				{
					alert("lol1");
				}

				
			</script>			

	
</body>
</html>

		