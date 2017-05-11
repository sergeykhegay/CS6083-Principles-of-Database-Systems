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

      <!-- Tabs -->
      <ul class="nav nav-tabs nav-justified">
        <li role="presentation">
          <a href="./dashboard.php"> Events </a>
        </li>
        <li role="presentation">
          <a href="./dashboard_projects.php"> Projects </a>
        </li>
        <li role="presentation" class='active'>
          <a href="./dashboard_pledges.php"> Pledges </a>
        </li>
        <li role="presentation">
          <a href="./dashboard_profile.php"> Profile </a>
        </li>
      </ul>

      <?php
        $pledges = get_pledges($_SESSION["uid"]);
        if ($pledge == null) {

        }
      ?>

      <table class="table">
        <caption> </caption>
        <tr>
          <th>Project</th>
          <th>Amount</th>
          <th>Date</th>
          <th>Rating</th>
        </tr>
        <?php
          while ($row = pg_fetch_object($pledges)) {
            if ($row->psuccess == 't') {
              $status = "Successful";
            }
            else if ($row->pactive == 'f') {
              $status = "Failed";
            }
            else {
              $status = "Funding";
            }?>
            <tr>
              <td class="col-sm-8 col-md-5">
                <div class="media">
                  <img class="pull-left" src="http://success-at-work.com/wp-content/uploads/2015/04/free-stock-photos.gif" style="width: 150px; height: 120px;"> </a>
                  <div class="media-body">
                    <h4 class="media-heading"><a href="project/?pid=<?=$row->pid?>"><?=$row->ptitle?></a></h4>
                    <h5 class="media-heading"> by <a href="#"><?=$row->uid?></a></h5>
                    <span>Status: </span><span class="text-success"><?=$status?></span>
                    <a href="#"> <button class="btn btn-info btn-xs">details</button></a>
                  </div>
                </div>
              </td>
              <td>$<?=$row->plamount?>.00</td>
              <td><?=substr($row->pldate,0,-7)?></td>
              <td></td>
            </tr>
            <?php
          }
        ?>
        </table>
      


    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>

  

  </body>
</html>