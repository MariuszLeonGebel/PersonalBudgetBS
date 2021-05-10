<?php

  session_start();

  if((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==true))
{
  header('Location: menu.php');
  exit();
}
?>

<!DOCTYPE html>
<html lang="pl">
  <head>
    <title>Logowanie</title>
    <link rel="shortcut icon" href="https://img.icons8.com/office/16/000000/money-bag.png"/>
    <!-- <a href="https://icons8.com/icon/43840/money-box">Money Box icon by Icons8</a> money-box--v1.png-->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="main.css">
    <style>
    body {font-family: Arial, Helvetica, sans-serif;}
    * {box-sizing: border-box;}
    h2{color: black;}
    label{color: black;}

    .input-container {
      display: -ms-flexbox; /* IE10 */
      display: flex;
      width: 100%;
      margin-bottom: 15px;
    }

    .icon {
      padding: 10px;
      background: dodgerblue;
      color: white;
      min-width: 50px;
      text-align: center;
    }

    .input-field {
      width: 100%;
      padding: 10px;
      outline: none;
    }

    .input-field:focus {
      border: 2px solid dodgerblue;
    }

    /* Set a style for the submit button */
    .btn {
      background-color: dodgerblue;
      color: white;
      padding: 15px 20px;
      border: none;
      cursor: pointer;
      width: 100%;
      opacity: 0.9;
      margin-bottom: 10px;
      border-radius: 5px;
    }

    .btn:hover {
      opacity: 1;
    }

    #button_color
    {
      background-color: red;
    }
    </style>
  </head>
  <body>

    <form action="zaloguj.php" method="post" style="max-width:450px; margin:auto;">
      <h2>Logowanie</h2>
      <div class="input-container">
        <i class="fa fa-user icon"></i>
        <input class="input-field" type="text" placeholder="Login" name="login" onkeydown="usuwanieKomentarza()" autofocus>
      </div>

      <div class="input-container">
        <i class="fa fa-key icon"></i>
        <input class="input-field" type="password" placeholder="Hasło" name="haslo" onkeydown="usuwanieKomentarza()">
      </div>

      <button type="submit" class="btn">Zaloguj się</button>
      <button type="button" class="btn" id="button_color" onclick="returnToIndex()">Anuluj</button>  
     

      <div id="kom_blad">

    <?php
    if(isset($_SESSION['blad']))
    echo $_SESSION['blad'];
    unset($_SESSION['blad']);
    ?>
    
    </div>
    </form>

   <script>      
    function usuwanieKomentarza() {
      document.getElementById("kom_blad").innerHTML = "";
    }

    function returnToIndex() {
      window.location.href="index.php";
    }
       
    </script>

  </body>
</html>
