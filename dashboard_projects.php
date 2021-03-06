<?php require_once "utils.php"; ?>
<?php 
  session_start(); 
  require_once "require_login.php"; 
?>
<!DOCTYPE html>
<html lang="en">
  <?php $_title = "Events Dashboard @ Cabbage"; include "./inc_head.inc";?>
  <body>
    <div class="container">
      <?php include "./inc_navbar.inc"; ?>
      
      <?php
        $no_user_flag = $_GET["no_such_user"] == "true";
        if ($no_user_flag) {
          echo "<div class=\"alert alert-danger\"><strong>Error!</strong> The user does not exist.</div>";
        }
      ?>
      
      <!-- Tabs -->
      <ul class="nav nav-tabs nav-justified">
        <li role="presentation">
          <a href="./dashboard.php"> Events </a>
        </li>
        <li role="presentation" class='active'>
          <a href="./dashboard_projects.php"> Projects </a>
        </li>
        <li role="presentation">
          <a href="./dashboard_pledges.php"> Pledges </a>
        </li>
      </ul>

      <?php
        $is_cancelled = isset($_GET["cancel"]);
        if($is_cancelled) {
          echo "<div class=\"alert alert-success\">
                  <strong>Project cancelled successfully!</strong> 
                </div>";
          $pid=test_input($_GET["pid"]);
          cancel_project($pid);
        }
        $project = get_user_project(test_input($_SESSION["uid"]));
      ?>
      <table class="table">
        <caption> </caption>
        <tr>
          <th>Project</th>
          <th>Info</th>
          <th></th>
        </tr>
        <?php
          while ($project_info = pg_fetch_object($project)) {
            $disable = "active";
            if ($project_info->psuccess == 't') {
              $status = "Successful";
              $disable = "disabled";
            }
            else if ($project_info->pactive == 'f') {
              if($project_info->pcancelled == 't'){
                $status = "Cancelled";
              }
              else{
                $status = "Failed";
              }
              $disable = "disabled";
            }
            else {
              $status = "Funding";
            }
        ?>
          <tr>
            <td class="col-sm-8 col-md-5">
              <div class="media">
                <img class="pull-left" src=<?=$project_info->pimage?> style="width: 150px; height: 120px;"> </a>
                <div class="media-body">
                  <h4 class="media-heading"><a href="./project.php?pid=<?=$project_info->pid?>"><?=$project_info->ptitle?></a></h4>
                  <h5 class="media-heading"> by <a href="#"><?=$project_info->uid?></a></h5>
                  <span>Status: </span><span class="text-success"><?=$status?></span>
                </div>
              </div>
            </td>
            <td>
              Created on <?=substr($project_info->pstartdate, 0, 19)?>
              <br><strong>Maximum required Amount:</strong> $<?=$project_info->pmaxamount?>.00</br>
              <strong>Current Fund:</strong> $<?=$project_info->pcurrentamount?>.00
            </td>
            <td>
              <a href="./project_update.php?pid=<?=$project_info->pid ?>">
              <button class="btn btn-primary btn-sm">Update</button> </a>
              <button onclick="changePidTo(<?=$project_info->pid?>);" type="button" class="btn btn-primary btn-sm <?=$disable?>" 
              <?php if($disable == 'active'){ echo "data-toggle=\"modal\" data-target=\"#myModal\"";}?> >Cancel Project</button>
            </td>
          </tr>
        <?php
          }
        ?>
      </table>

      <div id="myModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Cancel project</h4>
          </div>
          <div class="modal-body">
            <p>Are you sure to cancel this project?</p>
          </div>
          <div class="modal-footer">
            <form id="cancelForm" method="post" action="./dashboard_projects.php?cancel=true">
              <input  type="submit" value = "Cancel Project" class="btn btn-default">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </form>
          </div>
        </div>

      </div>
    </div>

    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>

    <script type="text/javascript">
      var changePidTo = null;

      $(document).ready(function() {
        changePidTo = function(pid) {
          $("#cancelForm").attr("action", "./dashboard_projects.php?cancel=true&pid=" + pid);
        };
      });
    </script>

  </body>
</html>