<?php
//echo $_SERVER['SCRIPT_NAME']."<br>";
include('debuggeri.php');
$patternPath = "/(\/[^\/]*)/";
$patterns['password'] = "/^.{16,}$/";
$patterns['firstname'] = "/^[a-zåäöA-ZÅÄÖ'-]*$/";
$patterns['lastname'] = $patterns['firstname']; 
$patterns['mobilenumber'] = "/^[0-9]{7,15}$/";
$patterns['email'] = "/^[\w]+[\w.+-]*@[\w-]+(\.[\w-]{2,})?\.[a-zA-Z]{2,}$/";

$virheilmoitus['firstname'] = "Anna etunimi";
$virheilmoitus['lastname'] = "Anna sukunimi";
$virheilmoitus['mobilenumber'] = "Anna puhelinnumero muodossa 358501234567";
$virheilmoitus['password'] = "Anna vähintään 16 merkkiä pitkä salasana.";
$virheilmoitus['email'] = "Anna sähköpostiosoite oikeassa muodossa.";
$path = preg_replace($patternPath,"../",$_SERVER['SCRIPT_NAME']);
//foreach ($_SERVER AS $k => $v) echo "$k:$v<br>";
//echo "http://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
    
if (!file_exists($path.'tunnukset.php')) {
    debuggeri(basename(__FILE__).",tunnuksia ei löydy, polku:$path");
    exit;
    }
else require($path.'tunnukset.php');
include('config/db.php');
include('posti.php');  
global $success_msg;
global $email_verify_success,$email_verify_err;
$kentat = array('firstname','lastname','email','mobilenumber','password');
$kentat = array_intersect(array_keys($_POST),$kentat);
$errors = [];
$emailExists = false;

function validate($patterns,$kentat){
    /* Lisätään arvoihin validoidut kentat */    
    $validated = true;    
    $arvot = [];
    foreach ($kentat AS $kentta){
      $$kentta = $_POST[$kentta];  
      if (empty($$kentta)) $validated = false;     
      else {
        if ($kentta == 'email'){ 
          if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $validated = false;
          else $arvot['email'] = $email;  
          }  
        else { 
          //debuggeri("$kentta:{$$kentta},".$patterns[$kentta]);
          if (!preg_match($patterns[$kentta],$$kentta)) $validated = false;
          else $arvot[$kentta] = $$kentta;  
          }
        }    
      }
    return array($validated,$arvot);
    }

if (isset($_POST["submit"])) {
    list($validated,$arvot) = validate($patterns,$kentat);
    if ($validated){
        foreach ($arvot AS $kentta => $arvo) {
          $$kentta = mysqli_real_escape_string($connection,$arvo);
          }  
        $query = "SELECT 1 FROM users WHERE email = '$email'"; 
        debuggeri("query:$query");
        $result = mysqli_query($connection, $query);
        $emailExists = mysqli_num_rows($result);
        if (!$emailExists){
          $token = md5(rand().time());
          $password_hash = password_hash($password, PASSWORD_BCRYPT);
          $query = "INSERT INTO users (firstname,lastname,email,mobilenumber,password,token) 
            VALUES ('$firstname','$lastname','$email','$mobilenumber','$password_hash','$token')";
          $result = mysqli_query($connection, $query);
          $id = mysqli_insert_id($connection);  
          if(!$result) die("Tietojen tallentaminen epäonnistui.".mysqli_error($connection));
          // Send verification email
          if($result) {
            $msg = 'Vahvista sähköpostiosoitteesi seuraavasta linkistä:<br><br>
                    <a href="http://localhost/php-user-authentication/user_verification.php?token='.$token.'">Click here to verify email</a>';
            $topic = 'Vahvista sähköpostiosoite!';
            debuggeri("email:$email,msg:$msg,topic:$topic");
            $tulos = posti($email,$msg,$topic);    
            if(!$tulos){
                $query = "DELETE FROM users WHERE id = $id";
                $result = mysqli_query($connection, $query);
                debuggeri($query);
                $email_verify_err = 
                  '<div class="alert alert-danger">
                   Verification email coud not be sent!
                   </div>';
                } 
            else {
                $email_verify_success = 
                  '<div class="alert alert-success">
                   Verification email has been sent!
                   </div>';
                   }
                }
            }
          else {
            /* Sähköpostiosoite on varattu */
            $errors['emailExists'] = 
            '<div class="alert alert-danger" role="alert">
            User with email already exist!
            </div>';
            }  
          }
        else {
          //Lomakekenttien validointi epäonnistui    
          $kentat_i = array_flip($kentat);
          $errors = array_diff_key($kentat_i,$arvot);
          /*
          if (!isset($arvot['firstname'])){
            $errors['firstname'] = true;
            }
          if (!isset($arvot['lastname'])){
            $errors['lastname'] = true;
            }
          if (!isset($arvot['email'])){
            $errors['email'] = true;
            }
          if (!isset($arvot['mobilenumber'])){
            $errors['mobilenumber'] = true;
            }
          if (!isset($arvot['password'])){
            $errors['password'] = true;
            }*/            
        }
    debuggeri($errors); 
    debuggeri($arvot); 
    //debuggeri("firstname:$firstname");  
    }
?>
