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
        $uid = $_SESSION["uid"];
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
      <link rel="stylesheet" type="text/css" href="grid_layout.css">
      
      <!-- List project -->
      <div class="page-header">
        <div class="row">
          <div class="col-md-1">
          </div>
          <div class="col-md-10">
            <h1 class="text-center"><?php echo "$title"; ?></h1>
            <p class="text-center"><small> by <a href="./user.php?uid=<?=$uid ?>"><?=$uid ?></a></small></p>
          </div>
          <div class="col-md-1 pull-right" style="vertical-align: text-bottom;">
            <input id="pidInput" type="hidden" value="<?=$pid?>">
            <input id="uidInput" type="hidden" value="<?=$uid?>">
            <button id="likeButton" class="btn btn-primary" type="submit" style="align: bottom;display:block;width:70px">Like</button>
            <button id="unlikeButton" class="btn btn-danger hidden" type="submit" style="align: bottom;display:block;width:70px">Unlike</button>
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

          <div class="card" style="margin-bottom:20px">
            <div class="card-block" >
                <h8 class="card-title">Update</h8><small> on DATE HERE</small>
            </div>
            <!--Card image or video-->
            <div class="view overlay hm-white-slight">
                <img src="https://mdbootstrap.com/img/Photos/Horizontal/Nature/4-col/img%20%287%29.jpg" class="img-fluid" alt="">
                <a href="#">
                    <div class="mask waves-effect waves-light"></div>
                </a>
            </div>
            <!--/.Card image-->

            <!--Card content-->

            <div class="card-block">
                <!--Text-->
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            </div>
            <!--/.Card content-->
          </div>

        </div>
        <div class="col-md-6">
          <div class="card" style="margin-bottom:20px">
            <div class="card-block">
              <h4 class="card-title">Comment</h4>
            </div>
            <!--Card content-->
            <div class="card-block">
              <!-- Comment form -->
              <form class="form" action="./comment_add_handler.php" method="POST">
                <div class="form-group">
                  <input type="hidden" name="uid" value=<?=$uid?>>
                  <input type="hidden" name="pid" value=<?=$pid?>>
                  <textarea rows="4" class="form-control" id="textareaInput" 
                         name="comtext" placeholder="Share your thoughts..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Share</button>
              </form>
            </div>
            <!--/.Card content-->
          </div>

          <!-- Comments -->
          <?php 
            $comments = get_comments($pid);
        
            if (!empty($comments)) {
              foreach ($comments as $comment) { ?>
                <div class="card card-outline-primary text-xs-center" style="margin-bottom:20px">
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
    });
    </script>

  </body>
</html>