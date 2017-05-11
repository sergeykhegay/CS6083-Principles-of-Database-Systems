<?php require_once "./utils.php"; ?>
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
  <?php $_title = "User Profile @ Cabbage"; include "./inc_head.inc";?>
  <body>
    <div class="container">
      <?php include "./inc_navbar.inc"; ?>

      <?php
        $visitor = $_SESSION["uid"];

        // flags
        $visitor_logged_in = isset($visitor) && !empty($visitor);

        // Profile user id
        $uid = test_input($_GET["uid"]);
        $user_info = get_user($uid);
        $project = get_user_project($uid);

        $follows = follow_exists($visitor, $uid);
      ?>
      

<!-- USER INFO -->
      <div class="row">
        <div class="col-sm-4 col-md-4">
        </div>
        <div class="col-sm-4 col-md-4 user-details text-center">
          <div class="user-image">
            <img src="http://success-at-work.com/wp-content/uploads/2015/04/free-stock-photos.gif" class="img-circle" width="150" height="150">
          </div>
          <div class="user-info-block">
            <div class="user-heading">
              <h3><?=$user_info->uname?></h3>
              <span class="help-block"><?=$user_info->uid?></span>
            </div>
            <div class="user-body">
              <div class="tab-content">
                <div id="information" class="tab-pane active">
                  <!-- <h4>Account Information</h4> -->
                  <p>City: <?=$user_info->ucity?> </p>
                  <p>Interests: <?=$user_info->uinterests?> </p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-4 col-md-4">
        </div>
      </div>
      
      <div class="row">
        <div class="col-sm-4 col-md-4">
        </div>
        <div class="col-sm-4 col-md-4 center-block">
        <?php if ($visitor_logged_in) { ?>
          <input id="uid1Input" type="hidden" value="<?=$visitor?>">
          <input id="uid2Input" type="hidden" value="<?=$uid?>">
          <button id="followButton" class="btn btn-primary btn-lg btn-block <?php if ($follows) echo 'hidden' ?>" 
                  type="button">Follow</button>
          <button id="unfollowButton" class="btn btn-danger btn-lg btn-block <?php if (!$follows) echo 'hidden' ?>" 
                  type="button">Unfollow</button>
        <?php } else { ?>
          <a class="btn btn-primary" href="./user_login.php" type="button" style="align:bottom;display:block;width:70px">Login</a>
        <?php } ?>
        </div>
        <div class="col-sm-4 col-md-4">
        </div>
      </div>


<!-- CREATED PROJECTS -->
      <div class="page-header">
        <h2>Created Projects <small>There might be nothing but hope...</small></h2>
      </div>

      <table class="table">
        <tr>
          <th>Project</th>
          <th>Info</th>
          <th>Action</th>
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
              <a href="./project.php?pid=<?=$project_info->pid ?>">
                <button class="btn btn-info btn-sm">View</button>
              </a>
            </td>
          </tr>
        <?php
          }
        ?>
      </table>



<!-- SUPPORTED PROJECTS -->
      <div class="page-header">
        <h2>Supported Projects <small>From heart to heart...</small></h2>
      </div>

      <table class="table">
        <tr>
          <th>Project</th>
          <th>Info</th>
          <th>Action</th>
        </tr>
        <?php
          $supported = get_user_supported_projects($uid);
          while ($project_info = pg_fetch_object($supported)) {
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
              <a href="./project.php?pid=<?=$project_info->pid ?>">
                <button class="btn btn-info btn-sm">View</button>
              </a>
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
    <link rel="stylesheet" type="text/css" href="grid_layout.css">


    <script type="text/javascript">
    $(document).ready(function() {
      $('#followButton').on('click', function() {
        var data = new FormData();
        data.append('action', 'follow');
        data.append('uid1', $('#uid1Input').val());
        data.append('uid2', $('#uid2Input').val());
      
        $.ajax({
          type: 'POST',               
          processData: false, // important
          contentType: false, // important
          data: data,
          url: "./follows_handler.php",
          dataType : 'json',
          success: function(data, textStatus, jqXHR) {
            console.log(data);
            if (typeof data.error === 'undefined') {
              $('#followButton').addClass("hidden");
              $('#unfollowButton').removeClass("hidden");
            } else {
              console.log('ERRORS success: ' + data.error);
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
            console.log('ERRORS error: ' + textStatus);
          }
        }); 

        return false; 
      });

      // Unlike
      $('#unfollowButton').on('click', function() {
        var data = new FormData();
        data.append('action', 'unfollow');
        data.append('uid1', $('#uid1Input').val());
        data.append('uid2', $('#uid2Input').val());
      
        $.ajax({
          type: 'POST',               
          processData: false, // important
          contentType: false, // important
          data: data,
          url: "./follows_handler.php",
          dataType : 'json',
          success: function(data, textStatus, jqXHR) {
            console.log(data);
            if (typeof data.error === 'undefined') {
              $('#followButton').removeClass("hidden");
              $('#unfollowButton').addClass("hidden");
            } else {
              console.log('ERRORS success: ' + data.error);
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
            console.log('ERRORS error: ' + textStatus);
          }
        }); 

        return false; 
      }); 
    });
    </script>
  </body>
</html>