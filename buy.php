<?php require_once "utils.php"; ?>
<?php session_start(); ?>
<?php
  $keyword = test_input($_POST["keyword"]);
  $pname = $_POST["pname"];
  $quantity = $_POST["quantity"];
  $pprice = $_POST["pprice"];
  $cname = $_SESSION["cname"];

  if (!isset($_SESSION["cname"])) {
    header("Location: ./store.php?keyword=$keyword");
    die();
  }

  // Parameters sanitation
  try {
    $quantity = intval($quantity);
  } catch (Exception $e) {
    $quantity = null;
  }

  try {
    $pprice = floatval($pprice);
  } catch (Exception $e) {
    $pprice = null;
  }
    
  if ($quantity == null || $quantity <= 0) {
    header("Location: ./store.php?bad_quantity=true&keyword=$keyword");
    die();
  }

  if ($pprice == null || $pprice < 0) {
    header("Location: ./store.php?bad_price=true&keyword=$keyword");
    die();
  }  

  if (!product_exists_and_available($pname)) {
    header("Location: ./store.php?product_does_not_exist=true&keyword=$keyword");
    die();
  }

  // Business logic
  $pending_order = retrieve_pending_order($cname, $pname);

  if ($pending_order == false) { // no such order, insert new
    $res = insert_order($cname, $pname, $quantity, $pprice);
  } else { // order exists, update
    $res = update_order($cname, $pname, $pending_order["putime"], $quantity, $pprice);
  }

  if ($res == true) {
    $escaped_pname = test_input($pname);
    header("Location: ./orders.php?purchased=true&pname=$escaped_pname&quantity=$quantity");
    die();
  } else {
    header("Location: ./store.php?something_went_wrong=true&keyword=$keyword");
    die();
  }
?>