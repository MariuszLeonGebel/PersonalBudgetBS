<?php

  session_start();
  
  if (isset($_POST['email']))
  {
    //Udana walidacja? Załóżmy, że tak!
    $wszystko_OK=true;
    
    //Sprawdzenie poprawności loginu
    $login = $_POST['login'];
    
    //Sprawdzenie długości loginu
    if ((strlen($login)<3) || (strlen($login)>20))
    {
      $wszystko_OK=false;
      $_SESSION['e_login']="Login musi posiadać od 3 do 20 znaków!";
    }
    
    if (ctype_alnum($login)==false)
    {
      $wszystko_OK=false;
      $_SESSION['e_login']="Login może składać się tylko z liter i cyfr (bez polskich znaków)";
    }
    
    // Sprawdzenie poprawności adresu email
    $email = $_POST['email'];
    $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
    
    if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email))
    {
      $wszystko_OK=false;
      $_SESSION['e_email']="Podaj poprawny adres e-mail!";
    }
    
    //Sprawdzenie poprawności hasła
    $haslo1 = $_POST['haslo1'];
    $haslo2 = $_POST['haslo2'];
    
    if ((strlen($haslo1)<3) || (strlen($haslo1)>20))
    {
      $wszystko_OK=false;
      $_SESSION['e_haslo']="Hasło musi posiadać od 3 do 20 znaków!";
    }
    
    if ($haslo1!=$haslo2)
    {
      $wszystko_OK=false;
      $_SESSION['e_haslo']="Podane hasła nie są identyczne!";
    } 

    $haslo_hash = password_hash($haslo1, PASSWORD_DEFAULT);
    
    $sekret = "6Lc2iboaAAAAAHTcqnh3dNKjTfvvmqHtiX45PyQi";
    
    $sprawdz = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$sekret.'&response='.$_POST['g-recaptcha-response']);
    
    $odpowiedz = json_decode($sprawdz);
    
    if ($odpowiedz->success==false)
    {
      $wszystko_OK=false;
      $_SESSION['e_bot']="Potwierdź, że nie jesteś botem!";
    }   
    
    //Zapamiętanie wprowadzonych danych
    $_SESSION['fr_login'] = $login;
    $_SESSION['fr_email'] = $email;
    $_SESSION['fr_haslo1'] = $haslo1;
    $_SESSION['fr_haslo2'] = $haslo2;
       
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
        //Czy email już istnieje?
        $rezultat = $polaczenie->query("SELECT id FROM users WHERE email='$email'");
        if (!$rezultat) throw new Exception($polaczenie->error);
        
        $ile_takich_maili = $rezultat->num_rows;
        if($ile_takich_maili>0)
        {
          $wszystko_OK=false;
          $_SESSION['e_email']="Istnieje już konto przypisane do tego adresu e-mail!";
        }   

        //Czy login jest już zarezerwowany?
        $rezultat = $polaczenie->query("SELECT id FROM users WHERE username='$login'");
        
        if (!$rezultat) throw new Exception($polaczenie->error);
        
        $ile_takich_nickow = $rezultat->num_rows;
        if($ile_takich_nickow>0)
        {
          $wszystko_OK=false;
          $_SESSION['e_login']="Istnieje już gracz o takim loginie! Wybierz inny.";
        }
        
        if ($wszystko_OK==true)
        {  
          $_SESSION['udanarejestracja']=true;       
          if ($polaczenie->query("INSERT INTO users VALUES (NULL, '$login', '$haslo_hash', '$email')"))
          {
            if($polaczenie->query("INSERT INTO incomes_category_assigned_to_users (name) SELECT name FROM incomes_category_default"))
            { 
                $result1 = $polaczenie->query("SELECT id FROM users WHERE username='$login'");
                $row = mysqli_fetch_assoc($result1);
                $idLogin = $row['id'];
              if($polaczenie->query("UPDATE incomes_category_assigned_to_users SET user_id = $idLogin WHERE user_id=0"))
              {                
                if($polaczenie->query("INSERT INTO expenses_category_assigned_to_users (name) SELECT name FROM expenses_category_default"))
                { 
                  if($polaczenie->query("UPDATE expenses_category_assigned_to_users SET user_id = $idLogin WHERE user_id=0"))
                  {
                    if($polaczenie->query("INSERT INTO payment_methods_assigned_to_users (name) SELECT name FROM payment_methods_default"))
                    { 
                      if($polaczenie->query("UPDATE payment_methods_assigned_to_users SET user_id = $idLogin WHERE user_id=0"))
                      {
                        $_SESSION['udanarejestracja']=true;
                        header('Location: index.php');
                      }
                    }
                  }
                }                 
              }        
            }
          else
          {
            throw new Exception($polaczenie->error);
          }          
        }        
        $polaczenie->close();
      }      
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
    <title>Rejestracja</title>
    <link rel="shortcut icon" href="https://img.icons8.com/office/16/000000/money-bag.png"/>
    <!-- <a href="https://icons8.com/icon/43840/money-box">Money Box icon by Icons8</a> money-box--v1.png-->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <script src='https://www.google.com/recaptcha/api.js'></script>
    <link rel="stylesheet" href="main.css">

    <style>
    body {font-family: Arial, Helvetica, sans-serif;}
    * {box-sizing: border-box;}
    h2{color: black;}

    .input-container {
      display: -ms-flexbox;
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

    .g-recaptcha
    {
      width: 100%;
      margin-bottom: 20px;
    }

    </style>
  </head>
  <body>

    <form method="post" style="max-width:450px;margin:auto">
      <h2>Rejestracja</h2>
      <div class="input-container">
        <i class="fa fa-user icon"></i>
        <input class="input-field" type="text" placeholder="Login" autofocus value="<?php
        if (isset($_SESSION['fr_login']))
        {
          echo $_SESSION['fr_login'];
          unset($_SESSION['fr_login']);
        }
      ?>" name="login">

      <?php
        if (isset($_SESSION['e_login']))
        {
          echo '<div class="error" style="color:red; font-size: 12px; text-align: center; margin-top: auto; margin-bottom: auto;">'.$_SESSION['e_login'].'</div>';
          unset($_SESSION['e_login']);
        }
      ?>

      </div>

      <div class="input-container">
        <i class="fa fa-envelope icon"></i>
        <input class="input-field" type="text" placeholder="Email" value="<?php
        if (isset($_SESSION['fr_email']))
        {
          echo $_SESSION['fr_email'];
          unset($_SESSION['fr_email']);
        }
      ?>" name="email">

      <?php
        if (isset($_SESSION['e_email']))
        {
          echo '<div class="error" style="color:red; font-size: 12px; text-align: center; margin-top: auto; margin-bottom: auto;">'.$_SESSION['e_email'].'</div>';
          unset($_SESSION['e_email']);
        }
      ?>

      </div>
      
      <div class="input-container">
        <i class="fa fa-key icon"></i>
        <input class="input-field" type="password" placeholder="Hasło" value="<?php
        if (isset($_SESSION['fr_haslo1']))
        {
          echo $_SESSION['fr_haslo1'];
          unset($_SESSION['fr_haslo1']);
        }
      ?>" name="haslo1">
      <?php
        if (isset($_SESSION['e_haslo']))
        {
          echo '<div class="error" style="color:red; font-size: 12px; text-align: center; margin-top: auto; margin-bottom: auto;">'.$_SESSION['e_haslo'].'</div>';
          unset($_SESSION['e_haslo']);
        }
      ?>
      </div>
      
       <div class="input-container">
        <i class="fa fa-key icon"></i>
        <input class="input-field" type="password" placeholder="Powtórz hasło" value="<?php
        if (isset($_SESSION['fr_haslo2']))
        {
          echo $_SESSION['fr_haslo2'];
          unset($_SESSION['fr_haslo2']);
        }
      ?>" name="haslo2">
      </div>

      <div class="g-recaptcha" data-sitekey="6Lc2iboaAAAAAGHQ1rKOFFWHBwijLFDlymvWu5pr" style="width: 100%; margin-bottom: 5px"></div>
      
      <?php
        if (isset($_SESSION['e_bot']))
        {
          echo '<div class="error" style="color:red; font-size: 12px; text-align: center; margin-top: auto; margin-bottom: auto;">'.$_SESSION['e_bot'].'</div>';
          unset($_SESSION['e_bot']);
        }
      ?>  

      <button type="submit" class="btn" style="margin-top: 10px">Zarejestruj się</button>
     
      <button type="button" class="btn" id="button_color" onclick="returnToIndex()">Anuluj</button>
    </form>

    <script>      
      function returnToIndex() {
        window.location.href="index.php";
      }
    </script>

  </body>
</html>
