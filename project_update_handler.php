<?php require_once "./utils.php"; ?>
<?php
  session_start();
  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // data
    $uid = $_SESSION["uid"];

    $pid = test_input($_POST["pid"]);
    $title = test_input($_POST["title"]);
    $description = test_input($_POST["description"]);
    $filepath = test_input($_POST["filepath"]);
    $mediavideo = test_input($_POST["mediavideo"]);


    // flags
    $title_empty = empty($title);
    $description_empty = empty($description);
    $filepath_empty = empty($filepath);
    $mediavideo_empty = empty($mediavideo);
    // GET args
    $args = "";
    
    if ($title_empty) {
      $args = $args . "title_empty=true";
    }
    if ($description_empty) {
      $args = $args . "&description_empty=true";
    }
    if ($filepath_empty) {
      $args = $args . "&filepath_empty=true";
    }
    if ($mediavideo_empty) {
      $mediavideo = 'FALSE';
      $args = $args . "&mediavideo_empty=true";
    }

    $args = $args ."&title=$title&description=$description&filepath=$filepath";

    if (!$title_empty && !$description_empty && !$filepath_empty) {
      $res = insert_update($pid, $title, $description, $filepath, $mediavideo);

      if ($res) {
        header("Location: ./project.php?pid=$pid");
        die();
      }
    }
  }
  header("Location: ./project_update.php?pid=$pid&$args");
  die();
?>