<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pl">
	<head>
	    <meta charset="UTF-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	    <link rel="shortcut icon" href="https://img.icons8.com/office/16/000000/money-bag.png"/>
    	<!-- <a href="https://icons8.com/icon/43840/money-box">Money Box icon by Icons8</a> money-box--v1.png-->
    	<title>Bilans</title>
	    <meta name="description" content="">
	  	<meta name="keywords" content="">
	  	<meta name="author" content="">
	  	<meta http-equiv="X-Ua-Compatible" content="IE=edge">
	    <link rel="stylesheet" href="css/bootstrap.min.css">
	    <title>Bilans</title>
		<link rel="stylesheet" href="main.css">
	</head>
	<body onload="wyborOkresu()">

	 <nav class="navbar navbar-light bg-faded navbar-expand-sm">
	  
	    <div class="collapse navbar-collapse navbar-toggleable-md" id="navbarNavAltMarkup">
	        <div class="navbar-nav mx-auto">
	            <a class="nav-link" href="przychod.php">Przychód</a>
	            <a class="nav-link" href="wydatek.php">Wydatek</a>
	            <a class="nav-link active" href="bilans.php">Bilans</a>
	            <a class="nav-link" href="ustawienia.php">Ustawienia</a>
	            <a class="nav-link" href="logout.php">Wylogowanie</a>
	        </div>
	    </div>
	    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
	        <span class="navbar-toggler-icon"></span>
	    </button>
	 </nav>		

	<div class="container bg-white text-center col-12 col-lg-10 col-xl-8 mt-sm-2 p-1 p-lg-3 shadow">		
		<h1>BILANS</h1>
		
		<fieldset class="border m-2">		
			<form method="Post">
				<div class="input-group mb-2 w-75 mx-auto mt-4">
					<div class="input-group-prepend w-50">
						<span class="input-group-text w-100 justify-content-center">Podaj okres bilansu</span>
					</div>
					<select id="okres" class="selection" name="wybor" onchange="wyborOkresu();">
						<option value="Aktualny">Aktualny miesiąc</option>
						<option value="Poprzedni">Poprzedni miesiąc</option>
						<option value="Rok">Aktualny rok</option>
						<option value="InnyOkres">Inny okres</option>
					</select>
				</div>

				<div id="wybranyOkresSprawozdania" style="color: #C0D06F; margin-bottom: 10px; font-size: 16px;">				
				</div>

				<fieldset class="border m-2">
					<label for="radioSuma" style="color: black;">Podsumowania</label>
					<input id="radioSuma" type="radio" name="rodzajRaportu" style="margin-right: 50px; color: black; margin-top: 20px; margin-bottom: 20px;" value="podsumowania"checked>
					<label for="radioSzczegol" style="color: black;">Szczegóły</label>
					<input id="radioSzczegol" type="radio" name="rodzajRaportu" style="margin-right: 50px; color: black;" value="szczegoly">
				</fieldset>
				
				<!-- Button trigger modal -->
				<button id="hiddenbutton" type="button" class="btn btn-warning btn-sm mb-2 w-75 mx-auto mt-0" data-toggle="modal" data-target="#myModal" style="display: none; width: 100%;">
	  			Zmień okres zestawienia
				</button>

					<input id="DPocz" type="text" name="DP" style="display: none;">
					<input id="DKonc" type="text" name="DK" style="display: none;">
					<input type="text" name="Spr" style="display: none;" >
					<input id="tO" type="text" name="Okres" style="display: none;">
					
				<button id="statementButton" type="submit" class="btn btn-primary btn-sm mb-2 w-75 mx-auto mt-2" style="width: 100%;" name="statButton">
	  			Pokaż zestawienie
				</button>
			</form>		

				<div id="kolorWynikuFinansowego" class="bg-success py-2 px-4 mt-3 mb-3">
					<h5 id="wynikFinansowy" class="font-weight-bold">Wybierz okres zestawienia</h5>
					<h5 id="komWynikuFinansowego" class="font-weight-bold"></h5>
				</div>			

				<div class="bg-info">
					<h6 id="pasekOkresu" class="font-weight-bold"></h6>
				</div>
			
				<div class="modal fade" id="myModal" aria-hidden="true" style="display: none;">
					<div class="modal-dialog">
						<div class="modal-content">
							<form method="post">
								<!-- Modal Header -->
								<div class="modal-header">
									<h4 class="modal-title" style="color: #C0D06F;">Wybierz okres sprawozdania: </h4>
									<button type="button" class="close" data-dismiss="modal">×</button>
								</div>							
								<!-- Modal body -->
								<div class="modal-body">
									<div class="input-group mb-2 w-100">
										<div class="input-group-prepend w-50">
											<span class="input-group-text w-100 justify-content-center">Data początkowa:</span>
										</div>
										<input id="dateB" type="date" name="start" class="form-control" required="">
									</div>
									<div class="input-group mb-2 w-100">
										<div class="input-group-prepend w-50">
											<span class="input-group-text w-100 justify-content-center">Data końcowa:</span>
										</div>
										<input id="dateE" type="date" name="end" class="form-control" required="">
									</div>

									<script>
										let today = new Date().toISOString().substr(0, 10);
										document.querySelector("#dateB").valueAsDate = new Date();
										document.querySelector("#dateE").valueAsDate = new Date();
									</script>										
								</div>
							
								<!-- Modal footer -->
								<div class="modal-footer">
								<input type="button" class="btn btn-primary" name="customize_period" onclick="zamkniecieModal()" value="OK" data-dismiss="modal">
								<button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
								</div>
							</form>
						</div>
					</div>
				</div>

				<?php
				if(isset($_POST['Spr'])) {
							$_SESSION['sumy_szczegoly'] = $_POST['rodzajRaportu'];
					if($_SESSION['sumy_szczegoly'] == "podsumowania"){

echo <<<EOL

					<fieldset class="border m-3">
						<legend class="border" style="color: #C0D06F">Przychody (podsumowanie)</legend>
						<table class="table table-striped m-2 col-11 col-md-6 table-sm" style="width: 60%; color: black; float: left; border-collapse: separate; border: 1px solid grey; font-size: 13px;">
							<colgroup>
								<col width="70%">
								<col width="30%">
							</colgroup>
						    <thead>
						    <tr>
						      <th scope="col">Kategoria</th>
						      <th scope="col">Kwota</th>
						    </tr>
						  	</thead>
						  	<tbody>
EOL;

					require_once "connect.php";
					$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);	
					if ($polaczenie->connect_errno!=0)
					{
						echo "Error: ".$polaczenie->connect_errno;
					}
					else
					{
						$_SESSION['dataPoczatkowa'] = $_POST['DP'];
						$_SESSION['dataKoncowa'] = $_POST['DK'];
						$_SESSION['tekstOkres'] = $_POST['Okres'];
						$userId = $_SESSION['id'];
						$dPocz = $_SESSION['dataPoczatkowa'];
						$dKonc = $_SESSION['dataKoncowa']; 
						$incomesSum = 0;
														
						$q1 = "SELECT id, name FROM incomes_category_assigned_to_users WHERE user_id = $userId";
						$result1 = mysqli_query($polaczenie, $q1) or die("Problemy z odczytem danych0!");
						
						while($row1 = mysqli_fetch_array($result1)){
						$q = "SELECT sum(amount) FROM incomes WHERE user_id = $userId AND income_category_assigned_to_user_id = $row1[0] AND date_of_income >='$dPocz' AND date_of_income <='$dKonc'";
						$result = mysqli_query($polaczenie, $q) or die("Problemy z odczytem danych1!");

							while($row = mysqli_fetch_array($result)){
								if($row[0]>0){
								echo   '<tr >';
							    //echo  '<td><input type="radio"></td>';
							    $kwota = number_format($row[0], 2, ',', '.');
							   	echo ' <td>'.$row1[1]."</td>";
							    echo ' <td style="text-align: right;">'.$kwota."</td>";
							    $incomesSum += $row[0];
							    echo "</tr>";
							    }
					   		} 
					 	}
					  	echo "</tbody>";
						echo '<tfoot>';
						echo '<tr>';
						echo '<th style="text-align: right;">RAZEM';
						$_SESSION['s_incomeSum'] = $incomesSum;
						$incomesSum = number_format($incomesSum, 2, ',', '.');
						echo '<th style="text-align: right;">'.$incomesSum;
						echo '</tr';
						echo '</tfoot>';						
							 
echo <<<EOL1
			
						</table>
						<table class="table table-striped m-1" style="width: 40%; color: black; float: left; border-collapse: separate; ">
						<!-- tu miałbybyć wykres przychodów -->
						</table>
			
					</fieldset>
				


					<fieldset class="border m-3">
						<legend class="border" style="color: #C0D06F">Wydatki (podsumowanie)</legend>
						<table class="table table-striped m-2 col-11 col-md-6 table-sm" style="width: 60%; color: black; float: left; border-collapse: separate; border: 1px solid grey; font-size: 13px;">
							<colgroup>
								<col width="70%">
								<col width="30%">
							</colgroup>
						    <thead>
						    <tr>
						      <th scope="col">Kategoria</th>
						      <th scope="col">Kwota</th>
						    </tr>
						  	</thead>
						  	<tbody>
EOL1;

					$expensesSum = 0;
					$q2 = "SELECT id, name FROM expenses_category_assigned_to_users WHERE user_id = $userId";
					$result2 = mysqli_query($polaczenie, $q2) or die("Problemy z odczytem danych0!");
				
					while($row3 = mysqli_fetch_array($result2)){
						$q3 = "SELECT sum(amount) FROM expenses WHERE user_id = $userId AND expense_category_assigned_to_user_id = $row3[0] AND date_of_expense >='$dPocz' AND date_of_expense <='$dKonc'";
						$result3 = mysqli_query($polaczenie, $q3) or die("Problemy z odczytem danych1!");

						while($row4 = mysqli_fetch_array($result3)){
							if($row4[0]>0){
								echo   '<tr >';
							   // echo  '<td><input type="radio"></td>';
							    $kwota1 = number_format($row4[0], 2, ',', '.');
							   	echo ' <td>'.$row3[1]."</td>";
							    echo ' <td style="text-align: right;">'.$kwota1."</td>";
							    $expensesSum += $row4[0];
							    echo "</tr>";
						    }
					   	} 
			 		}	
					echo "</tbody>";
					echo '<tfoot>';
					echo '<tr>';
					echo '<th style="text-align: right;">RAZEM';
					$_SESSION['s_expenseSum'] = $expensesSum;
					$expensesSum = number_format($expensesSum, 2, ',', '.');
					$difference = ($_SESSION['s_incomeSum'] - $_SESSION['s_expenseSum']);
					
					$difference = number_format($difference, 2, ',', '.');
					$_SESSION['s_difference'] = $difference;
					echo '<th style="text-align: right;">'.$expensesSum;
					echo '</tr';
					echo '</tfoot>';
					$polaczenie->close();
				}	
			}

		else if($_SESSION['sumy_szczegoly'] == "szczegoly"){
echo <<<EOL2

				<fieldset class="border m-3">
					<legend class="border" style="color: #C0D06F">Przychody (szczegóły)</legend>
					
						<table class="table table-striped m-2 col-12 col-md-6 table-sm" style="width: 98%; color: black; border-collapse: separate; border: 1px solid grey; font-size: 12px;">
						<colgroup>
							<col width="3%"> 
							<col width="3%"> 
							<col width="30%">
							<col width="20%">
							<col width="15%">
							<col width="29%">						
						</colgroup>
					    <thead>
					    <tr>
					      <th scope="col">E.</th>
					      <th scope="col">U.</th>
					      <th scope="col">Kategoria</th>				      
						  <th scope="col">Data</th>
					      <th scope="col">Kwota</th>
					     <th scope="col">Opis</th>
					    </tr>
					  	</thead>
					  	<tbody>
EOL2;

	
							require_once "connect.php";
							$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);	
							if ($polaczenie->connect_errno!=0)
							{
								echo "Error: ".$polaczenie->connect_errno;
							}
							else
							{
								$_SESSION['dataPoczatkowa'] = $_POST['DP'];
								$_SESSION['dataKoncowa'] = $_POST['DK'];
								$_SESSION['tekstOkres'] = $_POST['Okres'];
								$userId = $_SESSION['id'];
								$dPocz = $_SESSION['dataPoczatkowa'];
								$dKonc = $_SESSION['dataKoncowa']; 
								$incomesSum = 0;
																
								$q = "SELECT i.id, k.name, i.date_of_income, i.amount, i.income_comment FROM incomes i LEFT JOIN incomes_category_assigned_to_users k ON k.id = i.income_category_assigned_to_user_id WHERE i.user_id = $userId AND i.date_of_income >='$dPocz' AND i.date_of_income <='$dKonc'";
								$result = mysqli_query($polaczenie, $q) or die("Problemy z odczytem danych0!");
								
								while($row = mysqli_fetch_array($result)){					
									echo '<tr >';
								    echo '<td><input type="radio"></td>';
								    echo '<td><input type="radio"></td>';
								    echo '<td>'.$row[1].'</td>';
								    $kwota = number_format($row[3], 2, ',', '.');
								   	echo '<td>'.$row[2].'</td>';
								    echo '<td style="text-align: right;">'.$kwota.'</td>';
								    echo '<td>'.$row[4].'</td>';
								    $incomesSum += $row[3];
								    echo '</tr>';
							 	}
							  	echo '</tbody>';
								echo '<tfoot>';
								echo '<tr>';
								echo '<th colspan="4" style="text-align: right;">RAZEM</th>';
								$_SESSION['s_incomeSum'] = $incomesSum;
								$incomesSum = number_format($incomesSum, 2, ',', '.');
								echo '<th style="text-align: right;">'.$incomesSum.'</th>';
								echo '</tr>';
								echo '</tfoot>';
								echo '</table>';
								echo '</fieldset>';
					
echo <<<EOL3

				<fieldset class="border m-3">
				<legend class="border" style="color: #C0D06F">Wydatki (szczegóły)</legend>
				
					<table class="table table-striped m-2 col-12 col-md-6 table-sm" style="width: 98%; color: black; border-collapse: separate; border: 1px solid grey; font-size: 12px;">
					<colgroup>
						<col width="3%"> 
						<col width="3%"> 
						<col width="30%">
						<col width="20%">
						<col width="15%">
						<col width="29%">						
					</colgroup>
				    <thead>
				    <tr>
				      <th scope="col">E.</th>
				      <th scope="col">U.</th>
				      <th scope="col">Kategoria</th>				      
					  <th scope="col">Data</th>
				      <th scope="col">Kwota</th>
				      <th scope="col">Opis</th>
				    </tr>
				  	</thead>
				  	<tbody>

EOL3;

					$expensesSum = 0;
									
					$q5 = "SELECT e.id, k.name, p.name, e.date_of_expense, e.amount, e.expense_comment FROM expenses e LEFT JOIN expenses_category_assigned_to_users k on k.id = e.expense_category_assigned_to_user_id LEFT JOIN payment_methods_assigned_to_users p on p.id = e.payment_method_assigned_to_user_id WHERE e.user_id = $userId AND e.date_of_expense >='$dPocz' AND e.date_of_expense <='$dKonc'";
					$result3 = mysqli_query($polaczenie, $q5) or die("Problemy z odczytem danych0!");

						while($row4 = mysqli_fetch_array($result3)){
							echo '<tr >';
						   	echo '<td><input type="radio"></td>';
						   	echo '<td><input type="radio"></td>';
						    $kwota1 = number_format($row4[4], 2, ',', '.');
						   	echo '<td>'.$row4[1].'</td>';
						   	echo '<td>'.$row4[3].'</td>';
						    echo '<td style="text-align: right;">'.$kwota1.'</td>';
						    $expensesSum += $row4[4];
						    echo ' <td>'.$row4[5].'</td>';
						    echo '</tr>';
				 		}	
						echo '</tbody>';
						echo '<tfoot>';
						echo '<tr>';
						echo '<th colspan="4" style="text-align: right;">RAZEM</th>';
						$_SESSION['s_expenseSum'] = $expensesSum;
						$expensesSum = number_format($expensesSum, 2, ',', '.');
						$difference = ($_SESSION['s_incomeSum'] - $_SESSION['s_expenseSum']);									
						$difference = number_format($difference, 2, ',', '.');
						$_SESSION['s_difference'] = $difference;
						echo '<th style="text-align: right;">'.$expensesSum.'</th>';
						echo '</tr>';
						echo '</tfoot>';
						echo '</fieldset>';
						$polaczenie->close();
					}
				}		 	
			}

			?>

				<script>
				
					var x = "<?php echo $_SESSION['dataPoczatkowa'] ?>";
					var y = "<?php echo $_SESSION['dataKoncowa'] ?>";
					var z = "<?php echo $_SESSION['s_difference'] ?>";
					var k = "<?php echo $_SESSION['s_incomeSum'] ?>";
					var l = "<?php echo $_SESSION['s_expenseSum'] ?>";				
					var o = "<?php echo $_SESSION['tekstOkres'] ?>";
										
					document.getElementById("pasekOkresu").innerHTML = "ZESTAWIENIE ZA OKRES: "+x+" - "+y;
					document.getElementById("wynikFinansowy").innerHTML = "Całkowity bilans: "+z+" zł";
					
					if((k-l) < 0) {
						document.getElementById("kolorWynikuFinansowego").setAttribute("class", "bg-danger py-2 px-4 mt-3 mb-3");
						document.getElementById("komWynikuFinansowego").innerHTML = "Uwaga! W "+o+" twoje wydatki przekroczyły przychody!";
					} else if ((k-l) == 0 && k==0 && l==0) {
						document.getElementById("kolorWynikuFinansowego").setAttribute("class", "bg-primary py-2 px-4 mt-3 mb-3");
						document.getElementById("komWynikuFinansowego").innerHTML = "W "+o+" nie było żadnych przychodów i wydatków!";
					}  else if ((k-l) == 0) {
						document.getElementById("kolorWynikuFinansowego").setAttribute("class", "bg-success py-2 px-4 mt-3 mb-3");
						document.getElementById("komWynikuFinansowego").innerHTML = "W "+o+" przychody były równe wydatkom!";
					} else if ((k-l) >0) {
						document.getElementById("kolorWynikuFinansowego").setAttribute("class", "bg-success py-2 px-4 mt-3 mb-3");
						document.getElementById("komWynikuFinansowego").innerHTML = "Gratulacje! Bardzo dobrze zarządzasz swoimi finansami!";
					}	

				</script>
			</table>
		</fieldset>	
	</div>
		
	<script type="text/javascript">
		var dataP;
		var dataK;

		function wyborOkresu() {
			var pagebutton= document.getElementById("hiddenbutton");
			var today = new Date();
			var dd = String(today.getDate()).padStart(2, '0');
			var mm = String(today.getMonth() + 1).padStart(2, '0');
			var mmPop = String(today.getMonth()).padStart(2, '0');
			var yyyy = today.getFullYear();
		
	    	if(document.getElementById("okres").value == "InnyOkres") {	  
	    		pagebutton.click();
	    		pagebutton.style="";    
	    		document.getElementById("tO").value = "wybranym okresie";
	    	}
	    	else if(document.getElementById("okres").value == "Aktualny") {
	    		pagebutton.style="display: none";
	    		dataP = yyyy + "-" + mm + "-01";
	    		dataK = yyyy + "-" + mm + "-" + dd;
	    		document.getElementById("wybranyOkresSprawozdania").innerHTML = "Wybrany okres sprawozdania: "+dataP+" - "+dataK;
				document.getElementById("tO").value = "bieżącym miesiącu";
	    	}
	    	else if(document.getElementById("okres").value == "Poprzedni") {
	    		pagebutton.style="display: none";
	    		var ostatni = ostatniDzienMiesiaca(mmPop*1, yyyy)
	    		dataP = yyyy + "-" + (mmPop) + "-01";
	    		dataK = yyyy + "-" + (mmPop) + "-" + ostatni;
	    		var miesiacP = previousMonthInWords(mmPop*1);
	    		document.getElementById("wybranyOkresSprawozdania").innerHTML = "Wybrany okres sprawozdania: "+miesiacP+" "+yyyy+" r.";
	    		document.getElementById("tO").value = "poprzednim miesiącu"; 
	    	}
	    	else if(document.getElementById("okres").value == "Rok") {
	    		pagebutton.style="display: none";
    			document.getElementById("wybranyOkresSprawozdania").innerHTML = "Wybrany okres sprawozdania: Rok "+yyyy;
    			dataP = yyyy + "-01-01";
    			dataK = yyyy + "-" + mm + "-" + dd;
    			document.getElementById("tO").value = "bieżącym roku";
	    	}	
	    	document.getElementById("DPocz").value = dataP;
	    	document.getElementById("DKonc").value = dataK;   		
	    }	    
		
		function zamkniecieModal() {
			dataP = document.getElementById("dateB").value;
			dataK = document.getElementById("dateE").value;
			document.getElementById("wybranyOkresSprawozdania").innerHTML = "Wybrany okres sprawozdania: "+dataP+" - "+dataK;
			document.getElementById("DPocz").value = dataP;
	    	document.getElementById("DKonc").value = dataK;
		}

		function previousMonthInWords(m){
			var nazwaMiesiaca;
			switch(m) {
				case 0: nazwaMiesiaca = "GRUDZIEŃ"; break;
				case 1: nazwaMiesiaca = "STYCZEŃ"; break;
				case 2: nazwaMiesiaca = "LUTY"; break;
				case 3: nazwaMiesiaca = "MARZEC"; break;
				case 4: nazwaMiesiaca = "KWIECIEŃ"; break;
				case 5: nazwaMiesiaca = "MAJ"; break;
				case 6: nazwaMiesiaca = "Czerwiec"; break;
				case 7: nazwaMiesiaca = "LIPIEC"; break;
				case 8: nazwaMiesiaca = "SIERPIEŃ"; break;
				case 9: nazwaMiesiaca = "WRZESIEŃ"; break;
				case 10: nazwaMiesiaca = "PAŹDZIERNIK"; break;
				case 11: nazwaMiesiaca = "LISTOPAD"; break;
			};
			return nazwaMiesiaca;
		}

		function ostatniDzienMiesiaca(m, y) {
			var LiczbaDni;
			switch(m) {
				case 0: LiczbaDni = 31; break;
				case 1: LiczbaDni = 31; break;
				case 2: 
					var d = checkLeapYear(y);
					LiczbaDni = d;
					break;
				case 3: LiczbaDni = 31; break;
				case 4: LiczbaDni = 30; break;
				case 5: LiczbaDni = 31; break;
				case 6: LiczbaDni = 30; break;
				case 7: LiczbaDni = 31; break;
				case 8: LiczbaDni = 31; break;
				case 9: LiczbaDni = 30; break;
				case 10: LiczbaDni = 31; break;
				case 11: LiczbaDni = 30; break;
			};
			return LiczbaDni;
		}

		function checkLeapYear(year) {
			var dni;
		    if ((0 == year % 4) && (0 != year % 100) || (0 == year % 400)) {
		        dni = 29;
		    } else {
		        dni = 28;
		    }
		    return dni;
		}

	</script>
	  <script src="js/jquery/jquery.min.js"></script>
	  <script src="js/popper/popper.min.js"></script>
	  <script src="js/bootstrap/bootstrap.min.js"></script>
		
	</body>
</html>
