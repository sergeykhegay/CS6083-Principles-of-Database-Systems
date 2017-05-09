<?php require_once "./utils.php"; ?>
<?php
  session_start();
  require_once "./require_login.php"; 
?>

<!DOCTYPE html>
<html lang="en">
  <?php $_title = "Create Project @ Cabbage"; include "./inc_head.inc";?>
  <body>
    <div class="container">
      <?php include "./inc_navbar.inc"; ?>

      <?php
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
          // flags
          $email_empty = isset($_GET["email_empty"]);
          $password_empty = isset($_GET["password_empty"]);
          $login_failed = isset($_GET["login_failed"]);

          // Display errors
          if ($email_empty) {
            echo "<div class=\"alert alert-danger\">
                   <strong>Error!</strong> Field 'email' cannot be empty.
                  </div>";
          }
          if ($password_empty) {
            echo "<div class=\"alert alert-danger\">
                   <strong>Error!</strong> Field 'password' cannot be empty.
                  </div>";
          }
          if ($login_failed) {
            echo "<div class=\"alert alert-danger\">
                    <strong>Error!</strong> Forgot your password? Well, we can do nothing... Or maybe you forgot to sign up <a href=\"./user_signup.php\">here</a>?
                  </div>";
          }
        }
      ?>
      
      <form method="post" action="./project_create_handler.php">
        
        <!-- project title -->
        <div class="form-group row">
          <label for="inputTitle" class="col-sm-2 col-form-label">Title *</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="inputTitle" 
                   aria-describedby="titleHelp" placeholder="Enter title" name="title"
                   <?php if (!$title_empty && $fillin_form) echo "value='$title'";?>
            >
          </div>
        </div>

        <!-- description -->
        <div class="form-group row">
          <label for="inputDescription" class="col-sm-2 col-form-label">Description *</label>
          <div class="col-sm-10">
            <textarea class="form-control" rows="4" id="inputDescription" 
                   aria-describedby="descriptionHelp" placeholder="Enter description" name="description"
                   <?php if (!$description_empty && $fillin_form) echo "value='$description'";?>
            ></textarea>
          </div>
        </div>

        <!-- category -->
        <div class="form-group row">
          <label for="inputCategory" class="col-sm-2 col-form-label">Category *</label>
          <div class="col-sm-10">
            <select class="form-control" id="inputCategory" 
                   aria-describedby="categoryHelp" name="category"
                   <?php if (!$category_empty && $fillin_form) echo "value='$category'";?>
                   selected="Comics"
            >
              <option>Art</option>
              <option>Comics</option>
              <option>Crafts</option>
              <option>Music</option>
              <option>Theater</option>
              <option>Food</option>
            </select>
          </div>
        </div>

        <!-- image-->
        <div class="form-group row">
          <label for="inputImage" class="col-sm-2 col-form-label">Image *</label>
          <div class="col-sm-9">
            <input type="hidden" class="form-control" id="inputFilePath" name="filepath" value=<?php echo "$filepath" ?>>
            <input type="file" class="form-control" id="inputFile" name="filename">
            <small id="fileHelp" class="form-text text-muted">
              <?php 
                if (empty($filepath)) {
                  echo "No image uploaded";
                } else {
                  $name = basename($filepath);
                  echo "File $name is uploaded";
                }
              ?>
            </small>
            <small id="fileHelp2" class="form-text text-muted"></small>
          </div>
          <!-- <div class="col-sm-3 progress progress-striped">
            <div class="progress-bar" style="width: 60%;">
              <span class="sr-only">60% Complete</span>
            </div>
          </div> -->
          <div class="col-sm-1">
            <button type="button" class="btn btn-default" id="uploadButton">Upload</button>
          </div>  
        </div>

        <!-- days -->
        <div class="form-group row">
          <label for="inputNumber" class="col-sm-2 col-form-label">Funding Period</label>
          <div class="col-sm-10">
            <input type="number" class="form-control" id="inputNumber" name="days" value="30">
          </div>
        </div>
        
        <!-- min and max amount -->
        <div class="form-group row">
          <label for="inputMin" class="col-sm-2 col-form-label">Requeried</label>
          <div class="col-sm-4">
            <input type="number" class="form-control" id="inputMin" name="min" value="100">
            <small id="minHelp" class="form-text text-muted">In USD. Minimum amount requered for successful funding.</small>
          </div>
          <label for="inputMax" class="col-sm-2 col-form-label">Stop Funding At</label>
          <div class="col-sm-4">
            <input type="number" class="form-control" id="inputMax" name="max" value="200">
            <small id="maxHelp" class="form-text text-muted">In USD. Funding will successfully finish when this amount is reached.</small>
          </div>
        </div>

        <div class="form-group row">
          <div class="offset-sm-10 col-sm-2">
            <button type="submit" class="btn btn-primary">Start Campaign!</button>
          </div>
        </div>
      </form>

    </div>  <!-- container --> 
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>

    <script type="text/javascript">
      function beforeSubmit(){
        //check whether client browser fully supports all File API
        if (window.File && window.FileReader && window.FileList && window.Blob) {
          var fsize = $('#inputFile')[0].files[0].size; //get file size
          var ftype = $('#inputFile')[0].files[0].type; // get file type
          //allow file types 
          switch (ftype) {
            case 'image/png': 
            case 'image/gif': 
            case 'image/jpeg': 
            case 'image/pjpeg':
            case 'video/mp4':
              break;
            default:
              $("#fileHelp2").html("<span class='warning'>"+ftype+"</span>. Unsupported file type!");
              return false
          }
        
          //Allowed file size is less than 5 MB (1048576 = 1 mb)
          if (fsize > 5242880) {
            alert("<b>"+fsize +"</b> Too big file! <br />File is too big, it should be less than 5 MB.");
            return false
          }
        } else {
          //Error for older unsupported browsers that doesn't support HTML5 File API
          alert("Please upgrade your browser, because your current browser lacks some new features we need!");
          return false
        }
    };

    $(document).ready(function() {
      $('#uploadButton').on('click', function() {
        if (beforeSubmit() === null) {
          return false;
        }

        var data = new FormData();
        data.append('filename', $('#inputFile')[0].files[0]);
        console.log($('#inputFile')[0].files);
        $.ajax({
          type: 'POST',               
          processData: false, // important
          contentType: false, // important
          data: data,
          url: "./upload.php",
          dataType : 'json',
          success: function(data, textStatus, jqXHR) {
            if (typeof data.error === 'undefined') {
              console.log(data);
              filepath = data["filepath"];
              $('#fileHelp').html("File is uploaded to: <a href='" + filepath + "' target=blank>" + filepath + "</a>");
              // $('#inputFile').val("");
              $('#inputFilePath').val(filepath);
            } else {
              console.log('ERRORS success: ' + data.error);
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
            console.log('ERRORS error: ' + textStatus);
          }
        }); 

        return false; 
      }); 
    });
    </script>

  </body>
</html>