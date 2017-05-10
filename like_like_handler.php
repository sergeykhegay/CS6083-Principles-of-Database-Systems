<?php require_once "./utils.php"; ?>
<?php
  //check if this is an ajax request
  if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
    die();
  }

  $uid = test_input($_POST["uid"]);
  $pid = test_input($_POST["pid"]);

  echo $pid;
  $like_exists = like_exists($uid, $pid);

  if (!empty($uid) && !empty($pid)) {
    if ($like_exists) {
      $res = update_like_to_active($uid, $pid);
    } else {
      $res = insert_like($uid, $pid);
    }

    if ($res) {
      $data = array('success' => true,
                    'message' => "liked");
      echo json_encode($data);
    }
  } else {
    die();
  }
?>