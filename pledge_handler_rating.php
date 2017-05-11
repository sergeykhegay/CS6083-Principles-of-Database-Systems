<?php require_once "./utils.php"; ?>
<?php
  session_start();
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rate = test_input($_POST["min"]);
    $pid = test_input($_POST["pid"]);
    $uid = $_SESSION["uid"];
    $disable = test_input($_POST["disable"]);
    if($disable == "disabled"){
        header("Location: ./dashboard_pledges.php?rate=0");
    }
    else{
        header("Location: ./dashboard_pledges.php?rate=$rate&pid=$pid");
        update_rating($rate, $pid, $uid);
    }
}
?>