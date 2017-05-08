<?php require_once "./utils.php"; ?>
<?php
  session_start();
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // data
    $email = test_input($_POST["email"]);
    $password = test_input($_POST["password"]);
    
    // flags
    $email_empty = empty($_POST["email"]);
    $password_empty = empty($_POST["password"]);      

    // GET args
    $args = "";
    
    if ($email_empty) {
      $args = $args . "email_empty=true";
    }
    if ($password_empty) {
      $args = $args . "&password_empty=true";
    }

    // Try logging in
    if (!$email_empty && !$password_empty) {
      $password_hash = get_upasswordhash($email)["upasswordhash"];
      $passwords_match = password_verify($password, $password_hash);

      if ($passwords_match) {
        $_SESSION["uid"] = $email;
        header("Location: ./user_dashboard.php");
        die();
      } else {
        $args = $args . "&login_failed=true";
        header("Location: ./user_login.php?$args");
      }
    }
  }

  // In case someone got here by mistake
  header("Location: ./user_login.php?$args");
  die();
?>