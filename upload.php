<?php require_once "./utils.php"; ?>
<?php
// Credits: https://www.sanwebe.com/2012/06/ajax-file-upload-with-php-and-jquery

if (isset($_FILES["filename"]) && $_FILES["filename"]["error"]== UPLOAD_ERR_OK) {
    ############ Edit settings ##############
    $UploadDirectory    = './uploads/'; //specify upload directory ends with / (slash)
    ##########################################
    /*
    Note : You will run into errors or blank page if "memory_limit" or "upload_max_filesize" is set to low in "php.ini". 
    Open "php.ini" file, and search for "memory_limit" or "upload_max_filesize" limit 
    and set them adequately, also check "post_max_size".
    */
    
    //check if this is an ajax request
    if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
        die("Here");
    }
    
    //Is file size is less than allowed size.
    if ($_FILES["filename"]["size"] > 5242880) {
        $data = array('success' => false, 
                      'message' => "File is too big.");
        echo json_encode($data);
        die();
    }
    
    // allowed file type Server side check
    switch (strtolower($_FILES['filename']['type'])) {
        //allowed file types
        case 'image/png': 
        case 'image/gif': 
        case 'image/jpeg': 
        case 'image/pjpeg':
        // case 'text/plain':
        // case 'text/html': //html file
        // case 'application/x-zip-compressed':
        // case 'application/pdf':
        // case 'application/msword':
        // case 'application/vnd.ms-excel':
        case 'video/mp4':
            break;
        default:
            $data = array('success' => false, 
                          'message' => "Unsupported format.");
            echo json_encode($data);
            die();
    }

    date_default_timezone_set("UTC");
    $File_Name          = strtolower($_FILES['filename']['name']);
    $File_Ext           = substr($File_Name, strrpos($File_Name, '.')); //get file extention
    $Random_Number      = rand(0, 9999999999); //Random number to be added to name.
    $Date               = date("y-m-d-H-i-s-");
    $NewFileName        = $Date.$Random_Number.$File_Ext; //new file name
    

    if (move_uploaded_file($_FILES['filename']['tmp_name'], $UploadDirectory.$NewFileName )) {
        // do other stuff 
        $data = array('success' => true, 
                      'filepath' => $UploadDirectory.$NewFileName);
        echo json_encode($data);
        die();
    } else {
        $data = array('success' => false, 
                      'message' => "Error occured while uploading.");
        echo json_encode($data);
    }    
} else {
    $data = array('success' => false, 
                  'message' => "Server error. Please report to the technical support. Try smaller image size.");
    echo json_encode($data);
    // die('Something wrong with upload! Is "upload_max_filesize" set correctly?');
    die();
}

?>