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
        $tag = $_GET[tag];

        // flags
        $user_logged_out = isset($_GET["logged_out"]);
        $tag_all = $tag !== "art" &&
                   $tag !== "comics" &&
                   $tag !== "crafts" &&
                   $tag !== "music" &&
                   $tag !== "theater" &&
                   $tag !== "food";

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
      <ul class="nav nav-pills nav-justified">
        <li role="presentation" <?php if ($tag_all) echo "class='active'"; ?> >
          <a href=".?">All</a>
        </li>
        <li role="presentation" <?php if ($tag === 'art') echo "class='active'"; ?>>
          <a href=".?tag=art">Art</a>
        </li>
        <li role="presentation" <?php if ($tag === 'comics') echo "class='active'"; ?>>
          <a href=".?tag=comics">Comics</a>
        </li>
        <li role="presentation" <?php if ($tag === 'crafts') echo "class='active'"; ?>>
          <a href=".?tag=crafts">Crafts</a>
        </li>
        <li role="presentation" <?php if ($tag === 'music') echo "class='active'"; ?>>
          <a href=".?tag=music">Music</a>
        </li>
        <li role="presentation" <?php if ($tag === 'theater') echo "class='active'"; ?>>
          <a href=".?tag=theater">Theater</a>
        </li>
        <li role="presentation" <?php if ($tag === 'food') echo "class='active'"; ?>>
          <a href=".?tag=food">Food</a>
        </li>
      </ul>
      
      <!-- List projects here based on tags -->
      <div class="page-header">
        <h1>Example page header <small>Subtext for header</small></h1>
      </div>

    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>

  </body>
</html>