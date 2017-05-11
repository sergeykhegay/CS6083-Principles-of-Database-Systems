<?php require_once "utils.php"; ?>
<?php session_start(); ?>
<?php
  // data
  $uid = $_SESSION["uid"];
  $pid = test_input($_GET["pid"]);
  $project = get_project_info($pid);

  // flags
  $user_logged_in = isset($uid) && !empty($uid);
  $project_exists = isset($project) && !empty($project);

  if ($project_exists) {
    $ownerid = $project["uid"];

    $image = $project["pimage"];
    $title = $project["ptitle"];
    $description = $project["pdescription"];
    $category = $project["catname"];

    $startdate = pg_to_php_date($project["pstartdate"]);
    $finishdate = pg_to_php_date($project["pfinishdate"]);
    $minamount = $project["pminamount"];
    $maxamount = $project["pmaxamount"];
    $currentamount = $project["pcurrentamount"];

    $project_success = $project["psuccess"] === "t";
    $project_active = $project["pactive"] === "t";
    $project_cancelled = $project["pcancelled"] === "t";

    $project_closedate = pg_to_php_date($project["pclosedate"]);


    $creditcards = get_creditcards($uid);
    $updates = get_updates($pid);

    $liked = like_exists_active($uid, $pid);
    $pledged = pledge_exists_active($uid, $pid);
  } else {
    header("Location: ./404.php");
    die();
  }
