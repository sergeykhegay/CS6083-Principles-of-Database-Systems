<?php require_once "utils.php"; ?>
<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
  <?php $_title = "Store @ Infinitely Pending Express"; include "inc_head.inc";?>
  <body>
    <div class="container">
      <?php include "inc_navbar.inc"; ?>
      
      <?php
        if (!isset($_SESSION["cname"])) {
          echo "<div class=\"alert alert-danger\"><strong>Error!</strong> There is no session attached. <a href=\"./index.php\">Start over</a>.</div>";
        }
      ?>
      <?php 
        if ($_GET["bad_quantity"] == "true") {
          echo "<div class=\"alert alert-danger\"><strong>Error!</strong> We would love you to actually buy something, pal... Zero or negative quantity is bad.</div>";
        }
      ?>
      <?php 
        if ($_GET["bad_price"] == "true") {
          echo "<div class=\"alert alert-danger\"><strong>Error!</strong> The price for the product does not make any sense. Too negative! Try again!</div>";
        }
      ?>
      <?php 
        if ($_GET["product_does_not_exist"] == "true") {
          echo "<div class=\"alert alert-danger\"><strong>Error!</strong> There is no such product or it is discontinued! We do not sell yesterday's news.</div>";
        }
      ?>
      <?php 
        if ($_GET["something_went_wrong"] == "true") {
          echo "<div class=\"alert alert-danger\"><strong>Error!</strong> There was a mistake in processing. Please try again! </div>";
        }
      ?>
      <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <div class="form-group row">
          <label for="inputKeyword" class="col-sm-2 col-form-label">Keyword</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="inputKeyword" placeholder="Keyword" name="keyword" value="<?php echo test_input($_GET["keyword"]) ?>">
            <small id="fileHelp" class="form-text text-muted">Search for keyword in name or description</small>
          </div>
        </div>
        <div class="form-group row">
          <div class="offset-sm-2 col-sm-10">
            <button type="submit" class="btn btn-primary">Keep cogs moving!</button>
          </div>
        </div>
      </form>

      <?php
        $keyword = test_input($_GET["keyword"]);
        $products = get_products($keyword); 
        if (empty($products)) {
          echo "<div class=\"alert alert-info\"><strong>Info!</strong> Nothing found. Try another keyword, explorer!</div>";
        }
      ?>

      <table class="table">
        <thead>
          <tr>
            <th>Product</th>
            <th>Description</th>
            <th>Price</th>
            <th>Status</th>
            <th>Quantity</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php
            if (is_array($products)) {
              foreach ($products as $item) {
                $pname = $item["pname"];
                $pdescription = $item["pdescription"];
                $pprice = $item["pprice"]; // do not do this in production!!!
                $pstatus = $item["pstatus"];

                $row_class = ($pstatus == "discontinued" ? "bg-danger" : "");
                $maybe_disabled = ($pstatus == "discontinued" ? "disabled" : "");
                echo "<tr>
                  <form method=post action='./buy.php'>
                    <input type='hidden' value='$keyword' name='keyword'>
                    <input type='hidden' value='$pname' name='pname'>
                    <input type='hidden' value='$pprice' name='pprice'>

                    <th scope='row'>$pname</th>
                    <td>$pdescription</td>
                    <td>$pprice</td>
                    <td class=$row_class>$pstatus</td>
                    <td><input type='number' value=0 name='quantity' $maybe_disabled></td>
                    <td><button type='submit' class='btn btn-primary' $maybe_disabled>Buy</button></td>
                  </form>
                </tr>";
              }
            }
          ?>
        </tbody>
      </table>

    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>

  </body>
</html>