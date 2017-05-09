<?php require_once "utils.php"; ?>
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
  <?php $_title = "Main @ Cabbage"; include "inc_head.inc";?>
  <body>
    <div class="container">
      <?php include "inc_navbar.inc"; ?>
      
      <?php
        // data
        $pid = test_input($_GET["pid"]);
        $project = get_project($pid);

        // flags
        $user_logged_in = isset($_SESSION["uid"]);
        $project_exists = isset($project);

        if ($project_exists) {
          $pimage = $project["pimage"];
          $ptitle = $project["ptitle"];
        }
        // messages
        // if (!$user_logged_in) {
        //   echo "<div class=\"alert alert-info\"><strong>Info!</strong> Logged out.</div>";
        // }

      ?>

      <!-- Image jumbotron -->
      <div class="jumbotron" style="background: url('<?php echo "$pimage"; ?>') no-repeat center center;
                                    vertical-align: text-bottom;
                                    text-align: center; 
                                    font-weight: bold;
                                    color: white;
                                    height: 250px">
      </div>
      
      
      <!-- List project -->
      <div class="page-header">
        <h1><?php echo "$ptitle"; ?></h1>
      </div>

    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>

  </body>
</html>