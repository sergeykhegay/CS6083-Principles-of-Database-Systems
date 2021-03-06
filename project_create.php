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
        $default_min = "200";
        $default_max = "500";
        $default_days = "30";

        if ($_SERVER["REQUEST_METHOD"] == "GET") {
          // data
          $title = test_input($_GET["title"]);
          $description = test_input($_GET["description"]);
          $category = test_input($_GET["category"]);
          $filepath = test_input($_GET["filepath"]);
          $days = test_input($_GET["days"]);
          $min = test_input($_GET["min"]);
          $max = test_input($_GET["max"]);

          // flags
          $title_empty = isset($_GET["title_empty"]);
          $description_empty = isset($_GET["description_empty"]);
          $category_empty = isset($_GET["category_empty"]);
          $filepath_empty = isset($_GET["filepath_empty"]);
          $wrong_minmax = isset($_GET["wrong_minmax"]);

          // Display errors
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
          if ($category_empty) {
            echo "<div class=\"alert alert-danger\">
                   <strong>Error!</strong> Category cannot be empty.
                  </div>";
          }
          if ($filepath_empty) {
            echo "<div class=\"alert alert-danger\">
                   <strong>Error!</strong> You have to upload an image.
                  </div>";
          }
          if ($wrong_minmax) {
            echo "<div class=\"alert alert-danger\">
                    <strong>Error!</strong> Inconsistent minimum and maximum amounts.
                  </div>";
          }
        }
      ?>
      <div class="page-header">
        <h1>Create a New Project <small>Make the world a better place...</small></h1>
      </div>
      <form class="form-horizontal" method="post" action="./project_create_handler.php">
        
        <!-- project title -->
        <div class="form-group row <?php if ($title_empty) echo "has-error";?>">
          <label for="inputTitle" class="col-sm-2 col-form-label">Title *</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="inputTitle" 
                   aria-describedby="titleHelp" placeholder="Enter title" name="title"
                   <?php if (!$title_empty) echo "value='$title'";?>
            >
          </div>
        </div>

        <!-- description -->
        <div class="form-group row <?php if ($description_empty) echo "has-error";?>">
          <label for="inputDescription" class="col-sm-2 col-form-label">Description *</label>
          <div class="col-sm-10">
            <textarea class="form-control" rows="4" id="inputDescription" 
                   aria-describedby="descriptionHelp" placeholder="Enter description" name="description"
                   
            ><?php if (!$description_empty) echo "$description";?></textarea>
          </div>
        </div>

        <!-- category -->
        <div class="form-group row <?php if ($category_empty) echo "has-error";?>">
          <label for="inputCategory" class="col-sm-2 col-form-label">Category *</label>
          <div class="col-sm-10">
            <select class="form-control" id="inputCategory" 
                   aria-describedby="categoryHelp" name="category">
              <option <?php if ($category === "Art") echo "selected"; ?>>Art</option>
              <option <?php if ($category === "Comics") echo "selected"; ?>>Comics</option>
              <option <?php if ($category === "Crafts") echo "selected"; ?>>Crafts</option>
              <option <?php if ($category === "Music") echo "selected"; ?>>Music</option>
              <option <?php if ($category === "Theater") echo "selected"; ?>>Theater</option>
              <option <?php if ($category === "Food") echo "selected"; ?>>Food</option>
            </select>
          </div>
        </div>

        <!-- image-->
        <div class="form-group row <?php if ($filepath_empty) echo "has-error";?>">
          <label for="inputImage" class="col-sm-2 col-form-label">Image *</label>
          <div class="col-sm-10">
            <div class="input-group">
              <input type="hidden" class="form-control" id="inputFilePath" name="filepath" value=<?php echo "$filepath" ?>>
              <input type="file" class="form-control" id="inputFile" name="filename">
              <div class="input-group-btn">
                <button type="button" class="btn btn-default" id="uploadButton">Upload</button>
              </div>
              
            </div>
            <small id="fileHelp" class="form-text text-muted"><?php 
                  if (empty($filepath)) {
                    echo "<span style='color:red'>No image uploaded<span>";
                  } else {
                    echo "File is uploaded to: <a href='$filepath' target=blank>$filepath</a>";
                  }
            ?>
            </small>
          </div>
        </div>

        <!-- days -->
        <div class="form-group row">
          <label for="inputNumber" class="col-sm-2 col-form-label">Funding Period</label>
          <div class="col-sm-10">
            <input type="number" class="form-control" id="inputNumber" name="days" 
                   <?php if (isset($days)) echo "value=$days"; ?> >
          </div>
        </div>
        
        <!-- min and max amount -->
        <div class="form-group row <?php if ($wrong_minmax) echo "has-error";?>">
          <label for="inputMin" class="col-sm-2 col-form-label">Required</label>
          <div class="col-sm-4">
            <input type="number" class="form-control" id="inputMin" name="min" 
                   value="<?php echo(isset($min)) ? ($min) : ($default_min);?>">
            <small id="minHelp" class="form-text text-muted">In USD. Minimum amount requered for successful funding.</small>
          </div>
          <label for="inputMax" class="col-sm-2 col-form-label">Stop Funding At</label>
          <div class="col-sm-4">
            <input type="number" class="form-control" id="inputMax" name="max" 
                   value="<?php if (isset($max)) echo $max; ?>">
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