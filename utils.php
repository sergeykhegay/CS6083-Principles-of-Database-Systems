<?php

  require_once "utils_consts.php";

  function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = pg_escape_string($data);
    return $data;
  };

  function get_db_connection() {
    // "host=localhost dbname=project user=sergey"
    return pg_connect(get_connection_string());
  };

  function pg_to_php_date($pg_date) {
    $format = arguments[1];
    date_default_timezone_set("UTC");
    if (empty($format)) {
      $format = 'M d, Y';
    }
    return date($format, strtotime($pg_date));
  }

// USER
  function user_exists($uid) {
    $db_connection = get_db_connection();
    $result = pg_query($db_connection, 
      "SELECT * 
         FROM users
        WHERE uid='$uid';"
    );
    pg_close();

    return pg_num_rows($result) == 1;
  };

  function insert_user($uid, $password_hash) {
    $db_connection = get_db_connection();
    $result = @pg_query($db_connection, 
      "INSERT INTO users (uid, upasswordhash) 
          VALUES ('$uid', '$password_hash');"
    );
    pg_close();

    return is_resource($result); // true or false
  };

  function get_upasswordhash($uid) {
    $db_connection = get_db_connection();
    $result = pg_query($db_connection, 
      "SELECT upasswordhash
         FROM users
        WHERE uid='$uid';"
    );
    pg_close();

    return pg_fetch_array($result);
  }

  function get_umarkreaddate($uid) {
    $db_connection = get_db_connection();
    $result = pg_query($db_connection, 
      "SELECT umarkedreaddate
         FROM users
        WHERE uid='$uid';"
    );
    pg_close();

    return pg_fetch_array($result)["umarkedreaddate"];
  }

  function user_update_umarkreaddate($uid) {
    $db_connection = get_db_connection();
    $result = pg_query($db_connection, 
      "UPDATE users
          SET umarkedreaddate = current_timestamp
        WHERE uid='$uid';"
    );
    pg_close();

    return is_resource($result);
  }

