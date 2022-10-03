<?php
    //echo $_SERVER['SCRIPT_NAME']."<br>";
    include('debuggeri.php');
    $patternPath = "/(\/[^\/]*)/";
    $patterns['password'] = "/^.{16,}$/";
    $patterns['firstname'] = "/^[\p{Latin}'-]*$/";
    $patterns['lastname'] = $patterns['firstname']; 
    $patterns['mobilenumber'] = "/^[0-9]{7,15}+$/";

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
    global $success_msg, $email_exist, $f_NameErr, $l_NameErr, $_emailErr, $_mobileErr, $_passwordErr;
    global $fNameEmptyErr, $lNameEmptyErr, $emailEmptyErr, $mobileEmptyErr, $passwordEmptyErr, $email_verify_err, $email_verify_success;
    $kentat = array('firstname','lastname','email','mobilenumber','password');
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
        if (!$mailExists){
          $token = md5(rand().time());
          $password_hash = password_hash($password, PASSWORD_BCRYPT);
          $query = "INSERT INTO users (firstname,lastname,email,mobilenumber,password,token,is_active) 
            VALUES ('$firstname','$lastname','$email','$mobilenumber','$password_hash','$token',0)";
          $result = mysqli_query($connection, $query);
          $id = mysqli_insert_id($connection);  
          if(!$result) die("Tietojen tallentaminen epäonnistui.".mysqli_error($connection));
          // Send verification email
          if($result) {
            $msg = 'Vahvista sähköpostiosoitteesi seuraavasta linkistä:.<br><br>
                    <a href="http://localhost/php-user-authentication/user_verification.php?token='.$token.'">Click here to verify email</a>';
            $topic = 'Vahvista sähköpostiosoite!';
            debuggeri("email:$email,msg:$msg,topic:$topic");
            $tulos = posti($email,$msg,$topic);    
            if(!$tulos){
                $query = "DELETE FROM users WHERE id = $id";
                $result = mysqli_query($connection, $query);
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
          if (!isset($arvot['firstname'])){
            $errors['firstname'] = 
              '<div class="alert alert-danger">
               Anna etunimi.
               </div>';
            }
          if (!isset($arvot['lastname'])){
            $errors['lastname'] = 
              '<div class="alert alert-danger">
              Anna sukunimi.
              </div>';
            }
          if (!isset($arvot['email'])){
            $errors['email'] = 
                '<div class="alert alert-danger">
                Anna sähköpostiosoite oikeassa muodossa.
                </div>';
            }
          if (!isset($arvot['mobilenumber'])){
            $errors['mobilenumber'] = 
              '<div class="alert alert-danger">
               Anna puhelinnumero muodossa 358501234567.
               </div>';
            }
          if (!isset($arvot['password'])){
            $errors['password'] = 
                '<div class="alert alert-danger">
                Anna vähintään 16 merkkiä pitkä salasana.
                </div>';
            }            
        }
    debuggeri($errors); 
    debuggeri($arvot); 
    //debuggeri("firstname:$firstname");  
    }
?>
