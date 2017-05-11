<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="./">
        <img alt="Cabbage Logo" src="./images/logo64.png" height="20" width="20">
      </a>
    </div>

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      
      <?php
        if (isset($_SESSION["uid"])) {
          echo "
            <ul class='nav navbar-nav'>
              <li class='dropdown'>
                <a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>Dashboard <span class='caret'></span></a>
                <ul class='dropdown-menu'>
                  <li><a href='./dashboard.php'>Events</a></li>
                  <li><a href='./dashboard_projects.php'>Projects</a></li>
                  <li><a href='./dashboard_pledges.php'>Pledges</a></li>
                  <li role='separator' class='divider'></li>
                  <!-- <li><a href='./dashboard_profile.php'>Profile</a></li> -->
                </ul>
              </li>
              <li><a href='./'>Explore</a></li>
              <li><a href='./project_create.php?min=200&max=500&days=30'>Create</a></li>
              <!-- <li><a href='./user.php'>Connect</a></li> -->
            </ul>
            <p class='navbar-text navbar-right'><a href='./user_logout.php' class='navbar-link'>Log out</a></p>
            <p class='navbar-text navbar-right'>Signed in as <a href='./dashboard_profile.php' class='navbar-link'>{$_SESSION["uid"]}</a></p>
          ";
        } else {
          echo "
            <ul class='nav navbar-nav'>
              <li><a href='./'>Explore</a></li>
            </ul>
            <p class='navbar-text navbar-right'><a href='./user_login.php' class='navbar-link'>Log in</a></p>
            <p class='navbar-text navbar-right'><a href='./user_signup.php' class='navbar-link'>Sign up</a></p>;
          ";
        }
      ?>
    </div><!-- /.navbar-collapse -->

  </div><!-- /.container-fluid -->
</nav>