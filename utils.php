<?php
  function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  };

  function get_db_connection() {
    return pg_connect("host=localhost dbname=postgres user=postgres");
  };

// USER
  function user_exists($uid) {
    $db_connection = get_db_connection();
      $result = pg_query($db_connection, 
        "SELECT * 
           FROM users
          WHERE uid='$uid';"
      );
      
      return pg_num_rows($result) == 1;
  };

  function insert_user($uid, $password_hash) {
    $db_connection = get_db_connection();
    $result = @pg_query($db_connection, 
      "INSERT INTO users (uid, upasswordhash) 
          VALUES ('$uid', '$password_hash');"
    );
    
    return is_resource($result); // true or falqse
  };

  function get_upasswordhash($uid) {
    $db_connection = get_db_connection();
    $result = @pg_query($db_connection, 
      "SELECT upasswordhash
         FROM users
        WHERE uid='$uid';"
    );

    return pg_fetch_array($result);
  }
// PROJECT
  function get_projects($category){
    $db_connection = get_db_connection();
    if($category == null){
      $result = pg_query
      ($db_connection, "SELECT * FROM project");
    }
    else{
      $result = pg_query
      ($db_connection, "SELECT * FROM project WHERE Lower(catname) = '$category';");
    }
    return $result;
  }

// PLEDGE
  function get_pledges($uid){
    $db_connection = get_db_connection();
    $result = pg_query
    ($db_connection, "SELECT * FROM pledge natural join project WHERE uid = '$uid';");
    
    return $result;
  }
// PRODUCT
  function product_exists_and_available($pname) {
    $db_connection = get_db_connection();
    $result = pg_query($db_connection, 
      "SELECT * 
         FROM product 
        WHERE pname='$pname' AND 
              (pstatus='available' OR pstatus='backordered');"
    );
    
    return pg_num_rows($result) == 1;
  };

  function get_products($keyword) {
    $db_connection = get_db_connection();
    $result = null;

    if (empty($keyword)) {
      $result = pg_query($db_connection, "SELECT * FROM product;");
    }
    else {
      $result = pg_query($db_connection, 
        "SELECT * 
         FROM product 
         WHERE pdescription ILIKE '%$keyword%' OR
               pname ILIKE '%$keyword%';");
    }
    
    if (!is_resource($result)) {
      return null;
    }
    return pg_fetch_all($result);
  };

// PURCHASES
  function get_orders($username) {
    $db_connection = get_db_connection();
    $result = pg_query($db_connection, 
      "SELECT * 
       FROM purchase 
       WHERE cname='$username'
       ORDER BY putime DESC;");
    
    if (!is_resource($result)) {
      return null;
    }

    return pg_fetch_all($result);
  };

  // returns false if no pending order exist
  function retrieve_pending_order($cname, $pname) {
    $db_connection = get_db_connection();
    $result = pg_query($db_connection, 
      "SELECT * 
       FROM purchase 
       WHERE cname='$cname' AND 
             pname='$pname' AND 
             status='pending'
       ORDER BY putime DESC;"
    );
    
    if (pg_num_rows($result) <= 0) {
      return false;
    }

    return pg_fetch_array($result);
  };

  function update_order($cname, $pname, $putime, $quantity, $price) {
    $db_connection = get_db_connection();
    $additional_price = $quantity * $price;
    $result = pg_query($db_connection, 
      "UPDATE purchase
       SET quantity = quantity + $quantity,
           puprice = puprice + $additional_price,
           putime = NOW(),
           status = 'pending'
       WHERE cname='$cname' AND 
             pname='$pname' AND 
             putime='$putime'::timestamp AND
             status='pending';"
    );
    
    return is_resource($result);
  };

  function insert_order($cname, $pname, $quantity, $price) {
    $db_connection = get_db_connection();
    $total_price = $quantity * $price;
    $result = @pg_query($db_connection, 
      "INSERT INTO purchase 
        VALUES ('$cname', '$pname', NOW(), '$quantity', '$total_price', 'pending');"
    );
    
    return is_resource($result);
  };
?>