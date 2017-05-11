<?php require_once "./utils.php"; ?>
<?php
  if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
    die("Not ajax");
  }

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // data
    $uid = test_input($_POST["uid"]);
    $pid = test_input($_POST["pid"]);
    $ccid = test_input($_POST["ccid"]); 
    $amount = intval(test_input($_POST["amount"]));


    // flags
    $uid_empty = empty($uid);
    $pid_empty = empty($pid);
    $ccid_empty = empty($ccid);
    $amount_empty = empty($amount);
    
    if ($uid_empty || $pid_empty) {
      $data = array('success' => false, 
                    'message' => "Reload the page and try again.");
      echo json_encode($data);
      die();
    }

    if ($ccid_empty || !is_numeric($ccid)) {
      $data = array('success' => false, 
                    'message' => "Choose a credit card.",
                    'ccid' => $ccid);
      echo json_encode($data);
      die();
    }

    if ($amount_empty || $amount <= 0) {
      $data = array('success' => false, 
                    'message' => "Wrong amount format.");
      echo json_encode($data);
      die(); 
    }

    $creditcard = get_creditcard($ccid);
    
    if ($uid !== $creditcard["uid"]) {
      $data = array('success' => false, 
                    'message' => "Ooops! Looks like this is not your creditcard.");
      echo json_encode($data);
      die();  
    }

    // Get project info
    $project = get_project($pid);
    $remains_to_max = intval($project["pmaxamount"]) - intval($project["pcurrentamount"]);

    if ($remains_to_max < $amount) {
      $data = array('success' => false, 
                    'message' => "You can only pledge \$$remains_to_max.00.");
      echo json_encode($data);
      die(); 
    }

    $pledge = get_pledge($uid, $pid);

    if (empty($pledge)) {
      $res = insert_pledge($uid, $pid, $creditcard["ccnumber"], $amount);
    } elseif ($pledge["plcancelled"] === 't') {
      $res = update_pledge_to_active($uid, $pid, $creditcard["ccnumber"], $amount);
    } else {
      $data = array('success' => false, 
                    'message' => "You cannot pledge twise.");
      echo json_encode($data);
      die(); 
    }

    if ($res) {
      $data = array('success' => true, 
                    'message' => "Success!");
      echo json_encode($data);
      die(); 
    } else {
      $data = array('success' => false, 
                    'message' => "Something went wrong. Try again.");
      echo json_encode($data);
      die(); 
    }
  }

  header("Location: ./404.php");
  die();
?>