<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="./">
        <img alt="Cabbage Logo" src="./images/logo64.png" height="20" width="20">
      </a>
    </div>

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      
      <?php
        if (isset($_SESSION["cname"])) {
          echo "
            <ul class='nav navbar-nav'>
              <li><a href='./user/dashboard.php'>Dashboard</a></li>
              <li><a href='./'>Projects</a></li>
            </ul>
            <p class='navbar-text navbar-right'><a href='./user_logout.php' class='navbar-link'>Log out.</a></p>
            <p class='navbar-text navbar-right'>Signed in as <a href='./orders.php' class='navbar-link'>{$_SESSION["cname"]}.</a></p>
          ";
        } else {
          echo "
            <ul class='nav navbar-nav'>
              <li><a href='./'>Projects</a></li>
            </ul>
            <p class='navbar-text navbar-right'><a href='./user_login.php' class='navbar-link'>Log in.</a></p>
            <p class='navbar-text navbar-right'><a href='./user_signup.php' class='navbar-link'>Sign up.</a></p>;
          ";
        }
      ?>
    </div><!-- /.navbar-collapse -->

  </div><!-- /.container-fluid -->
</nav>