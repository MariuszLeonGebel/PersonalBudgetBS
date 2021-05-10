<?php
  session_start();

  if(isset($_POST['kwota']))
  {
  	$wszystko_OK = true;
  	$kwota = $_POST['kwota'];
  	$data = $_POST['data'];
  	$kategoria = $_POST['kategoria'];
  	$platnosc = $_POST['platnosc'];
  	$opis = $_POST['opis'];

  	$kwota = str_replace(",", ".", $kwota);

  	if(is_numeric($kwota)==false || $kwota<0)
  	{
  		$wszystko_OK=false;
  		$_SESSION['e_kwota']="W polu \"Kwota\" można wpisywać tylko wartości liczbowe większe od 0";
  	}

	if ($data == "")
	{
  		$wszystko_OK=false;
		$_SESSION['e_data']="Podana data była nieprawidłowa! Ustawiono datę dzisiejszą!" ;
	}
  	
  	require_once "connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);
    
    try 
    {
      $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
      if ($polaczenie->connect_errno!=0)
      {
        throw new Exception(mysqli_connect_errno());
      }
      else
      {
        if ($wszystko_OK==true)
        {
         $userId = $_SESSION['id'];  
          if ($polaczenie->query("INSERT INTO expenses VALUES (NULL, '$userId', '$kategoria', '$platnosc', '$kwota', '$data', '$opis')"))
          {            
              $_SESSION['komunikat']="Wydatek został zarejestrowany!";
          }
          else
          {
            throw new Exception($polaczenie->error);
          }          
        }        
        $polaczenie->close();
      }      
    }
    catch(Exception $e)
    {
      echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie!</span>';
      echo '<br />Informacja developerska: '.$e;
    }    
  }
?>


