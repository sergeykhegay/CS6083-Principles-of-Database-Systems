<?php require_once "utils.php"; ?>
<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
  <?php $_title = "Main @ Cabbage"; include "inc_head.inc";?>
  <body>
    <div class="container">
      <?php include "inc_navbar.inc"; ?>
      
      <?php
        $no_user_flag = $_GET["no_such_user"] == "true";
        if ($no_user_flag) {
          echo "<div class=\"alert alert-danger\"><strong>Error!</strong> The user does not exist.</div>";
        }
      ?>
      <?php
        if (isset($_GET["logged_out"])) {
          echo "<div class=\"alert alert-info\"><strong>Info!</strong> Logged out.</div>";
        }
      ?>

      <a href="./dashboard.php"> Events </a>
      <a href="./dashboard_projects.php"> Projects </a>
      <a href="./dashboard_pledges.php"> Pledges </a>
      <a href="./dashboard_profile.php"> Profile </a>
      
      Display user profile here.

    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>

  </body>
</html>