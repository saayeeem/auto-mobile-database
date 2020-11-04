<?php // Do not put any HTML above this line
session_start();
if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to game.php
    header("Location: index.php");
    return;
}

$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';  // Pw is php123
$failure = false;  // If we have no POST data
if ( isset($_SESSION['failure']) ) {
    $failure = $_SESSION['failure'];

    unset($_SESSION['failure']);
}

// Check to see if we have some POST data, if we do process 0interface
if ( isset($_POST['email']) && isset($_POST['pass']) ) {
    if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 ) {
        $_SESSION['failure'] = "User name and password are required";
        header("Location: login.php");
        return;
    }
    else{
        $pass = htmlentities($_POST['pass']);
        $email = htmlentities($_POST['email']);
        $check = hash('md5', $salt.$pass);
          if ( $check == $stored_hash ) {
            error_log("Login success ".$email);
              // Redirect the browser to game.php
              $_SESSION['name'] = $email;
              header( "Location: index.php" ) ;
              return;
          }
          else {
            error_log("Login fail ".$pass." $check");
            $_SESSION['failure'] = "Incorrect password.";
              header( "Location: login.php" ) ;

              return;
      }
    }
 }


// Fall through into the View
?>
<!DOCTYPE html>
<html>
<head>
<?php require_once "bootstrap.php"; ?>
<title>Mohammad Sayem Chowdhury's Login Page</title>
</head>
<body>
        <div class="container">
            <h1>Please Log In</h1>
                <?php
                    // Note triple not equals and think how badly double
                    // not equals would work here...
                    if ( $failure !== false )
                    {
                        // Look closely at the use of single and double quotes
                        echo(
                            '<p style="color: red;" class="col-sm-10 col-sm-offset-2">'.
                                htmlentities($failure).
                            "</p>\n"
                        );
                    }
                ?>
            <form method="post" class="form-horizontal">
                <div class="form-group">
                    <label class="control-label col-sm-2" for="email">User Name</label>
                    <div class="col-sm-3">
                        <input class="form-control" type="text" name="email" id="email">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="pass">Password:</label>
                    <div class="col-sm-3">
                        <input class="form-control" type="text" name="pass" id="pass">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-2 col-sm-offset-2">
                        <input class="btn btn-primary" type="submit" value="Log In">
                        <input class="btn" type="submit" name="cancel" value="Cancel">
                    </div>
                </div>
            </form>
            <p>
                For a password hint, view source and find a password hint
                in the HTML comments.
                <!-- Hint: The password is the four character sound a cat
                makes (all lower case) followed by 123. -->
            </p>
        </div>
    </body>
</html>
