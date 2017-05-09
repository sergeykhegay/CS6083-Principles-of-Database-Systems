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
    $wrong_minmax = intval($min) < 1 || intval($min) > intval($max);

    // GET args
    $args = "";
    
    if ($title_empty) {
      $args = $args . "title_empty=true";
    }
    if ($description_empty) {
      $args = $args . "&description_empty=true";
    }
    if ($category_empty) {
      $args = $args . "&category_empty=true";
    }
    if ($filepath_empty) {
      $args = $args . "&filepath_empty=true";
    }
    if ($wrong_minmax) {
      $args = $args . "&wrong_minmax=true";
    }

    $args = $args ."&title=$title&description=$description&category=$category";
    $args = $args ."&filepath=$filepath&days=$days&min=$min&max=$max";

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
  header("Location: ./project_create.php?$args");
  die();
?>