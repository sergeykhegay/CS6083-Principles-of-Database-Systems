<?php require_once "./utils.php"; ?>
<?php
  session_start();
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // data
    $uid = $_SESSION["uid"];
    $title = test_input($_POST["title"]);
    $description = test_input($_POST["description"]);
    $category = test_input($_POST["category"]);
    $filepath = test_input($_POST["filepath"]);
    $days = test_input($_POST["days"]);
    $min = test_input($_POST["min"]);
    $max = test_input($_POST["max"]);


    // flags
    $title_empty = empty($title);
    $description_empty = empty($description);      
    $category_empty = empty($category);
    $filepath_empty = empty($filepath);

    // GET args
    // $args = "";
    
    // if ($email_empty) {
    //   $args = $args . "email_empty=true";
    // }
    // if ($password_empty) {
    //   $args = $args . "&password_empty=true";
    // }

    // Try logging in
    if (!$title_empty && !$description_empty && !$category_empty && !$filepath_empty) {
      $res = insert_and_return_project($uid, $title, $description, $category, $filepath, $days, $min, $max);
      if (!empty($res) && isset($res["pid"])) {
        $pid = $res["pid"];
        header("Location: ./project.php?pid=$pid");
        die();
      }
    }
  }

  // In case someone got here by mistake
  header("Location: ./user_login.php?$args");
  die();
?>