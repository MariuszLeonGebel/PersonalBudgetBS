<?php

  session_start();

  require_once "connect.php";
  mysqli_report(MYSQLI_REPORT_STRICT);
  $wszystko_OK=true;  

  if(isset($_POST['zmiana_loginu']))
  {     
      $login = $_POST['login']; 
 
    if ((strlen($login)<3) || (strlen($login)>20))
    {
      $wszystko_OK=false;
      $_SESSION['fr_login'] = $login;
      $_SESSION['e_login']="Login musi posiadać od 3 do 20 znaków!";
    }

    else if($login==$_SESSION['login'])
        {
          $wszystko_OK=false;
          $_SESSION['e_login']="Nie zmieniłeś loginu! Podałeś taki sam login jak poprzednio!";
        }

    else if (ctype_alnum($login)==false)
    {
      $wszystko_OK=false;
      $_SESSION['fr_login'] = $login;
      $_SESSION['e_login']="Login może składać się tylko z liter i cyfr (bez polskich znaków)";
    }
    else {
      $_SESSION['login'] = $login;     
    }
 
   try 
    {
      $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
      if ($polaczenie->connect_errno!=0)
      {
        throw new Exception(mysqli_connect_errno());
      }
      else
      {
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
          //Testy zaliczone, zmieniamy e-mail użytkownika  
          $tempId = $_SESSION['id'];        
          if ($polaczenie->query("UPDATE users SET username = '$login' WHERE id = '$tempId'"))
          {
            $_SESSION['e_login']="Login został zmieniony!";
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

    if(isset($_POST['zmiana_emaila']))
    { 
      $email = $_POST['email'];
      $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
    
      if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email))
      {
        $wszystko_OK=false;
        $_SESSION['fr_email'] = $email;
        $_SESSION['e_email']="Podaj poprawny adres e-mail!";
      }

      else {
        $_SESSION['email'] = $email;
      }  

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
               
        if ($wszystko_OK==true)
        {
          //Testy zaliczone, zmieniamy e-mail użytkownika  
          $tempId = $_SESSION['id'];        
          if ($polaczenie->query("UPDATE users SET email = '$email' WHERE id = '$tempId'"))
          {
            $_SESSION['e_email']="E-mail został zmieniony!";
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
 
    if(isset($_POST['zmiana_hasla']))
    { 
       $haslo1 = $_POST['haslo1'];
       $haslo2 = $_POST['haslo2'];
    
      if ((strlen($haslo1)<3) || (strlen($haslo1)>20))
      {
        $wszystko_OK=false;
        $_SESSION['fr_haslo1'] = $haslo1;
        $_SESSION['fr_haslo2'] = $haslo2;
        $_SESSION['e_haslo']="Hasło musi posiadać od 3 do 20 znaków!";
      }

      else if ($haslo1!=$haslo2)
      {
        $wszystko_OK=false;
        $_SESSION['fr_haslo1'] = $haslo1;
        $_SESSION['fr_haslo2'] = $haslo2;
        $_SESSION['e_haslo']="Podane hasła nie są identyczne!";
      } 

      else {
        $haslo_hash = password_hash($haslo1, PASSWORD_DEFAULT);
        $_SESSION['haslo'] = $haslo1;
      }

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
          $tempId = $_SESSION['id'];        
          if ($polaczenie->query("UPDATE users SET password = '$haslo_hash' WHERE id = '$tempId'"))
          {
            $_SESSION['e_haslo']="Hasło zostało zmienione!";
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
    <title>Zmiana danych użytkownika</title>
    <link rel="shortcut icon" href="https://img.icons8.com/office/16/000000/money-bag.png"/>
    <!-- <a href="https://icons8.com/icon/43840/money-box">Money Box icon by Icons8</a> money-box--v1.png-->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="main.css">

    <style>
    body {font-family: Arial, Helvetica, sans-serif;}
    * {box-sizing: border-box;}
    h1{color: #C0D06F; text-align: center;}

    .input-container {
      display: -ms-flexbox;
      display: flex;
      width: 100%;
      margin-bottom: 15px;
      height: 40px;
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
      height: 40px;
    }

    .btn:hover {
      opacity: 1;
    }

    #button_color
    {
      background-color: orange;
    }

    .g-recaptcha
    {
      width: 100%;
      margin-bottom: 20px;
    }

    </style>
  </head>
  <body>

  <form method="post" style="max-width:450px; margin:auto">
    <h1>DANE UŻYTKOWNIKA</h1>
    <div class="input-container">
      <i class="fa fa-user icon"></i>
      <input class="input-field" type="text" placeholder="<?php echo $_SESSION['login']?>" value="<?php
      if (isset($_SESSION['fr_login']))
      {        
        echo $_SESSION['fr_login'];
        unset($_SESSION['fr_login']);
      }
    ?>" name="login">
    <input type="hidden" name="zmiana_loginu">
    <button type="submit" class="btn" style="width: 70%; ">Zapisz nowy login</button>

      </div>

       <?php
      if (isset($_SESSION['e_login']))
      {
        echo '<div class="error" style="color:red; font-size: 12px; text-align: center; margin-top: auto; margin-bottom: 10px;">'.$_SESSION['e_login'].'</div>';
        unset($_SESSION['e_login']);
      }
    ?>

  </form>
  <form method="post" style="max-width:450px;margin:auto">
    <div class="input-container">
      <i class="fa fa-envelope icon"></i>
      <input class="input-field" type="text" placeholder="<?php echo $_SESSION['email']?>" value="<?php
      if (isset($_SESSION['fr_email']))
      {
        echo $_SESSION['fr_email'];
        unset($_SESSION['fr_email']);
      }
    ?>" name="email">
      <input type="hidden" name="zmiana_emaila">
     <button type="submit" class="btn" style="width: 70%; ">Zapisz nowy e-mail</button>
    </div>

    <?php
      if (isset($_SESSION['e_email']))
      {
        echo '<div class="error" style="color:red; font-size: 12px; text-align: center; margin-top: auto; margin-bottom: 10px;">'.$_SESSION['e_email'].'</div>';
        unset($_SESSION['e_email']);
      }
    ?>
    </form>

  <form method="post" style="max-width:450px;margin:auto">
    <div class="input-container">
      <i class="fa fa-key icon"></i>
      <input class="input-field" type="password" placeholder="Nowe hasło" value="<?php
      if (isset($_SESSION['fr_haslo1']))
      {
        echo $_SESSION['fr_haslo1'];
        unset($_SESSION['fr_haslo1']);
      }
    ?>" name="haslo1">
    </div>
    
     <div class="input-container">
      <i class="fa fa-key icon"></i>
      <input class="input-field" type="password" placeholder="Powtórz nowe hasło" value="<?php
      if (isset($_SESSION['fr_haslo2']))
      {
        echo $_SESSION['fr_haslo2'];
        unset($_SESSION['fr_haslo2']);
      }
      ?>" name="haslo2">

     <input type="hidden" name="zmiana_hasla">
    <button type="submit" class="btn" style="width: 70%; ">Zapisz nowe hasło</button>
    </div>

      <?php
      if (isset($_SESSION['e_haslo']))
      {
        echo '<div class="error" style="color:red; font-size: 12px; text-align: center; margin-top: auto; margin-bottom: 10px;">'.$_SESSION['e_haslo'].'</div>';
        unset($_SESSION['e_haslo']);
      }
      ?>
    
    <a href="ustawienia.php"><button type="button" class="btn" id="button_color">Powrót do ustawień</button></a> 
  </form>

  </body>
</html>
