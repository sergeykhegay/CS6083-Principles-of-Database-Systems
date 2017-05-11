<?php require_once "./utils.php"; ?>
<?php
  session_start();
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // data
    $uid = test_input($_POST["uid"]);
    $city = test_input($_POST["city"]);
    $interests = test_input($_POST["interest"]);
    
    // flags
    $uid_empty = empty($_POST["uid"]);

    if (!$uid_empty) {
      $user_exists = user_exists($uid);
    }

    // Try logging in
    if (!$uid_empty && $user_exists) {
      $res = user_update($uid, $city, $interests);

      if ($res) {
          $data = array('success' => true,
                        'message' => "Operation done");
      } else {
          $data = array('success' => false,
                        'message' => "Something went wrong");
      }
      echo json_encode($data);
    }
  }
?>