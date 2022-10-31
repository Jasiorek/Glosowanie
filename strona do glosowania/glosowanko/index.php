<?php

	session_start();
	
	if((isset($_SESSION['loggedin'])) && ($_SESSION['loggedin']==true))
	{
		header('Location: vote.php');
		exit();
	}
	
		if(isset($_POST['email']))
	{
		$all_good=true;
		
		$nick = $_POST['nick'];
		
		if((strlen($nick)<3) || (strlen($nick)>20))
		{
			$all_good=false;
			$_SESSION['e_nick']="Login musi posiadac od 3 do 20 znakow!";
			
		}
		
		if(ctype_alnum($nick)==false)
		{
			$all_good=false;
			$_SESSION['e_nick']="Login może składać się tylko z liter i cyfr bez polskich znaków";
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
		
		$key = "6LdbNAEVAAAAAEdknWrl985PEfnVidzvl7Ej37p0";
		
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
					$_SESSION['e_nick']="Istnieje już konto z takim loginem!";
				}
				
				if($all_good==true)
		        {			 
					if($connection->query("INSERT INTO uzytkownicy VALUES(NULL, '$nick','$pass_hash','$email',0,0,0,now() + INTERVAL 14 DAY)"))
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
	<title>Formularz logowania</title>
	<link rel="stylesheet" href="ziki.css">
	<link href="https://fonts.googleapis.com/css?family=Ubuntu&display=swap&subset=latin-ext" rel="stylesheet">
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>

	
	<style> 

	.error
	{
		color: red;
		margin-top: 0;
		margin-bottom: 7px;
		font-size: 10px;
			  -webkit-user-select: none;  
  -moz-user-select: none;    
  -ms-user-select: none;      
  user-select: none;
	}
	.error2
	{
		color: red;
		margin-top: 7px;
		margin-bottom: 0;
		font-size: 10px;
			  -webkit-user-select: none;  
  -moz-user-select: none;    
  -ms-user-select: none;      
  user-select: none;
	}
	.error3
	{
		color: red;
		margin-top: 0;
		margin-bottom: 0;
		font-size: 10px;
			  -webkit-user-select: none;  
  -moz-user-select: none;    
  -ms-user-select: none;      
  user-select: none;
	}
	</style>
</head>
<body>
		<div class="hero">
			<div class="formbox">
				<div class="buttonbox">
					<div id="btn"></div>
					<button type="button" class="togglebtn" onclick="login()">Logowanie</button>
					<button type="button" class="togglebtn" onclick="register()">Rejestracja</button>
				</div>
							<div class="socialicons">
				<div id="fb"><a href="https://www.facebook.com/iiilotarnow/" target="blank"><img src="fb.png"></a></div>
				<div id="go"><a href="http://www.iii-lo.tarnow.pl/" target="blank"><img src="iii.png"></a></div>
				<div id="tw"><a href="https://www.facebook.com/iiilotarnow/" target="blank"><img src="tw.png"></a></div>
			
			</div>
				<div id="quote"><q><i  id="ADA"></i></q>- Adam Mickiewicz</div>
			<form action="login.php" method="post" id="login" class="inputgroup">
				<input type="text" class="inputfield" placeholder="Login"    name="login" onfocus="this.placeholder=''" onblur="this.placeholder='Login'" id="gobik">
				<input type="password" name="password" class="inputfield" placeholder="Hasło"   onfocus="this.placeholder=''" onblur="this.placeholder='Hasło'">
			<label class="czek"><input type="checkbox" class="chechbox"><span>Pamiętaj hasło</span></label>
				<button type="submit" class="submitbtn">Zaloguj się</button>
			</form>
			
			<form id="register" method="post" class="inputgroup">
				<input type="text" class="inputfield" placeholder="Login" onfocus="this.placeholder=''" id="gobik2"onblur="this.placeholder='Login'" value="<?php 
		if(isset($_SESSION['fr_nick']))
		{
			echo $_SESSION['fr_nick'];
			unset($_SESSION['fr_nick']);
		}
		?>" name="nick"> <?php
			if(isset($_SESSION['e_nick']))
			{
				echo'<div class="error">'.$_SESSION['e_nick'].'</div>';
				unset($_SESSION['e_nick']);
			}?>
				<input type="text" class="inputfield" placeholder="Email" onfocus="this.placeholder=''" id="gobik3" onblur="this.placeholder='Email'" value="<?php 
		if(isset($_SESSION['fr_email']))
		{
			echo $_SESSION['fr_email'];
			unset($_SESSION['fr_email']);
		}
		?>" name="email"> <?php 
			if (isset($_SESSION['e_email']))
			{
				echo'<div class="error">'.$_SESSION['e_email'].'</div>';
				unset($_SESSION['e_email']);
			}	?>
				<input type="password" class="inputfield" placeholder="Hasło" onfocus="this.placeholder=''" onblur="this.placeholder='Hasło'" value="<?php 
		if(isset($_SESSION['fr_pass1']))
		{
			echo $_SESSION['fr_pass1'];
			unset($_SESSION['fr_pass1']);
		}
		?>" name="pass1"><?php
			if (isset($_SESSION['e_password']))
			{
				echo'<div class="error2">'.$_SESSION['e_password'].'</div>';
				unset($_SESSION['e_password']);
			}	?> 
			
			<input type="password" id="gobik4" class="inputfield" placeholder="Powtórz hasło" onfocus="this.placeholder=''" onblur="this.placeholder='Powtórz hasło'" value="<?php 
		if(isset($_SESSION['fr_pass2']))
		{
			echo $_SESSION['fr_pass2'];
			unset($_SESSION['fr_pass2']);
		}
				?>" name="pass2" /><br />
				
				   <div class="g-recaptcha" data-sitekey="6LdbNAEVAAAAADY68B9MwhmBsQkSoUaavwX60SFb"></div>
	  				<?php
			if (isset($_SESSION['e_bot']))
			{
				echo'<div class="error2">'.$_SESSION['e_bot'].'</div>';
				unset($_SESSION['e_bot']);
			}
			
		
		
		?>
				
			
	
			
		<label class="czek">
			<input type="checkbox" class="chechbox"  name="terms"><span>Akceptuję regulamin</span></label> <?php
			if (isset($_SESSION['fr_terms']))
			{
		
				unset($_SESSION['fr_terms']);
			}
				?>
		
		
		<?php
			if (isset($_SESSION['e_terms']))
			{
				echo '<div class="error">'.$_SESSION['e_terms'].'</div>';
				unset($_SESSION['e_terms']);
			}
		?>	
				
				
				<button type="submit" class="submitbtn">Register</button>
				
				
				
			</form>
			
			<?php
	if(isset($_SESSION['error']))   echo $_SESSION['error'];
	
			?>
			
			</div>
			
			
			
			<script>
			
			var x = document.getElementById("login");
			var y = document.getElementById("register");
			var z = document.getElementById("btn");
			var q = document.getElementById("quote");
			var q2 = document.getElementById("quote").style.transitionDuration = "0.5s";
			var passwords = ["Bo kto nie był ni razu człowiekiem, temu człowiek nic nie pomoże.", "Bo serce nie jest sługa, nie zna, co to pany, I nie da się przemocą okuwać w kajdany.", "Tam sięgaj, gdzie wzrok nie sięga; Łam, czego rozum nie złamie" ,"Tak! zemsta, zemsta, zemsta na wroga Z Bogiem i choćby mimo Boga!", "I znowu sobie powtarzam pytanie Czy to jest przyjaźń? czy to jest kochanie?...","Niech słowo kocham jeszcze raz z ust twycg usłyszę, Niech je w sercu wyryję i w myśli zapiszę.","Zawsze przy mnie, lecz nie ze mną.","Kto miłości nie zna, ten żyje szczęśliwy i noc ma spokojną i dzień nietęskliwy.","Nazywam się Milijon – bo za miliony Kocham i cierpię katusze"];
			var password_random = Math.floor(Math.random()*passwords.length);

			if(password_random > passwords.length-1) password_random = 1;

			var password = passwords[password_random];

			var length = password.length;
			window.onload = quoter();
			var b = document.getElementById("dod");
			var b2 = document.getElementById("dod").style.transitionDuration = "0.5s";
			
			
				
			
			
			function quoter(){
				document.getElementById("ADA").innerHTML = password;
			}
			
		



			function register(){
				x.style.left = "-400px";
				y.style.left = "50px";
				z.style.left = "130px";
				q.style.left = "-400px";
				y.style.top = "170px";
				b.style.left = "-1000px";

			}
			function login(){
				x.style.left = "50px";
				y.style.left = "450px";
				z.style.left = "0";
				q.style.left = "0px";
				b.style.left = "100px";

			}
			
			</script>

		</div>
</body>
</html>


