<?php require_once "./utils.php"; ?>
<?php

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // data
    $uid = pg_escape_string(test_input($_POST["uid"]));
    $pid = pg_escape_string(test_input($_POST["pid"]));
    $comtext = pg_escape_string(test_input($_POST["comtext"]));

    // flags
    $uid_empty = empty($uid);
    $pid_empty = empty($pid);
    $comtext_empty = empty($comtext);

    // GET args
    $args = "";
    
    if ($comtext_empty) {
      $args = $args . "comtext_empty=true";
    }

    // Try addig a comment
    if (!$uid_empty && !$pid_empty && !$comtext_empty) {
      $res = insert_and_return_comment($uid, $pid, $comtext);

      if ($res) {
        $cid = $res["cid"];
        header("Location: ./project.php?pid=$pid#$cid");
        die();
      } else {
        $args = $args . "&retry=true";
        header("Location: ./project.php?pid=$pid&$args");
        die();
      }
    }
  }

  header("Location: ./404.php");
  die();
?>