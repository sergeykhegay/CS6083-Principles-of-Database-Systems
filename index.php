<?php require_once "utils.php"; ?>
<!DOCTYPE html>
<html lang="en">
  <?php $_title = "Main @ Cabbage"; include "head.inc";?>
  <body>
    <div class="container">
      <?php include "navbar.inc"; ?>
      
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
      <form method="post" action="./login.php">
        <div class="form-group row">
          <label for="inputUser" class="col-sm-2 col-form-label">User</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="inputUser" placeholder="User" name="user">
            <small id="fileHelp" class="form-text text-muted">You can add a new user <a href="./user.php">here</a>.</small>
          </div>
        </div>
        <div class="form-group row">
          <label for="inputKeyword" class="col-sm-2 col-form-label">Keyword</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="inputKeyword" placeholder="Keyword" name="keyword" value="<?php echo test_input($_GET["keyword"]) ?>">
            <small id="fileHelp" class="form-text text-muted">Search for keyword in name or description</small>
          </div>
        </div>
        <div class="form-group row">
          <div class="offset-sm-2 col-sm-10">
            <button type="submit" class="btn btn-primary">I feel lucky!</button>
          </div>
        </div>
      </form>

    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>

  </body>
</html>