<!DOCTYPE html>
<html lang="pl">
	<head>
	    <meta charset="UTF-8">
	    <title>Wydatek</title>
	    <link rel="shortcut icon" href="https://img.icons8.com/office/16/000000/money-bag.png"/>
    	<!-- <a href="https://icons8.com/icon/43840/money-box">Money Box icon by Icons8</a> money-box--v1.png-->
	    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	    <meta name="description" content="">
	  	<meta name="keywords" content="">
	  	<meta name="author" content="">
	  	<meta http-equiv="X-Ua-Compatible" content="IE=edge">
	    <link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="main.css">
		
	</head>
	<body>

	 	 <nav class="navbar navbar-light bg-faded navbar-expand-sm">
	  
		    <div class="collapse navbar-collapse navbar-toggleable-md" id="navbarNavAltMarkup">
		        <div class="navbar-nav mx-auto">
		            <a class="nav-link" href="przychod.php">Przychód</a>
		            <a class="nav-link active" href="wydatek.php">Wydatek</a>
		            <a class="nav-link" href="bilans.php">Bilans</a>
		            <a class="nav-link" href="ustawienia.php">Ustawienia</a>
		            <a class="nav-link" href="logout.php">Wylogowanie</a>
		        </div>
		    </div>
		    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
		        <span class="navbar-toggler-icon"></span>
		    </button>

	 	</nav>	

		<div class="container bg-white text-center" style="max-width:450px; margin:auto; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
			<h1 style="margin-top: 10px; padding-top: 15px;">WYDATEK</h1>
			
			<form method="post">
				<div class="input-group mb-2">
					<div class="input-group-prepend w-25">
						<span class="input-group-text w-100 justify-content-center">Kwota</span>
					</div>
					<input type="text" class="form-control" placeholder="0,00" name="kwota" onkeydown="usuwanieKomentarzy()" autofocus />	
					<span style="margin-left: 5px; color: red;">*</span>				
				</div>
				
				<div id="kom_amount">
					<?php
      					if (isset($_SESSION['e_kwota']))
      					{
        					echo '<div class="error" style="color:red; font-size: 13px; text-align: center; margin-top: auto; margin-bottom: 5px; width=100%;">'.$_SESSION['e_kwota'].'</div>';
        					unset($_SESSION['e_kwota']); 
	        			}  
	        		?>				
				</div>
				
				<div class="input-group mb-2">
					<div class="input-group-prepend w-25">
						<span class="input-group-text w-100 justify-content-center">Data</span>
					</div>
					<input id="date" type="date" class="form-control" name="data" onkeydown="usuwanieKomentarzy()"/>
					<span style="margin-left: 5px; color: red;">*</span>	
				</div>
				
				<div id="kom_date">
					<?php
      					if (isset($_SESSION['e_data']))
      					{
        					echo '<div class="error" style="color:red; font-size: 13px; text-align: center; margin-top: auto; margin-bottom: 5px;">'.$_SESSION['e_data'].'</div>';
        					unset($_SESSION['e_data']); 
	        			}  
	        		?>					
				</div>
				<script>
					let today = new Date().toISOString().substr(0, 10);
					document.querySelector("#date").value = today;
				</script>
				
				<div class="input-group mb-2">
				<div class="input-group-prepend w-25">
					<span class="input-group-text w-100 justify-content-center">Kategoria</span>
				</div>
				<div class="dropdown flex-grow-1">

		<?php
			require_once "connect.php";
			$polaczenie = new mysqli($host, $db_user, $db_password, $db_name) or die("Błąd połączenia!");
			$userId = $_SESSION['id'];  

				mysqli_set_charset($polaczenie, "utf8");
				$q = "SELECT id, name FROM expenses_category_assigned_to_users where user_id = $userId";
				$result = mysqli_query($polaczenie, $q) or die("Problemy z odczytem danych!");		
		?>

		<select class="dropdown h-100 w-100" name="kategoria" onchange="usuwanieKomentarzy()">
			<?php
			while($row = mysqli_fetch_assoc($result)){
			?>
				<option value = "<?php echo $row['id'];?>">
				<?php echo $row['name'];?> 
				</option>
			<?php
			}
			?>
		</select> 
				</div>
					<span style="margin-left: 5px; color: red;">*</span>
				</div>
				
				<div class="input-group mb-2">
				<div class="input-group-prepend w-25">
					<span class="input-group-text w-100 justify-content-center">Płatność</span>
				</div>
				<div class="dropdown flex-grow-1">

		<?php

			$sql = "SELECT id, name FROM payment_methods_assigned_to_users where user_id = $userId";
			$result = mysqli_query($polaczenie, $sql) or die("Problemy z odczytem danych!");
		?>

		<select class="dropdown h-100 w-100" name="platnosc" onchange="usuwanieKomentarzy()">
			<?php
			while($row = mysqli_fetch_assoc($result)){
			?>
				<option value = "<?php echo $row['id'];?>">
				<?php echo $row['name'];?> 
				</option>
			<?php
			}
				$polaczenie->close();
			?>
		</select> 

				</div>
					<span style="margin-left: 5px; color: red;">*</span>
				</div>

				<div class="input-group mb-2">
					<div class="input-group-prepend w-25">
						<span class="input-group-text w-100 justify-content-center">Opis</span>
					</div>
					<textarea class="form-control" name="opis" onkeydown="usuwanieKomentarzy()"></textarea>
				</div>

				<div id="kom_success">	
					<?php
      					if (isset($_SESSION['komunikat']))
      					{
        					echo '<div class="error" style="color:green; font-size: 15px; text-align: center; margin-top: auto; margin-bottom: auto;">'.$_SESSION['komunikat'].'</div>';
        					unset($_SESSION['komunikat']); 
	        			}  
	        		?>		
	        	</div>	
				
				<div class="w-100 mt-4">
					<button type="submit" class="btn btn-primary mr-2">Dodaj wydatek</button>
				</div>

				<div id="komunikat"></div>
			</form>			
		</div>

	  	<script>
	      
		    function usuwanieKomentarzy() {
		    	document.getElementById("kom_success").innerHTML = "";
		    	document.getElementById("kom_amount").innerHTML = "";
		    	document.getElementById("kom_date").innerHTML = "";
			}

	    </script>

	   <script src="js/jquery/jquery.min.js"></script>
	   <script src="js/popper/popper.min.js"></script>
	   <script src="js/bootstrap/bootstrap.min.js"></script>
		
	</body>
</html>
