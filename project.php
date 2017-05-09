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
        $project = get_project_info($pid);

        // flags
        $user_logged_in = isset($_SESSION["uid"]);
        $project_exists = isset($project);

        if ($project_exists) {
          $image = $project["pimage"];
          $title = $project["ptitle"];
          $description = $project["pdescription"];
          $uid = $project["uid"];

        }
        // messages
        // if (!$user_logged_in) {
        //   echo "<div class=\"alert alert-info\"><strong>Info!</strong> Logged out.</div>";
        // }

      ?>

      <!-- Image jumbotron -->
      <div class="jumbotron" style="background: url('<?php echo "$image"; ?>') no-repeat center center;
                                    vertical-align: text-bottom;
                                    text-align: center; 
                                    font-weight: bold;
                                    color: white;
                                    height: 250px">
      </div>
      
      
      <!-- List project -->
      <div class="page-header">
            <h1><?php echo "$title"; ?> <small>created by <a href="./user.php?uid=<?=$uid ?>"><?=$uid ?></a></small></h1>
          </div>
      <div class="row show-grid">
        
        <div class="col-md-6">
          
          <div>
            <?php echo "$description" ?> Expanding on the CSS provided by user2136179, you can also do bottom borders. It requires using matchHeight but can get your Bootstrap Grid looking like a table grid. Check it out
          </div>
        </div>

        <div class="col-md-6">
          
          <div>
            <?php echo "$description" ?>
          </div>
        </div>
      </div>




    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>

  </body>
</html>