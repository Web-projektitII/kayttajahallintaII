<?php 
include('./controllers/register.php');
/* 
LOMAKKEEN SELITYKSET 
1 Muotoilu, 2 selaimen validointi, 3 palvelimen validointi,
4 yhtenäiset virheilmoitukset, 5 kenttien arvon muistaminen

Tässä on Bootstrap 4 lomakkeen muotoiluun.
Bootstrap edellyttää novalidate -määritystä, se
estää HTML5-virheilmoitusten näyttämisen. 
class needs-validation osoittaa ne lomakkeet,
joille javascriptilla lisätään class was-validated. Sitä
edellyttää ja se käynnistää HTML5-validoinnin.

LOMAKKEEN MÄÄRITYS
<form novalidate class="needs-validation" action="" method="post">

KENTÄN MÄÄRITYS
<div class="form-group">
    <label>First name</label>
    // class is-invalid
    <input type="text" required class="form-control<?php if(isset($errors['firstname'])) echo " is-invalid";?>" 
        name="firstname" id="firstname" 
    // pattern on sama kuin palvelimella    
        pattern="<?php echo trim($patterns['firstname'],"/");?>"
    // oninput poistaa is-invalid -classin ja oninput-attribuutin
        <?php if(isset($errors['firstname'])) echo "oninput=\"removeServerError(this)\"";?>
    // oikein annettu arvo säilytetään
        value="<?php echo (!empty($errors) && !isset($errors['firstname'])) ? htmlentities($_POST['firstname']) : "";?>"
        />
    // sama virheilmoitus selaimelta ja palvelimelta invalid-feedback -div-elementtiin    
    <div class="invalid-feedback">
    <?php echo $virheilmoitus['firstname'];?>
    </div>
*/    
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>PHP User Registration System Example</title>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>

 <!--
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.6.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
-->
<link rel="stylesheet" href="./css/style.css">
<script>
removeServerError = e => { 
    /*if (e.classList.contains('is-invalid')) 
        e.classList.remove('is-invalid');*/
    e.classList.remove('is-invalid');    
    e.removeAttribute('oninput');
    }    
</script>    
</head>

<body>
<?php include('./header.php'); ?>
<div class="App">
<div class="vertical-center">
<div class="inner-block">
<h3>Register</h3>  
<?php echo $email_verify_success; ?>
<?php echo $email_verify_err; ?>
<?php echo $errors['emailExists'] ?? "" ?>

<form novalidate class="needs-validation" action="" method="post">
<div class="form-group">
    <label>First name</label>
    <input type="text" required class="form-control<?php if(isset($errors['firstname'])) echo " is-invalid";?>" 
        name="firstname" id="firstname" 
        pattern="<?php echo trim($patterns['firstname'],"/");?>"
        <?php if(isset($errors['firstname'])) echo "oninput=\"removeServerError(this)\"";?>
        value="<?php echo (!empty($errors) && !isset($errors['firstname'])) ? htmlentities($_POST['firstname']) : "";?>"
        />
    <div class="invalid-feedback">
    <?php echo $virheilmoitus['firstname'];?>
    </div>
</div>
<div class="form-group">
    <label>Last name</label>
    <input type="text" required class="form-control<?php if(isset($errors['lastname'])) echo " is-invalid";?>" 
        name="lastname" id="lastname" 
        pattern="<?php echo trim($patterns['lastname'],"/");?>"
        <?php if(isset($errors['lastname'])) echo "oninput=\"removeServerError(this)\"";?>
        value="<?php echo (!empty($errors) && !isset($errors['lastname'])) ? htmlentities($_POST['lastname']) : "";?>"
        />
    <div class="invalid-feedback">
    <?php echo $virheilmoitus['lastname'];?>
    </div>
</div>
<div class="form-group">
    <label>Email</label>
    <input type="email" required class="form-control<?php if(isset($errors['email'])) echo " is-invalid";?>" 
        name="email" id="email" 
        pattern="<?php echo trim($patterns['email'],"/");?>"
        <?php if(isset($errors['email'])) echo "oninput=\"removeServerError(this)\"";?>
        value="<?php echo (!empty($errors) && !isset($errors['email'])) ? htmlentities($_POST['email']) : "";?>"
        />
    <div class="invalid-feedback">
    <?php echo $virheilmoitus['email'];?>
    </div>
</div>
<div class="form-group">
    <label>Mobile</label>
    <input type="tel" required class="form-control<?php if(isset($errors['mobilenumber'])) echo " is-invalid";?>" 
        name="mobilenumber" id="mobilenumber" 
        pattern="<?php echo trim($patterns['mobilenumber'],"/");?>"
        <?php if(isset($errors['mobilenumber'])) echo "oninput=\"removeServerError(this)\"";?>
        value="<?php echo (!empty($errors) && !isset($errors['mobilenumber'])) ? htmlentities($_POST['mobilenumber']) : "";?>"
        />
    <div class="invalid-feedback">
    <?php echo $virheilmoitus['mobilenumber'];?>
    </div>
</div>
<div class="form-group">
    <label>Password</label>
    <input type="password" required class="form-control<?php if(isset($errors['password'])) echo " is-invalid";?>" 
        name="password" id="password" 
        pattern="<?php echo trim($patterns['password'],"/");?>"
        <?php if(isset($errors['password'])) echo "oninput=\"removeServerError(this)\"";?>
        value="<?php echo (!empty($errors) && !isset($errors['password'])) ? htmlentities($_POST['password']) : "";?>"
        />
    <div class="invalid-feedback">
    <?php echo $virheilmoitus['password'];?>
    </div>
</div>
<button type="submit" name="submit" id="submit" class="btn btn-outline-primary btn-lg btn-block">Sign up
</button>
</form>
</div>
</div>
</div>
</body>
</html>
<script>
// Example starter JavaScript for disabling form submissions if there are invalid fields
(function() {
    'use strict';
    window.addEventListener('load', function() {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
            if (form.checkValidity() === false) {
              event.preventDefault();
              event.stopPropagation();
            }
            form.classList.add('was-validated');
          }, false);
        });
    }, false);
})();
</script>