?>
<!DOCTYPE html>
<html lang="en">
  <?php $_title = "Project @ Cabbage"; include "inc_head.inc";?>
  <body>
    <div class="container">
      <?php include "inc_navbar.inc"; ?>
      
      <!-- Image jumbotron -->
      <div class="jumbotron" style="background: url('<?php echo "$image"; ?>') no-repeat center center;
                                    background-size: cover;
                                    vertical-align: text-bottom;
                                    text-align: center; 
                                    font-weight: bold;
                                    color: white;
                                    height: 320px">
      </div>
      <link rel="stylesheet" type="text/css" href="grid_layout.css">

      <!-- List project -->
      <div class="page-header">
        <div class="row">
          <div class="col-md-1">
            <?php if ($project_cancelled) { ?>
              <button class="btn btn-danger" 
                    type="submit" style="align:bottom;display:block;width:70px">Sad</button>
            <?php } elseif ($project_success) { ?>
              <button class="btn btn-success" 
                    type="submit" style="align:bottom;display:block;width:70px">Funded</button>
            <?php } elseif ($project_active) { ?>
              <button class="btn btn-info" 
                    type="submit" style="align:bottom;display:block;width:70px">Active</button>
            <?php } else { ?>
              <button class="btn btn-danger" 
                    type="submit" style="align:bottom;display:block;width:70px">Fail</button>
            <?php } ?>
          </div>
          <div class="col-md-10">
            <h1 class="text-center"><?php echo "$title"; ?></h1>
            <p class="text-center"><small> by <a href="./user.php?uid=<?=$uid ?>"><?=$uid ?></a></small></p>
          </div>
          <div class="col-md-1 pull-right" style="vertical-align: text-bottom;">
            <input id="pidInput" type="hidden" value="<?=$pid?>">
            <input id="uidInput" type="hidden" value="<?=$uid?>">
            <?php if ($user_logged_in) { ?>
              <button id="likeButton" class="btn btn-primary <?php if ($liked) echo 'hidden' ?>" 
                      type="submit" style="align: bottom;display:block;width:70px">Like</button>
              <button id="unlikeButton" class="btn btn-danger <?php if (!$liked) echo 'hidden' ?>" 
                      type="submit" style="align: bottom;display:block;width:70px">Unlike</button>
            <?php } else { ?>
              <a class="btn btn-primary" href="./user_login.php" type="button" style="align:bottom;display:block;width:70px">Login</a>
            <?php } ?>
          </div>
        </div> 
      </div>

      <div class="row show-grid">
        
        <div class="col-md-6">
          <!-- Description -->
          <div class="card" style="margin-bottom:20px">
            <div class="card-block">
              <h4 class="card-title">Description</h4>
            </div>
            <!--Card content-->
            <div class="card-block">
                <!--Text-->
                <p class="card-text"><?php echo "$description" ?></p>
                <!-- <a href="#" class="btn btn-primary">Button</a> -->
            </div>
            <!--/.Card content-->
          </div>


          <?php 
            if (!empty($updates)) {
              foreach ($updates as $update) { ?>
                <div class="card" style="margin-bottom:10px">
                  <div class="card-block" >
                      <h8 class="card-title">Update</h8><small> on <?=pg_to_php_date($update["upddate"])?></small>
                  </div>
                  
                  <?php if ($update["updmediavideo"] === 't') { ?>
                    <div class="view overlay hm-white-slight" style="text-align:center">
                        <!-- <video  style="text-align:center" controls>
                          <source src='<$update["updmedia"]?>' type="video/mp4">
                          Your browser does not support the video tag.
                        </video> -->
                        <div class="embed-responsive embed-responsive-16by9">
                          <iframe class="embed-responsive-item" src='<?=$update["updmedia"]?>'></iframe>
                        </div>
                        <a href="#">
                            <div class="mask waves-effect waves-light"></div>
                        </a>
                    </div>
                  <?php } else { ?>
                    <div class="view overlay hm-white-slight" style="text-align:center">
                        <img src='<?=$update["updmedia"]?>' class="img-fluid" alt="">
                        <a href="#">
                            <div class="mask waves-effect waves-light"></div>
                        </a>
                    </div>
                  <?php } ?>

                  <!--/.Card image-->

                  <div class="card-block">
                      <!--Text-->
                      <h4><?=$update["updtitle"]?></h4>
                      <p class="card-text"><?=$update["upddescription"]?></p>
                  </div>
                </div><?php 
              }
            } else { ?>
              <div class="alert alert-info text-center">
                <strong>Info!</strong> No updates yet.
              </div>
            <?php } ?>





        </div>
        <div class="col-md-6">



          <!-- Pledge -->
          <div class="card" style="margin-bottom:20px">
            <div class="card-block">
              <h4 class="card-title">Pledge</h4>
            </div>
            <div class="card-block">
              <form>
                <div class="row">
                  <div class="form-group col-md-6">
                    <label>Start Date</label>
                    <input type="text" class="form-control text-center" value="<?=$startdate?>" style="background:transparent;border:0px" readonly>
                  </div>
                  <div class="form-group col-md-6">
                    <label>End Date</label>
                    <input type="text" class="form-control text-center" value="<?=$finishdate?>" style="background:transparent;border:0px" readonly>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-md-6">
                    <label>Minumim Required</label>
                      <input type="text" class="form-control text-center" value="$<?=$minamount?>.00" style="background:transparent;border:0px"readonly>
                  </div>
                  <div class="form-group col-md-6">
                    <label>Maximum Required</label>
                    <input type="text" class="form-control text-center" value="$<?=$maxamount?>.00" style="background:transparent;border:0px"readonly>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-md-6  has-success">
                    <label>Pledged</label>
                    <input type="text" class="form-control text-center" value="$<?=$currentamount?>.00" style="background:transparent;" readonly>                    
                  </div>
                  <div class="form-group col-md-6">
                  </div>
                </div>
              </form>
              <hr />
              
              <!-- pledge form -->

              <?php if (!$project_active) { ?>

                <?php 
                  if ($project_cancelled) {
                    echo "<div class='alert alert-danger text-center'>
                            <strong>Sad!</strong> The owner cancelled the project on $project_closedate. All pledges have been released
                          </div>";
                  } elseif ($project_success) {
                    echo "<div class='alert alert-success text-center'>
                           <strong>Hoooray!</strong> Successfully funded on $project_closedate! If you were one of the backers you can rate it now at the dashboard.
                         </div>";
                  } elseif (!$project_success) {
                    echo "<div class='alert alert-danger text-center'>
                            <strong>Sad!</strong> The project did not get required funding in time.
                          </div>";
                  }
                ?>
              <?php } elseif ($user_logged_in) { ?>
                <form>
                  <div id="credicardGroup" class="form-group">
                    <label class="control-label" for="exampleInputAmount">Credit Card</label>
                    <input type="hidden" name="uid" value=<?=$uid?>>
                    <input type="hidden" name="pid" value=<?=$pid?>>
                    <select id="ccidInput" name="ccidInput" class="form-control">
                      <?php 
                        if (empty($creditcards)) {
                          echo "<option class='form-control text-danger'>You have not added any credit cards yet</option>";
                        } else {
                          foreach ($creditcards as $cc) {
                            $ccid_tmp = $cc['ccid'];
                            $ccname_tmp = $cc['ccname'];
                            echo "<option class='form-control' value=$ccid_tmp>$ccname_tmp</option>";
                          }                        
                        }
                      ?>
                    </select>
                    <p class="help-block">Add a new credit card <a href="">here</a></p>
                  </div>
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon">$</div>
                      <input id="pledgeInput" type="text" class="form-control" placeholder="Amount">
                      <div class="input-group-addon">.00</div>
                    </div>
                    <p id="pledgeHelp" class="help-block"></p>                    
                  </div>
                  <?php if (!$pledged) { ?>
                    <button id="pledgeButton" type="submit" class="btn btn-success pull-right" style="margin-bottom:15px">Pledge!</button> 
                  <?php } else { ?>
                    <button type="submit" class="btn btn-error pull-right" style="margin-bottom:15px" disabled>You cannot pledge twise</button> 
                  <?php } ?>
                </form>
              <?php } else { ?>
                <div class="alert alert-info text-center">
                  <strong>Info!</strong> You need to <a href="./user_login.php">login</a> to pledge.
                </div>
              <?php } ?>
            </div>
          </div>

          <!-- Comments -->
          <div class="card" style="margin-bottom:20px">
            <div class="card-block">
              <h4 class="card-title">Comment</h4>
            </div>
            <div class="card-block">

              <?php if ($user_logged_in) { ?>
                <!-- Comment form -->
                <form class="form" action="./comment_add_handler.php" method="POST">
                  <div class="form-group">
                    <input type="hidden" name="uid" value=<?=$uid?>>
                    <input type="hidden" name="pid" value=<?=$pid?>>
                    <textarea rows="4" class="form-control" id="textareaInput" 
                           name="comtext" placeholder="Share your thoughts..."></textarea>
                  </div>
                  <button type="submit" class="btn btn-primary pull-right" style="margin-bottom:15px">Share</button>
                </form>
              <?php } else { ?>
                <div class="alert alert-info text-center">
                  <strong>Info!</strong> You need to <a href="./user_login.php">login</a> to share comments.
                </div>
              <?php } ?>

            </div>
          </div>

          <?php 
            $comments = get_comments($pid);
        
            if (!empty($comments)) {
              foreach ($comments as $comment) { ?>
                <div class="card card-outline-primary text-xs-center" style="margin-bottom:10px">
                  <div class="card-block" id="<?=$comment["cid"]?>">
                    <div class="card-blockquote">
                      <p><?=$comment["comtext"]?></p>
                    </div>
                    <footer class="text-info pull-right">Posted by <cite><a href="./user.php?uid=<?=$comment["uid"]?>"><?=$comment["uid"]?></a></cite> on <?=substr($comment["comdate"], 0, 16)?></footer>
                  </div>
                </div>
                <?php
              }
            } else {
              echo "<div class=\"alert alert-info\"><strong>Info!</strong> Be the first appreciator. Spare some words!</div>";
            }

          ?>
        </div>
      </div>

    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>


    <script type="text/javascript">
    $(document).ready(function() {
      // Like
      $('#likeButton').on('click', function() {
        var data = new FormData();
        data.append('pid', $('#pidInput').val());
        data.append('uid', $('#uidInput').val());
      
        $.ajax({
          type: 'POST',               
          processData: false, // important
          contentType: false, // important
          data: data,
          url: "./like_like_handler.php",
          dataType : 'text',
          success: function(data, textStatus, jqXHR) {
            console.log(data);
            if (typeof data.error === 'undefined') {
              $('#likeButton').addClass("hidden");
              $('#unlikeButton').removeClass("hidden");
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
      $('#unlikeButton').on('click', function() {
        var data = new FormData();
        data.append('pid', $('#pidInput').val());
        data.append('uid', $('#uidInput').val());
      
        $.ajax({
          type: 'POST',               
          processData: false, // important
          contentType: false, // important
          data: data,
          url: "./like_unlike_handler.php",
          dataType : 'text',
          success: function(data, textStatus, jqXHR) {
            console.log(data);
            if (typeof data.error === 'undefined') {
              $('#likeButton').removeClass("hidden");
              $('#unlikeButton').addClass("hidden");
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

      // Pledge
      $('#pledgeButton').on('click', function() {
        var data = new FormData();
        data.append('pid', $('#pidInput').val());
        data.append('uid', $('#uidInput').val());
        data.append('ccid', $('#ccidInput').val());
        data.append('amount', $('#pledgeInput').val());
        
        $.ajax({
          type: 'POST',               
          processData: false, // important
          contentType: false, // important
          data: data,
          url: "./pledge_handler.php",
          dataType : 'json',
          success: function(data, textStatus, jqXHR) {
            console.log(data);
            if (typeof data.error === 'undefined') {
              if (data["success"]) {
                $("#pledgeHelp").html(data["message"]);
                window.location.href = window.location.href;
              } else {
                $("#pledgeHelp").html("<span style='color:red'>" + data["message"] + "</span>");
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