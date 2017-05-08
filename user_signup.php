<?php require_once "./utils.php"; ?>
<!DOCTYPE html>
<html lang="en">
  <?php $_title = "Sign Up @ Cabbage"; include "./inc_head.inc";?>
  <body>
    <div class="container">
      <?php include "./inc_navbar.inc"; ?>

      <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
          // data
          $email = test_input($_POST["email"]);
          $password = test_input($_POST["password"]);
          $password2 = test_input($_POST["password2"]);

          
          // flags
          $email_empty = empty($_POST["email"]);
          $password_empty = empty($_POST["password"]);
          $password2_empty = empty($_POST["password2"]);
          $passwords_match = (strcmp($password, $password2) === 0);
          $fillin_form = true;


          // Display errors
          if ($email_empty) {
            echo "<div class=\"alert alert-danger\"><strong>Error!</strong> Field 'email' cannot be empty.</div>";
          }
          if ($password_empty) {
            echo "<div class=\"alert alert-danger\"><strong>Error!</strong> Field 'password' cannot be empty.</div>";
          }
          if ($password2_empty) {
            echo "<div class=\"alert alert-danger\"><strong>Error!</strong> Field 're-type password' cannot be empty.</div>";
          }
          if (!$passwords_match) {
            echo "<div class=\"alert alert-danger\"><strong>Error!</strong> Passwords do not match.</div>";
          }


          // Try creating an account
          if (!$email_empty && !$password_empty && $passwords_match) {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $insert_failed = !insert_user($email, $password_hash);
            if ($insert_failed) {
              echo "<div class=\"alert alert-danger\">
                      <strong>Error!</strong> An account with email $email already exists! You can login <a href='./user_login.php'>here</a>.
                    </div>";
            } else {
              echo "<div class=\"alert alert-success\">
                      <strong>Success!</strong> Your account for $email was created. You can login <a href='./user_login.php'>here</a>.
                    </div>";
            }
            // Prevent form refill
            $fillin_form = false;
          }
        }
      ?>
      
      <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <!-- login -->
        <div class="form-group row">
          <label for="inputEmail" class="col-sm-2 col-form-label">Email *</label>
          <div class="col-sm-10">
            <input type="email" class="form-control" id="inputEmail" 
                   aria-describedby="emailHelp" placeholder="Enter email" name="email"
                   <?php if (!$email_empty && $fillin_form) echo "value=\"$email\"";?>
            >
            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
          </div>
        </div>

        <!-- password -->
        <div class="form-group row <?php if ($password_empty) echo "has-danger"?>">
          <label for="inputPassword" class="col-sm-2 col-form-label">Password *</label>
          <div class="col-sm-10">
            <input type="password" class="form-control" id="inputPassword" 
                   placeholder="Enter password" name="password"
                   <?php if (!$password_empty && $fillin_form) echo "value=\"$password\"";?>
            >
          </div>
          
        </div>

        <!-- retype password -->
        <div class="form-group row">
          <label for="inputPassword2" class="col-sm-2 col-form-label">Re-type Password *</label>
          <div class="col-sm-10">
            <input type="password" class="form-control" id="inputPassword2" 
                   placeholder="Re-type password" name="password2"
                   <?php if (!$password2_empty && $fillin_form) echo "value=\"$password2\"";?>
            >
          </div>
        </div>

        <div class="form-group row">
          <div class="offset-sm-2 col-sm-10">
            <button type="submit" class="btn btn-primary">Sign me up!</button>
          </div>
        </div>
      </form>

    </div>  <!-- container --> 
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>

  </body>
</html>