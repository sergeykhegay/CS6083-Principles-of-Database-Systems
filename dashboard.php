<?php require_once "utils.php"; ?>
<?php session_start(); ?>
<?php 
  session_start(); 
  require_once "require_login.php";
?>
<!DOCTYPE html>
<html lang="en">
  <?php $_title = "Main @ Cabbage"; include "inc_head.inc";?>
  <body>
    <link rel="stylesheet" type="text/css" href="grid_layout.css">
    <div class="container">
      <?php include "inc_navbar.inc"; ?>
      
      <?php
        $uid = test_input($_SESSION["uid"]);
        $user = get_user($uid);
        // flag
        $mark_read = isset($_GET["mark_read"]);

        if ($mark_read) {
          user_update_umarkreaddate($uid);
        }

        $last_marked_read = get_umarkreaddate($uid);
        $events = get_events_from($uid, $last_marked_read);
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
      </ul>
      
      <hr/>


      <div class="row">
        <div class="col-md-6">

          <div class="card" style="margin-bottom:20px">
            <div class="card-block">
              <h4 class="card-title">Your Profile</h4>
            </div>
            <div class="card-block">
              <form class="form-horizontal">
                <div class="form-group row">
                  <div class="col-md-4">
                    <label>Username</label>
                  </div>
                  <div class="col-md-8">
                    <input type="text" class="form-control text-center" value="<?=$uid?>" style="background:transparent;border:0px" readonly>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-4">
                    <label>City</label>
                  </div>
                  <div class="col-md-8">
                    <input id="uidInput" type="hidden" value="<?=$uid?>">
                    <input id="cityInput" type="text" class="form-control text-center" 
                           value="<?=$user->ucity;?>" style="background:transparent;border:0px" placeholder="No city" readonly>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-4">
                    <label>Interests</label>
                  </div>
                  <div class="col-md-8">
                    <textarea id="interestInput" type="text" class="form-control text-center" rows=4 
                              style="background:transparent;border:0px"  placeholder="No interests" readonly><?=$user->uinterests;?></textarea>
                  </div>
                </div>

                <hr />

                <div class="row">
                  <div id="editGroup" class="col-md-12">
                    <button id="editButton" type="button" class="btn btn-success">Edit</button>
                  </div>
                  <div id="saveGroup" class="col-md-12" style="display: none;">
                    <button id="cancelButton" type="button" class="btn btn-danger">Cancel</button>
                    <button id="saveButton" type="button" class="btn btn-success">Save</button>
                  </div>
                </div>
              </form>
              
      
            </div>
          </div>
        </div>


        <div class="col-md-6">
          <form action="./dashboard.php" method="GET">
            <input type="hidden" name="mark_read" value="true">
            <button type="submit" class="btn btn-primary btn-lg btn-block" style="margin-bottom:12px">Mark all as read</button>
          </form>
          <?php if ($mark_read) { ?>
            <div class="alert alert-info text-center" style="margin-bottom:12px">
                <strong>Info!</strong> Your feed was marked as read.
            </div>
          <?php } ?>

          <?php 
            if ($events) {
              foreach ($events as $event) {
                $u = $event["uid"];
                $id1 = $event["id1"];
                $id2 = $event["id2"];
                $context = $event["context"];
                $string = null;

                switch ($event["action"]) {
                  case "follow": 
                    $string = "<strong><a href='./user.php?uid=$u'>$u</a></strong> started to <span class='label label-info'>follow</span> <strong><a href='./user.php?uid=$id1'>$id1</a></strong>.";
                    break;
                  case "like":
                    $string = "<strong><a href='./user.php?uid=$u'>$u</a></strong> <span class='label label-success'>liked</span> project <a href='./project.php?pid=$id1'>'$context'</a>.";
                    break;
                  case "unlike":
                    $string = "<strong><a href='./user.php?uid=$u'>$u</a></strong> <span class='label label-danger'>unliked</span> project <a href='./project.php?pid=$id1'>'$context'</a>.";
                    break;
                  case "comment":
                    $string = "<strong><a href='./user.php?uid=$u'>$u</a></strong> <span class='label label-warning'><a href='./project.php?pid=$id1#$id2'>commented</a></span> on the project <a href='./project.php?pid=$id1'>'$context'</a>.";
                    break;
                  case "pledge":
                    $string = "<strong><a href='./user.php?uid=$u'>$u</a></strong> <span class='label label-success'>pledged</span> to the project <a href='./project.php?pid=$id1'>'$context'</a>.";
                    break;
                  case "unpledge":
                    $string = "Sadly, <strong><a href='./user.php?uid=$u'>$u</a></strong> <span class='label label-danger'>unpledged</span> the project <a href='./project.php?pid=$id1'>'$context'</a>.";
                    break;
                  case "update": 
                    $string = "Your fellow, <strong><a href='./user.php?uid=$u'>$u</a></strong>, <span class='label label-info'>updated</span> his project <a href='./project.php?pid=$id1'>'$context'</a>.";
                    break;
                  case "create":
                    $string = "<strong><a href='./user.php?uid=$u'>$u</a></strong> just <span class='label label-info'>created</span> a new project <a href='./project.php?pid=$id1'>'$context'</a>. Aren't you excited to see what it is?!";
                    break;
                  case "cancel": 
                    $string = "<strong><a href='./user.php?uid=$u'>$u</a></strong> <span class='label label-danger'>canceled</span> the project <a href='./project.php?pid=$id1'>'$context'</a>. Ask why.";
                    break;
                  default:
                    $string = $event["action"];
                }

                ?>
                <div class="panel panel-default" style="margin-bottom:12px">
                  <div class="panel-body" style="padding:11px">
                    <?=$string?>
                  </div>
                </div> <?php 
              }
            } else { ?>
              <div class="alert alert-info text-center">
                <strong>Info!</strong> Nothing new since <?=pg_to_php_date($last_marked_read, 'M d, Y H:i:s')?>
              </div>
              <?php
            }
          ?>
        </div>
      </div> <!-- row -->
    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>

    <script type="text/javascript">
    $(document).ready(function() {
      // Like
      var oldInterest;
      var oldCity;

      $('#editButton').on('click', function() {
        $('#editGroup').hide();
        $('#saveGroup').show();

        $('#interestInput').prop("readonly", false);
        $('#cityInput').prop("readonly", false);

        oldInterest = $('#interestInput').val();
        oldCity = $('#cityInput').val();

        $('#cityInput').focus();
        // background:transparent;border:0px

        return false; 
      });

      // Unlike
      $('#cancelButton').on('click', function() {
        $('#saveGroup').hide();
        $('#editGroup').show();

        $('#interestInput').prop("readonly", true);
        $('#cityInput').prop("readonly", true);

        $('#interestInput').val(oldInterest);
        $('#cityInput').val(oldCity);

        return false; 
      }); 

      // Pledge
      $('#saveButton').on('click', function() {
        var data = new FormData();
        data.append('uid', $('#uidInput').val());
        data.append('city', $('#cityInput').val());
        data.append('interest', $('#interestInput').val());
        
        $.ajax({
          type: 'POST',               
          processData: false, // important
          contentType: false, // important
          data: data,
          url: "./user_update.php",
          dataType : 'json',
          success: function(data, textStatus, jqXHR) {
            console.log(data);
            if (typeof data.error === 'undefined') {
              if (data["success"]) {
                $('#saveGroup').hide();
                $('#editGroup').show();

                $('#interestInput').prop("readonly", true);
                $('#cityInput').prop("readonly", true);
              } else {
                // TODO
              }
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