<?php require_once "utils.php"; ?>
<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
  <?php $_title = "Main @ Cabbage"; include "inc_head.inc";?>
  <body>
    <div class="container">
      <?php include "inc_navbar.inc"; ?>
      
      <?php
        // flags
        $no_user_flag = $_GET["no_such_user"] == "true";
        $logged_out = isset($_GET["logged_out"]);

        if ($no_user_flag) {
          echo "<div class=\"alert alert-danger\"><strong>Error!
                  </strong> The user does not exist.
                </div>";
        }
        if ($logged_out) {
          echo "<div class=\"alert alert-info\">
                 <strong>Info!</strong> Logged out.
                </div>";
        }
      ?>

      <!-- Tabs -->
      <ul class="nav nav-tabs nav-justified">
        <li role="presentation" class='active'>
          <a href="./dashboard.php"> Events </a>
        </li>
        <li role="presentation">
          <a href="./dashboard_projects.php"> Projects </a>
        </li>
        <li role="presentation">
          <a href="./dashboard_pledges.php"> Pledges </a>
        </li>
        <li role="presentation">
          <a href="./dashboard_profile.php"> Profile </a>
        </li>
      </ul>


      Display current events here

    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>

  </body>
</html>