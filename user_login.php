<?php require_once "./utils.php"; ?>
<!DOCTYPE html>
<html lang="en">
  <?php $_title = "Sign Up @ Cabbage"; include "./inc_head.inc";?>
  <body>
    <div class="container">
      <?php include "./inc_navbar.inc"; ?>

      <?php
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
          // flags
          $login_required = isset($_GET["login_required"]);
          $email_empty = isset($_GET["email_empty"]);
          $password_empty = isset($_GET["password_empty"]);
          $login_failed = isset($_GET["login_failed"]);

          // Display errors
          if ($login_required) {
            echo "<div class=\"alert alert-warning\">
                   <strong>Warning!</strong> You need to login first.
                  </div>";
          }
          if ($email_empty) {
            echo "<div class=\"alert alert-danger\">
                   <strong>Error!</strong> Field 'email' cannot be empty.
                  </div>";
          }
          if ($password_empty) {
            echo "<div class=\"alert alert-danger\">
                   <strong>Error!</strong> Field 'password' cannot be empty.
                  </div>";
          }
          if ($login_failed) {
            echo "<div class=\"alert alert-danger\">
                    <strong>Error!</strong> Forgot your password? Well, we can do nothing... Or maybe you forgot to sign up <a href=\"./user_signup.php\">here</a>?
                  </div>";
          }
        }
      ?>
      
      <form method="post" action="./user_login_handler.php">
        <!-- login -->
        <div class="form-group row">
          <label for="inputEmail" class="col-sm-2 col-form-label">Email *</label>
          <div class="col-sm-10">
            <input type="email" class="form-control" id="inputEmail" 
                   aria-describedby="emailHelp" placeholder="Enter email" name="email"
                   <?php if (!$email_empty && $fillin_form) echo "value=\"$email\"";?>
            >
          </div>
        </div>

        <!-- password -->
        <div class="form-group row">
          <label for="inputPassword" class="col-sm-2 col-form-label">Password *</label>
          <div class="col-sm-10">
            <input type="password" class="form-control" id="inputPassword" 
                   placeholder="Enter password" name="password"
            >
          </div>
        </div>

        <div class="form-group row">
          <div class="offset-sm-2 col-sm-10">
            <button type="submit" class="btn btn-primary">Log in!</button>
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