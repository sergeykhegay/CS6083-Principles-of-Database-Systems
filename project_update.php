<?php require_once "./utils.php"; ?>
<?php
  session_start();
  require_once "./require_login.php"; 
?>
<!DOCTYPE html>
<html lang="en">
  <?php $_title = "Update Project @ Cabbage"; include "./inc_head.inc";?>
  <body>
    <div class="container">
      <?php include "./inc_navbar.inc"; ?>

      <?php
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
          // data
          $uid = test_input($_SESSION["uid"]);
          $pid = test_input($_GET["pid"]);
          $project = @get_project_info($pid);

          $title = test_input($_GET["title"]);
          $description = test_input($_GET["description"]);
          $filepath = test_input($_GET["filepath"]);

          // flags
          $invalid_user = isset($_GET["invalid_user"]);
          $title_empty = isset($_GET["title_empty"]);
          $description_empty = isset($_GET["description_empty"]);
          $filepath_empty = isset($_GET["filepath_empty"]);
          $mediavideo_empty = isset($_GET["mediavideo_empty"]);

          $pid_empty = !isset($pid) || empty($pid);
          $project_exists = isset($project) && !empty($project);     

          // Display errors

          if ($pid_empty || $invalid_user || !$project_exists) {
            echo "<div class=\"alert alert-danger\">
                   <strong>Error!</strong> Ooops! Something went wrong. Please return to your <a href='./dashboard_projects.php'>dashboard</a> and try again.
                  </div>";
          }
          if ($title_empty) {
            echo "<div class=\"alert alert-danger\">
                   <strong>Error!</strong> Title cannot be empty.
                  </div>";
          }
          if ($description_empty) {
            echo "<div class=\"alert alert-danger\">
                   <strong>Error!</strong> Description cannot be empty.
                  </div>";
          }
          if ($filepath_empty) {
            echo "<div class=\"alert alert-danger\">
                   <strong>Error!</strong> You have to upload an image or a video.
                  </div>";
          }
        }
      ?>
      <div class="page-header">
        <h1>Update Project <small>Attract more supporters</small></h1>
        <h4>Project title:</h4> <small></small>
      </div>
      <form class="form-horizontal" method="post" action="./project_update_handler.php">
        
        <input type="hidden" class="form-control" id="inputPid" name="pid" value=<?php echo "$pid" ?>>

        <!-- update title -->
        <div class="form-group row <?php if ($title_empty) echo "has-error";?>">
          <label for="inputTitle" class="col-sm-2 col-form-label">Update Title *</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="inputTitle" 
                   aria-describedby="titleHelp" placeholder="Enter title" name="title"
                   <?php if (!$title_empty) echo "value='$title'";?>
            >
          </div>
        </div>

        <!-- description -->
        <div class="form-group row <?php if ($description_empty) echo "has-error";?>">
          <label for="inputDescription" class="col-sm-2 col-form-label">Update Description *</label>
          <div class="col-sm-10">
            <textarea class="form-control" rows="4" id="inputDescription" 
                   aria-describedby="descriptionHelp" placeholder="Enter description" name="description"
                   
            ><?php if (!$description_empty) echo "$description";?></textarea>
          </div>
        </div>

        <!-- media-->
        <div class="form-group row <?php if ($filepath_empty) echo "has-error";?>">
          <label for="inputImage" class="col-sm-2 col-form-label">Media *</label>
          <div class="col-sm-9">
            <input type="hidden" class="form-control" id="inputFilePath" name="filepath" value=<?php echo "$filepath" ?>>
            <input type="file" class="form-control" id="inputFile" name="filename">
            
            <small id="fileHelp" class="form-text text-muted">
              <?php 
                if (empty($filepath)) {
                  echo "<span style='color:red'>No image uploaded<span>";
                } else {
                  echo "File is uploaded to: <a href='$filepath' target=blank>$filepath</a>";
                }
              ?>
            </small>
            
            <p><input id="mediaTypeInput" type="checkbox" name="mediavideo" <?php if (!$mediavideo_empty) echo "checked"?>> I'm uploading a video file</p>
          </div>

          <div class="col-sm-1">
            <button type="button" class="btn btn-default" id="uploadButton">Upload</button>
          </div>  
        </div>

        <div class="form-group row">
          <div class="offset-sm-10 col-sm-2">
            <button type="submit" class="btn btn-primary">Post Update!</button>
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
              alert("Unsupported file type!");
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
              if (data["success"]) {
                filepath = data["filepath"];
                $('#fileHelp').html("File is uploaded to: <a href='" + filepath + "' target=blank>" + filepath + "</a>");
                $('#inputFilePath').val(filepath);
              } else {
                $('#fileHelp').html("<span style='color:red'>" + data["message"] + "<span>");
                $('#inputFilePath').val("");
              }
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