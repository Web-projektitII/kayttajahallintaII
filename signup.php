<?php include('./controllers/register.php'); ?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <title>PHP User Registration System Example</title>
    <!-- jQuery + Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</head>

<body>
   
   <?php include('./header.php'); ?>

    <div class="App">
        <div class="vertical-center">
            <div class="inner-block">
                <form action="" method="post">
                    <h3>Register</h3>

                    <?php echo $email_verify_success ?>
                    <?php echo $errors['emailExists'] ?? "" ?>

                    <div class="form-group">
                        <label>First name</label>
                        <input type="text" class="form-control" name="firstname" id="firstName" 
                        value="<?php echo (!empty($errors) && !isset($errors['firstname'])) ? htmlentities($_POST['firstname']) : "";?>"
                        pattern="<?php echo trim($patterns['firstname'],"/");?>"/>
                        <?php echo $errors['firstname'] ?? "" ?>
                    </div>

                    <div class="form-group">
                        <label>Last name</label>
                        <input type="text" class="form-control" name="lastname" id="lastName" 
                        value="<?php echo (!empty($errors) && !isset($errors['lastname'])) ? htmlentities($_POST['lastname']) : "";?>"
                        pattern="<?php echo trim($patterns['lastname'],"/");?>"/>
                        <?php echo $errors['lastname'] ?? "" ?>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" id="email" 
                        value = "<?php echo (!empty($errors) && !isset($errors['email'])) ? htmlentities($_POST['email']) : "";?>"/>
                        <?php echo $errors['email'] ?? "" ?>
                    </div>

                    <div class="form-group">
                        <label>Mobile</label>
                        <input type="text" placeholder="358501234567" class="form-control" name="mobilenumber" id="mobilenumber" 
                        value = "<?php echo (!empty($errors) && !isset($errors['mobilenumber'])) ? htmlentities($_POST['mobilenumber']) : "";?>"/>
                        <?php echo $errors['mobilenumber'] ?? "" ?>
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control" name="password" id="password" 
                        value = "<?php echo (!empty($errors) && !isset($errors['password'])) ? htmlentities($_POST['password']) : "";?>"/>
                        <?php echo $errors['password'] ?? "" ?>
                    </div>

                    <button type="submit" name="submit" id="submit" class="btn btn-outline-primary btn-lg btn-block">Sign up
                    </button>
                </form>
            </div>
        </div>
    </div>

</body>

</html>