// PROJECT
  function get_projects($category){
    $db_connection = get_db_connection();
    if($category == null){
      $result = pg_query
      ($db_connection, "SELECT * FROM project WHERE pactive = 'TRUE';");
    }
    else{
      $result = pg_query
      ($db_connection, "SELECT * FROM project 
        WHERE pactive = 'TRUE' AND Lower(catname) = '$category';");
    }
    pg_close();

    return $result;
  }

  function get_projects_by_keyword($keyword) {
    $db_connection = get_db_connection();
    $result = null;

    if (empty($keyword)) {
      $result = pg_query($db_connection, "SELECT * FROM project WHERE pactive = 'TRUE';");
    } else {
      $result = pg_query($db_connection, 
        "SELECT * 
         FROM project 
         WHERE pdescription ILIKE '%$keyword%' OR
               ptitle ILIKE '%$keyword%' OR
               catname ILIKE '%$keyword%' AND pactive = 'TRUE'
               ;");
    }
    pg_close();
    // if (!is_resource($result)) {
    //   return null;
    // }
    return $result;
  }

  function insert_and_return_project($uid, $title, $description, $category, 
                          $filepath, $days, $min, $max) {
    $db_connection = get_db_connection();
    $result = pg_query($db_connection, 
      "INSERT INTO project (uid, catname, ptitle, pdescription, pimage, pfinishdate, pminamount, pmaxamount)
          VALUES ('$uid', '$category', '$title', '$description', '$filepath', current_timestamp + interval '$days day', '$min', '$max')
          RETURNING pid;"
    );
    
    pg_close();
    if (!is_resource($result))
      return;
    return pg_fetch_array($result);
  };

  function project_exists($pid) {
    $db_connection = get_db_connection();
    $result = pg_query($db_connection, 
      "SELECT * 
         FROM project
        WHERE pid='$pid';"
    );
    pg_close();
    return pg_num_rows($result) == 1;
  };

  function get_project($pid) {
    $db_connection = get_db_connection();
    $result = pg_query($db_connection, 
      "SELECT * 
         FROM project
        WHERE pid='$pid';"
    );
    pg_close();

    if (!isset($result)) {
      return;
    }
    return pg_fetch_array($result);
  }

  function get_user_project($uid){
    $db_connection = get_db_connection();
    $result = pg_query($db_connection, 
      "SELECT * 
         FROM project
        WHERE uid='$uid' AND pcancelled = 'FALSE';"
    );
    pg_close();

    if (!isset($result)) {
      return;
    }
    return $result;
  }

  function get_project_info($pid) {
    $db_connection = get_db_connection();
    $result = pg_query($db_connection, 
      "SELECT * 
         FROM project, users
        WHERE pid=$pid AND
              project.uid = users.uid;"
    );
    pg_close();

    if (!is_resource($result)) {
      return;
    }
    return pg_fetch_array($result);
  }

  function cancel_project($pid){
    $db_connection = get_db_connection();
    $result = pg_query($db_connection, 
      "UPDATE project  
         SET pcancelled = 'TRUE',
             pactive = 'FALSE',
             plosedate = current_timestamp
        WHERE pid=$pid ;"
    );
    pg_close();
  }

// PLEDGE
  function get_pledges($uid){
    $db_connection = get_db_connection();
    $result = pg_query($db_connection, 
      "SELECT * 
         FROM pledge natural join creditcard
        WHERE uid = '$uid' AND 
              plcancelled = 'FALSE';"
    );
    
    return $result;
  }

  function get_pledge($uid, $pid) {
    $db_connection = get_db_connection();
    $result = pg_query($db_connection, 
      "SELECT * 
         FROM pledge
        WHERE pid=$pid AND
              uid='$uid';"
    );
    pg_close();
    return pg_fetch_array($result);
  };

  function cancel_pledge($pid, $uid){
    $db_connection = get_db_connection();
    pg_query($db_connection, 
      "UPDATE pledge 
          SET plcancelled = 'TRUE'
        WHERE pid='$pid' AND uid='$uid'; "
    );
    $amount = pg_query($db_connection,
      "SELECT plamount
         FROM pledge
        WHERE pid='$pid' AND uid='$uid';"
    );

    $amount = pg_fetch_row($amount)[0];
    pg_query($db_connection, 
      "UPDATE project 
          SET pcurrentamount = pcurrentamount - '$amount'
        WHERE pid='$pid' AND pactive='TRUE';"
    );
    pg_close();
  }

  function pledge_exists($uid, $pid) {
    $db_connection = get_db_connection();
    $result = pg_query($db_connection, 
      "SELECT * 
         FROM pledge
        WHERE pid=$pid AND
              uid='$uid';"
    );
    pg_close();
    return pg_num_rows($result) == 1;
  };

  function pledge_exists_active($uid, $pid) {
    $db_connection = get_db_connection();
    $result = pg_query($db_connection, 
      "SELECT * 
         FROM pledge
        WHERE pid=$pid AND
              uid='$uid' AND
              plcancelled=FALSE;"
    );
    pg_close();
    return pg_num_rows($result) == 1;
  };

  function insert_pledge($uid, $pid, $ccnumber, $amount) {
    $db_connection = get_db_connection();
    $result = pg_query($db_connection, 
      "INSERT INTO pledge (uid, pid, ccnumber, plamount)
        VALUES ('$uid', $pid, '$ccnumber', $amount);"
    );
    pg_close();

    return is_resource($result);
  }

  function update_pledge_to_active($uid, $pid, $ccnumber, $amount) {
    $db_connection = get_db_connection();
    $result = pg_query($db_connection, 
      "UPDATE pledge
          SET ccnumber = '$ccnumber',
              plamount = $amount,
              pldate = current_timestamp,
              plcancelled = FALSE
        WHERE uid='$uid' AND
              pid=$pid;"
    );
    pg_close();
    return is_resource($result);
  }

  function update_pledge_to_not_active($uid, $pid, $ccnumber, $amount) {
    $db_connection = get_db_connection();
    $result = pg_query($db_connection, 
      "UPDATE pledge
          SET pldate = current_timestamp,
              plcancelled = TRUE
        WHERE uid='$uid' AND
              pid='$pid';"
    );
    pg_close();
    return is_resource($result);
  }

// COMMENT
  function get_comments($pid) {
    $db_connection = get_db_connection();
    $result = pg_query($db_connection, 
      "SELECT * 
         FROM comment, users
        WHERE pid=$pid AND
              comment.uid = users.uid
        ORDER BY comdate DESC;"
    );
    pg_close();

    if (!is_resource($result)) {
      return null;
    }
    return pg_fetch_all($result);
  }

  function insert_and_return_comment($uid, $pid, $comtext) {
    $db_connection = get_db_connection();
    $result = pg_query($db_connection, 
      "INSERT INTO comment (uid, pid, comtext)
        VALUES ('$uid', '$pid', '$comtext')
        RETURNING cid;"
    );
    pg_close();

    if (!is_resource($result))
      return;
    return pg_fetch_array($result);
  }

// LIKE

  function like_exists($uid, $pid) {
    $db_connection = get_db_connection();
    $result = pg_query($db_connection, 
      "SELECT * 
         FROM likes
        WHERE pid='$pid' AND uid='$uid';"
    );
    pg_close();
    return pg_num_rows($result) == 1;
  }

  function like_exists_active($uid, $pid) {
    $db_connection = get_db_connection();
    $result = pg_query($db_connection, 
      "SELECT * 
         FROM likes
        WHERE pid='$pid' AND 
              uid='$uid' AND
              likeactive=TRUE;"
    );
    pg_close();
    return pg_num_rows($result) == 1;
  }

  function insert_like($uid, $pid) {
    $db_connection = get_db_connection();
    $result = pg_query($db_connection, 
      "INSERT INTO likes (uid, pid)
        VALUES ('$uid', '$pid');"
    );
    pg_close();

    return is_resource($result);
  }

  function update_like_to_active($uid, $pid) {
    $db_connection = get_db_connection();
    $result = pg_query($db_connection, 
      "UPDATE likes
          SET likedate = current_timestamp,
              likeactive = TRUE
        WHERE uid='$uid' AND
              pid='$pid';"
    );
    pg_close();
    return is_resource($result);
  }

  function update_like_to_not_active($uid, $pid) {
    $db_connection = get_db_connection();
    $result = pg_query($db_connection, 
      "UPDATE likes
          SET likedate = current_timestamp,
              likeactive = FALSE
        WHERE uid='$uid' AND 
              pid='$pid';"
    );
    pg_close();
    return is_resource($result);
  }

// CREDITCARD
  function get_creditcard($ccid) {
    $db_connection = get_db_connection();
    $result = pg_query($db_connection, 
      "SELECT *
         FROM creditcard
        WHERE ccid='$ccid' AND
              ccactive = TRUE
        ORDER BY ccid DESC;"
    );
    pg_close();

    if (!is_resource($result)) {
      return null;
    }
    return pg_fetch_array($result);
  }

  function get_creditcards($uid) {
    $db_connection = get_db_connection();
    $result = pg_query($db_connection, 
      "SELECT *
         FROM creditcard
        WHERE uid='$uid' AND
              ccactive = TRUE
        ORDER BY ccid DESC;"
    );
    pg_close();

    if (!is_resource($result)) {
      return null;
    }
    return pg_fetch_all($result);
  }



// UPDATE
  function get_updates($pid) {
    $db_connection = get_db_connection();
    $result = pg_query($db_connection, 
      "SELECT *
         FROM update
        WHERE pid='$pid'
        ORDER BY upddate;"
    );
    pg_close();

    if (!is_resource($result)) {
      return null;
    }
    return pg_fetch_all($result);
  }


  function insert_update($pid, $title, $description, $filepath, $mediavideo) {
    $db_connection = get_db_connection();
    $result = pg_query($db_connection, 
      "INSERT INTO update (pid, updtitle, upddescription, updmedia, updmediavideo)
          VALUES ('$pid', '$title', '$description', '$filepath', '$mediavideo')"
    );
    
    pg_close();

    return is_resource($result);
  };


// EVENTS
  function get_events_from($uid, $date) {
    $db_connection = get_db_connection();
    $result = pg_query($db_connection, 
      "SELECT *
         FROM events_view
              CROSS JOIN follows
        WHERE uid1 = '$uid' AND
              uid = uid2 AND
              date >= '$date'::timestamp
        ORDER BY date;"
    );
    pg_close();

    if (!is_resource($result)) {
      return null;
    }
    return pg_fetch_all($result);
  }


?>