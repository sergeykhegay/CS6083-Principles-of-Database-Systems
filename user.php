<?php require_once "./utils.php"; ?>
<?php
  session_start();
  require_once "./require_login.php"; 
?>
<!DOCTYPE html>
<html lang="en">
  <?php $_title = "User Profile @ Cabbage"; include "./inc_head.inc";?>
  <body>
    <div class="container">
      <?php include "./inc_navbar.inc"; ?>

      <?php 
        $uid = $_GET["uid"];
        $user_info = get_user($uid);
        $project = get_user_project($uid);
      ?>
      <div class="container">
          <div class="row">
            <div class="col-sm-4 col-md-4 user-details">
              <div class="user-image">
                <img src="http://success-at-work.com/wp-content/uploads/2015/04/free-stock-photos.gif" class="img-circle" width="150" height="150">
              </div>
              <div class="user-info-block">
                <div class="user-heading">
                  <h3><?=$user_info->uname?></h3>
                  <span class="help-block"><?=$user_info->uid?></span>
               </div>
                 <!--  <ul class="navigation">
                    <li class="active">
                      <a data-toggle="tab" href="#information">
                        <span class="glyphicon glyphicon-user"></span>
                      </a>
                    </li>
                    <li>
                      <a data-toggle="tab" href="#likes">
                        <span class="glyphicon glyphicon-thumbs-up"></span>
                      </a>
                    </li>
                    <li>
                      <a data-toggle="tab" href="#email">
                        <span class="glyphicon glyphicon-envelope"></span>
                      </a>
                    </li>
                    <li>
                      <a data-toggle="tab" href="#events">
                        <span class="glyphicon glyphicon-calendar"></span>
                      </a>
                    </li>
                  </ul> -->
                  <div class="user-body">
                    <div class="tab-content">
                      <div id="information" class="tab-pane active">
                        <!-- <h4>Account Information</h4> -->
                        <p>City: <?=$user_info->ucity?> </p>
                        <p>Interest: <?=$user_info->uinterests?> </p>
                      </div>
                      <div id="likes" class="tab-pane active">
                        
                      </div>
                      <div id="email" class="tab-pane">
                        <h4>Send Message</h4>
                      </div>
                      <div id="events" class="tab-pane">
                        <h4>Events</h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
          </div>
        </div>

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
              <button class="btn btn-primary btn-sm">update</button> </a>
              <button onclick="changePidTo(<?=$project_info->pid?>);" type="button" class="btn btn-primary btn-sm <?=$disable?>" 
              <?php if($disable == 'active'){ echo "data-toggle=\"modal\" data-target=\"#myModal\"";}?> >Cancel Project</button>
            </td>
          </tr>
        <?php
          }
        ?>
      </table>
        



    </div>  <!-- container --> 
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>

  </body>
</html>