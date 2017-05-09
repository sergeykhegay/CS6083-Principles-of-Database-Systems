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

      <?php
        $pledges = get_pledges($_SESSION["uid"]);
        if($pledge == null){

        }
      ?>
      

      <div class="container">
        <table class="table">
          <caption> </caption>
        <tr>
          <<!-- th>Project</th>
          <th>Amount</th>
          <th>Date</th>
          <th>Status</th>
          <th>Rating</th>
 -->        </tr>
        <?php
        while($row = pg_fetch_row($pledges)){
            echo "<tr>";
            echo "<td>" . $row[9] . "</td>";
            echo "<td>" . $row[3] . "</td>";
            echo "<td>" . $row[5] . "</td>";
            echo "<td>" . $row[15] . "</td>";
        }
        ?>
        <td><form action="/~justine/foo/buy.php" method = "post">
        <input type="submit" value = "Rate Now" class="btn btn-default">
        <input type='hidden' name='product_name' value='<?php echo "$product";?>'/> 
    </form></td>
       <!-- </tr> -->
        </table>
      </div>

    <label for="input-1" class="control-label">Rate This</label>
        <input id="input-1" name="input-1" class="rating rating-loading" data-min="0" data-max="5" data-step="1">

    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>

  </body>
</html>