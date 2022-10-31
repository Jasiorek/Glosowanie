<?php

	session_start();
	
	if(isset($_POST['email']))
	{
		$all_good=true;
		
		$nick = $_POST['nick'];
		
		if((strlen($nick)<3) || (strlen($nick)>20))
		{
			$all_good=false;
			$_SESSION['e_nick']="Nick musi posiadac od 3 do 20 znakow!";
			
		}
		
		if(ctype_alnum($nick)==false)
		{
			$all_good=false;
			$_SESSION['e_nick']="Nick może składać się tylko z liter i cyfr (bez polskich znaków)";
		}
		if(strlen($nick)==0) 
		{
			$all_good=false;
			$_SESSION['e_nick']="To pole nie może być puste!";
			
		}
		
		
		
		
		$email = $_POST['email'];
		$emails = filter_var($email, FILTER_SANITIZE_EMAIL);
		
		if((filter_var($emails, FILTER_VALIDATE_EMAIL)==false) || ($emails!=$email))
		{
			$all_good=false;
			$_SESSION['e_email']="Podaj poprawny adres e-mail!";
		}
				if(strlen($email)==0) 
		{
			$all_good=false;
			$_SESSION['e_email']="To pole nie może być puste!";
			
		}
		
		$pass1 = $_POST['pass1'];
		$pass2 = $_POST['pass2'];
		
				if((strlen($pass1)<8) || (strlen($pass1)>20))  
		{
			$all_good=false;
			$_SESSION['e_password']="Hasło musi posiadać od 8 do 20 znaków!";
			
		}
		
				if ($pass1!=$pass2)
				{
				$all_good=false;
				$_SESSION['e_password']="Hasła nie są identyczne!";
				}
				
		
		if(strlen($pass1)==0) 
		{
			$all_good=false;
			$_SESSION['e_password']="To pole nie może być puste!";
			
		}
		
		$pass_hash = password_hash($pass1, PASSWORD_DEFAULT);
		
		if(!isset($_POST['terms']))
		
		{
			$all_good=false;
			$_SESSION['e_terms']="Potwierdź akceptację regulaminu!";
			
		}
		
		$key = "6LerOewUAAAAAGq4kDIIXzRSQHPgBRE8MXw1x6l5";
		
		$check = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$key.'&response='.$_POST['g-recaptcha-response']);
		
		$response = json_decode($check);

		if($response->success==false)
		{
			$all_good=false;
			$_SESSION['e_bot']="Potwierdź, że nie jesteś botem!";
			
		}
		
		$_SESSION['fr_nick'] = $nick;
		$_SESSION['fr_email'] = $email;
		$_SESSION['fr_pass1'] = $pass1;
		$_SESSION['fr_pass2'] = $pass2;
		if(isset($_POST['terms'])) $_SESSION['fr_terms'] = true;
		
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
				$result = $connection->query("SELECT id FROM uzytkownicy WHERE email='$email'");
				
				if(!$result) throw new Exception($connection->error);
				
				$howmuchmails = $result->num_rows;
				if($howmuchmails>0)
				{
					$all_good=false;
					$_SESSION['e_email']="Istnieje już konto z takim e-mailem!";
				}
				
				$result = $connection->query("SELECT id FROM uzytkownicy WHERE user='$nick'");
				
				if(!$result) throw new Exception($connection->error);
				
				$howmuchnicks = $result->num_rows;
				if($howmuchnicks>0)
				{
					$all_good=false;
					$_SESSION['e_nick']="Istnieje już konto z takim nickiem!";
				}
				
				if($all_good==true)
		        {			
					if($connection->query("INSERT INTO uzytkownicy VALUES(NULL, '$nick','$pass_hash','$email',100,100,100,now() + INTERVAL 14 DAY)"))
					{
						$_SESSION['welldone']=true;
						header('Location: welcome.php');
					}
					else
					{
						throw new Exception($connection->error);
					}
		        }
				
				
				$connection->close();
			}
		}
		catch(Exception $e)
		{
			echo '<span style="color:red;">Błąd serwera! Proszę spróbować później!</span>';
			//echo '<br />Info: '.$e;
		}

		
	}
	


?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
	<title>Załóż darmowe konto</title>
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	
	<style> 
	
	.error
	{
		color: red;
		margin-top: 10px;
		margin-bottom: 10px;
	}
	</style>
	
</head>
<body>
	
	<form method="post">
	
		Nicnkname: <br /> <input type="text" value="<?php 
		if(isset($_SESSION['fr_nick']))
		{
			echo $_SESSION['fr_nick'];
			unset($_SESSION['fr_nick']);
		}
		?>" name="nick" /><br />
		<?php
			if(isset($_SESSION['e_nick']))
			{
				echo'<div class="error">'.$_SESSION['e_nick'].'</div>';
				unset($_SESSION['e_nick']);
			}
		
		
		?>
		E-mail: <br /> <input type="text"  value="<?php 
		if(isset($_SESSION['fr_email']))
		{
			echo $_SESSION['fr_email'];
			unset($_SESSION['fr_email']);
		}
		?>" name="email" /><br />
		<?php 
			if (isset($_SESSION['e_email']))
			{
				echo'<div class="error">'.$_SESSION['e_email'].'</div>';
				unset($_SESSION['e_email']);
			}
		
		
		?>
		Hasło: <br /> <input type="password"  value="<?php 
		if(isset($_SESSION['fr_pass1']))
		{
			echo $_SESSION['fr_pass1'];
			unset($_SESSION['fr_pass1']);
		}
		?>" name="pass1" /><br />
		<?php
			if (isset($_SESSION['e_password']))
			{
				echo'<div class="error">'.$_SESSION['e_password'].'</div>';
				unset($_SESSION['e_password']);
			}
			
		
		
		?>
		Powtórz Hasło: <br /> <input type="password" value="<?php 
		if(isset($_SESSION['fr_pass2']))
		{
			echo $_SESSION['fr_pass2'];
			unset($_SESSION['fr_pass2']);
		}
		?>" name="pass2" /><br />
		
	
		
	
	
      <br/>
      <input type="submit" value="Zarejestruj się">
	</form>
	
</body>
</html>