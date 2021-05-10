<?php
  session_start();
 ?>

<!DOCTYPE html>
<html lang="pl">
	<head>
	    <meta charset="UTF-8">
	    <title>Przychody osobiste</title>
	    <link rel="shortcut icon" href="https://img.icons8.com/office/16/000000/money-bag.png"/>
    	<!-- <a href="https://icons8.com/icon/43840/money-box">Money Box icon by Icons8</a> money-box--v1.png-->
	    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	    <meta name="description" content="">
	  	<meta name="keywords" content="">
	  	<meta name="author" content="">
	  	<meta http-equiv="X-Ua-Compatible" content="IE=edge">
		<link rel="preconnect" href="https://fonts.gstatic.com">
		<link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Lemon&family=Pacifico&family=Lato&display=swap" rel="stylesheet">
	    <link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="main.css"> 		
	</head>

	<body>
		<main>
			<div class="container">
				
				<header>
					<h1 class="fancy1">Finanse osobiste</h1>
				</header>

				<div class="row">
					<div class="col-sm-12 col-md-6">
						<figure>
							<img src="img/pexels-michael-steinberg-321464.jpg" alt="pexels-M. Steinberg" class="img-fluid">
						</figure>
					</div>
	 
					<div class="center col-sm-12 col-md-6">
						<p class="green">Prosty sposób</p><p class="green">na kontrolę twoich<p class="green">przychodów i wydatków!</p>
					</div>
				</div>
				<div></div>
				<div class="row">
					<div class="col-sm-6">
						<a href="rejestracja.php" class="btn btn-warning index_button">Rejestracja</a>
					</div>

					<div class="col-sm-6">
						<a href="logowanie.php" class="btn btn-success index_button">Logowanie</a>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6" style="color: red; font-size: 12px; text-align: center; margin-left: auto; margin-right: auto;">
					<?php
						if(isset($_SESSION['udanarejestracja']) && $_SESSION['udanarejestracja']==true) {
							echo "Rejestracja przebiegła prawidłowo";
							unset($_SESSION['udanarejestracja']);
							}
					?>
					</div>
				</div>
			</div>
		</main>

	  	<script src="js/jquery/jquery.min.js"></script>
	    <script src="js/popper/popper.min.js"></script>
	    <script src="js/bootstrap/bootstrap.min.js"></script>
		
	</body>
</html>