<?php require_once "./utils.php"; ?>
<?php
  //check if this is an ajax request
  if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    die();
  }

  $uid1 = test_input($_POST["uid1"]);
  $uid2 = test_input($_POST["uid2"]);
  $action = test_input($_POST["action"]);
  
  if (empty($uid1) || empty($uid2) || empty($action) 
      || !($action === "follow" || $action === "unfollow")) {
    $data = array('success' => false,
                  'message' => "Bad parameters");
    echo json_encode($data);
    die();
  }

  $follow_exists = follow_exists($uid1, $uid2);

  if ($action === "follow") {
    if (!$follow_exists) {
      $res = insert_follow($uid1, $uid2);
    } else {
      $res = true;
    }
  } else {
    if ($follow_exists) {
      $res = delete_follow($uid1, $uid2);
    } else {
      $res = true;
    }
  }

  if ($res) {
    $data = array('success' => true,
                  'message' => "Operation done: ".$action);
  } else {
    $data = array('success' => false,
                  'message' => "Something went wrong");
  }
  echo json_encode($data);
?>