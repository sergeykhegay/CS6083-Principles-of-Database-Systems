<?php require_once "./utils.php"; ?>
<?php
  $uid = $_SESSION["uid"];
  if (!isset($uid) || !user_exists($uid)) {
    header("Location: ./user_login.php?login_required=true");
  }
?>