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
        $tag = $_GET["tag"];
        $keyword = test_input($_GET["keyword"]);
        
        // flags
        $user_logged_out = isset($_GET["logged_out"]);
        $tag_all = !($tag === "art") &&
                   !($tag === "comics") &&
                   !($tag === "crafts") &&
                   !($tag === "music") &&
                   !($tag === "theater") &&
                   !($tag === "food");
        $keyword_is_set = !empty($keyword);

        // Show projects by keyword, not tags
        if ($keyword_is_set) {
          $tag_all = true;
        }
        
        // messages
        if ($user_logged_out) {
          echo "<div class=\"alert alert-info\"><strong>Info!</strong> Logged out.</div>";
        }

      ?>
      <!-- Image jumbotron -->
      <div class="jumbotron" style="background: url('./images/explore-wallpaper-1280x768.jpg') no-repeat center center;
                                    vertical-align: text-bottom;
                                    text-align: center; 
                                    font-weight: bold;
                                    color: white;
                                    height: 300px">
        <h1 style="padding-top: 50px">E X P L O R E</h1>
        <p>We are pleased to introduce you to our icredible set of fascinating projects! 
          You can help thousands of people around the world to make the world a better place.</p>
      </div>

      
      <!-- Tags -->
      
        <ul class="nav nav-tabs nav-justified"> 
          <li role="presentation" <?php if ($tag_all) echo "class='active'"; ?> >
            <a href=".?">All</a>
          </li>
          <li role="presentation" <?php if ($tag === 'art' && !$tag_all) echo "class='active'"; ?>>
            <a href=".?tag=art">Art</a>
          </li>
          <li role="presentation" <?php if ($tag === 'comics' && !$tag_all) echo "class='active'"; ?>>
            <a href=".?tag=comics">Comics</a>
          </li>
          <li role="presentation" <?php if ($tag === 'crafts' && !$tag_all) echo "class='active'"; ?>>
            <a href=".?tag=crafts">Crafts</a>
          </li>
          <li role="presentation" <?php if ($tag === 'music' && !$tag_all) echo "class='active'"; ?>>
            <a href=".?tag=music">Music</a>
          </li>
          <li role="presentation" <?php if ($tag === 'theater' && !$tag_all) echo "class='active'"; ?>>
            <a href=".?tag=theater">Theater</a>
          </li>
          <li role="presentation" <?php if ($tag === 'food' && !$tag_all) echo "class='active'"; ?>>
            <a href=".?tag=food">Food</a>
          </li>
        </ul>
      
       

      <!-- List projects here based on tags -->
        
      <form action="./index.php" method="GET" style="margin-top:3px">
        <div class="form-group">
          <div class="row">
            <div class="col-md-3">
            </div>
            <div class="col-md-6">
              <div class="input-group">
                <input type="text" class="form-control" placeholder="Search" 
                      name="keyword" value="<?=$keyword?>">
                <span class="input-group-btn">
                  <button type="submit" class="btn btn-default">Submit</button>
                </span>
              </div>
            </div>
            <div class="col-md-3">
            </div>
          </div>
        </div>
      </form>
        
        
      
      <table>
        <?php 
          $project =  ($keyword_is_set) ? get_projects_by_keyword($keyword) 
                                        : get_projects($tag);

          while ($row = pg_fetch_row($project)) {
            $user = $row[0];
            $pid = $row[1];
            $title = $row[3];
            $description = $row[4];
            $image = $row[5];
            $start_date = $row[6];
            $progress = 0;
            if ($row[12] != 0) {
              $progress = $row[12]/$row[11] * 100;
            }
        ?>
            <div class="col-sm-6 col-md-4 col-lg-4 mt-4" style="padding-top:30px">
              <div class="card">
                <div class="card-img-div-top" style="background: url('<?=$image?>') no-repeat center center;
                                                     background-size: cover">
                </div>
                <div class="card-block">
                  <figure class="profile">
                    <img src="http://success-at-work.com/wp-content/uploads/2015/04/free-stock-photos.gif" class="profile-avatar" alt="">
                  </figure>
                  <h4 class="card-title mt-3"><a href="./project.php?pid=<?= $pid ?>"><?php echo $title ?></a></h4>
                  <div class="meta">
                    <div class="progress">
                      <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow=<?=$progress?>
                           aria-valuemin="0" aria-valuemax="100" style="width:<?=$progress?>%">
                        <?=$progress ?>% Complete (success)
                      </div>
                    </div>
                  </div>
                  <div class="card-text">
                    <?php echo $description ?>
                  </div>
                </div>
                <div class="card-footer pull-right">
                  <small>Posted on <?php echo substr($start_date, 0, 19) ?></small>
                  <a href="./project.php?pid=<?=$pid ?>">
                    <button class="btn btn-info btn-xs">view</button>
                  </a>
                </div>
              </div>
            </div>
        <?php } // end of while ?>
      </table>

    </div>

    <link rel="stylesheet" type="text/css" href="grid_layout.css">

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>

  </body>
</html>