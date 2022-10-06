<?php
include('./config/db.php');
include('debuggeri.php');

function myFilter($link,$arvo){
return mysqli_real_escape_string($link,$arvo);      
}

global $email_verified, $email_already_verified, $activation_error;
$token = $_GET['token'] ?? "";
if (!empty($token)) {
    $token = myFilter($connection,$token);
    $query = "SELECT id,is_active FROM users WHERE token = '$token'";
    $result = mysqli_query($connection, $query);
    debuggeri($query);
    $countRow = mysqli_num_rows($result);
    /* Jos löytyi, is_active == 1 tai is_active == 0 */
    if($countRow == 1){
        list($id,$is_active) = mysqli_fetch_row($result);
        if (!$is_active) {
            $query = "UPDATE users SET is_active = 1 WHERE id = $id";
            $result = mysqli_query($connection, $query);
            debuggeri($query);
            if ($result){
                $email_verified = 
                '<div class="alert alert-success">
                User email successfully verified!
                </div>';
                }
            } 
        else {
            $email_already_verified = 
            '<div class="alert alert-danger">
            User email already verified!</div>';
            }
        }
        else {
            $activation_error = 
            '<div class="alert alert-danger">
            Activation error!
            </div>';
        }
    } 
else {
    $activation_error = 
    '<div class="alert alert-danger">
    Activation error!
    </div>';
    }
?>