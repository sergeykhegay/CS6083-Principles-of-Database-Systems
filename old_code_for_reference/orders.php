<?php require_once "utils.php"; ?>
<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
  <?php $_title = "Orders @ Infinitely Pending Express"; include "head.inc";?>
  <body>
    <div class="container">
      <?php include "navbar.inc"; ?>
      
      <?php
        if (!isset($_SESSION["cname"])) {
          echo "<div class=\"alert alert-danger\"><strong>Error!</strong> There is no session attached. <a href=\"./index.php\">Start over</a>.</div>";
        }
      ?>
      <?php
        if ($_GET["purchased"] == "true") {
          echo "<div class=\"alert alert-success\"><strong>Success!</strong> Thanks for your purchase of {$_GET["quantity"]} item(s) of <a href='./store.php?keyword={$_GET["pname"]}'>{$_GET["pname"]}</a>!</div>";
        }
      ?>

      <?php 
        $orders = get_orders($_SESSION["cname"]); 
        if (empty($orders)) {
          echo "<div class=\"alert alert-info\"><strong>Info!</strong> Nothing yet. Replenish <a href='./store.php'>here</a>.</div>";
        }
      ?>
      
      <table class="table">
        <thead>
          <tr>
            <th>Date</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Status</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php
            if (is_array($orders)) {
              foreach ($orders as $item) {
                $pdate = $item["putime"];
                $pname = test_input($item["pname"]);
                $quantity = $item["quantity"];
                $price = $item["puprice"];
                $status = $item["status"];
                echo "<tr>
                        <th scope='row'>$pdate</th>
                        <td><a href='./store.php?keyword=$pname'>$pname</a></td>
                        <td>$quantity</td>
                        <td>$price</td>
                        <td class=$row_class>$status</td>
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