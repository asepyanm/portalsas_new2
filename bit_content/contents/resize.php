  <?php // RAY_upload.php UPLOAD AN IMAGE AND RESIZE IT TO FIT A PREDEFINED SIZE


// A FUNCTION TO DETERMINE IF GD IS AT LEVEL 2 OR MORE
function get_gd_info($display=FALSE)
{

// IS GD INSTALLED AT ALL?
   if (!function_exists("gd_info"))
   {
      if ($display) echo "<br/>GD NOT INSTALLED\n";
      return FALSE;
   }

// IF GD IS INSTALLED GET DETAILS
   $gd = gd_info();

// IF DISPLAY IS REQUESTED, PRINT DETAILS
   if ($display)
   {
      echo "<br/>GD DETAILS:\n";
      foreach ($gd as $key => $value)
      {
         if ($value === TRUE)  $value = 'YES';
         if ($value === FALSE) $value = 'NO';
         echo "<br/>$key = $value \n";
      }
   }

// RETURN THE VERSION NUMBER
   $gd_version = preg_replace('/[^0-9\.]/', '', $gd["GD Version"]);;
   return $gd_version;
}


// A FUNCTION TO MAKE AN IMAGE INTO THE RIGHT WIDTH FOR PAGE DISPLAY
// WILL WORK IF GD2 NOT INSTALLED, BUT WILL MAKE BETTER IMAGES WITH GD2
// INPUT IS THE IMAGE FILE NAME, OUTPUT IS AN IMAGE RESOURCE, OR FALSE IF NO RESIZE NEEDED
function create_right_size_image($image, $width=720)
{
// IS GD HERE?
   $gdv = get_gd_info();
   if (!$gdv) return FALSE;

// GET AN IMAGE THING
   $source = imagecreatefromjpeg("$image");

// GET THE X AND Y DIMENSIONS
   $imageX = imagesx($source);
   $imageY = imagesy($source);

// IF NO RESIZING IS NEEDED
   if ($imageX <= $width)
   {
      return FALSE;
   }

// THE WIDTH IS TOO GREAT - MUST RESIZE
   $tnailX = $width;
   $tnailY = (int) (($tnailX * $imageY) / $imageX );

// WHICH FUNCTIONS CAN RESIZE / RESAMPLE THE IMAGE?
   if ($gdv >= 2)
   {
// IF GD IS AT LEVEL 2 OR ABOVE
      $target = imagecreatetruecolor($tnailX, $tnailY);
      imagecopyresampled ($target, $source, 0, 0, 0, 0, $tnailX, $tnailY, $imageX, $imageY);
   } else
   {
// IF GD IS AT A LOWER REVISION LEVEL
      $target = imagecreate($tnailX, $tnailY);
      imagecopyresized   ($target, $source, 0, 0, 0, 0, $tnailX, $tnailY, $imageX, $imageY);
   }
   return $target ;
}
/* ********************************************************************************************* */


// ESTABLISH THE NAME OF THE PHOTOS DIRECTORY
$photos    = 'photos';

// ESTABLISH THE LARGEST FILE WE WILL UPLOAD
$max_file_size = '5000000'; // A BIT MORE THAN 4MB

// THIS IS A LIST OF THE POSSIBLE ERRORS THAT CAN BE REPORTED in $_FILES[]["error"]
$errors    = array(
    0=>"Success!",
    1=>"The uploaded file exceeds the upload_max_filesize directive in php.ini",
    2=>"The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
    3=>"The uploaded file was only partially uploaded",
    4=>"No file was uploaded",
    6=>"Missing a temporary folder",
    7=>"Cannot write file to disk"
);

// IF THERE IS NOTHING USEFUL IN $_POST, PUT UP THE FORM FOR INPUT
if ( (empty($_POST['p'])) && (empty($_POST['MAX_FILE_SIZE'])) ) {
    ?>
    <h2>Upload Photos</h2>

    <!-- ENCTYPE -->
    <form name="UploadForm" enctype="multipart/form-data" action="<?=$_SERVER["REQUEST_URI"]?>" method="POST">
    <input type="hidden" name="p" value="1" />
    <!-- MAX_FILE_SIZE must precede the file input field -->
    <input type="hidden" name="MAX_FILE_SIZE" value="<?=$max_file_size?>" />
    <!-- INPUT NAME= IN TYPE=FILE DETERMINES THE NAME FOR ACTION SCRIPT TO USE IN $_FILES ARRAY -->
    <p>
    Find the photo you want to upload and click the "Upload" button below.
    </p>

    <table cellpadding="1" cellspacing="1" border="0">
    <tr><td align="right"><span class="required">Photo: </span></td> <td><input name="userfile" type="file" size="80" /></td></tr>
    <tr><td> </td><td><input type="submit" name="_submit" value="Upload" />
    &nbsp; &nbsp; Check this box <input autocomplete="off" type="checkbox" name="overwrite" /> to <b>overwrite</b> an existing photo.</td></tr>
    </table>
    </form>
    <?php

    die();

} else {

// THERE IS POST DATA - PROCESS IT
    echo "<h2>Results: Upload Photos</h2>\n";
    echo "<p>\n";

// SYNTHESIZE THE NEW FILE NAME
    $f_type    = trim(strtolower(end    (explode( '.', basename($_FILES['userfile']['name'] )))));
    $f_name    = trim(strtolower(current(explode( '.', basename($_FILES['userfile']['name'] )))));
    $my_new = getcwd() . '/' . $photos . '/' . $f_name .'.'. $f_type;
    $my_file= $photos . '/' . $f_name .'.'. $f_type;

// TEST FOR ALLOWABLE EXTENSIONS
    if ($f_type != 'jpg')
    {
       die('Sorry, only JPG files allowed');
    }

// IF THERE ARE ERRORS
    $error_code    = $_FILES["userfile"]["error"];
    if ($error_code != 0)
    {
        $error_message = $errors[$error_code];
        echo "<p class=\"required\">Upload Error Code: $error_code: $error_message</p>\n";
        die('Sorry');
    }

// MOVE THE FILE INTO THE DIRECTORY
    $overwrite    = $_POST['overwrite'];
    $file_size    = number_format($_FILES["userfile"]["size"]);

// IF THE FILE IS NEW
    if (!file_exists($my_new))
    {
        if (move_uploaded_file($_FILES['userfile']['tmp_name'], $my_new))
        {
            $upload_success = 1;
        } else
        {
            $upload_success = -1;
        }

// IF THE FILE ALREADY EXISTS
    } else
    {
        echo "<b><i>$my_file</i></b> already exists.\n";

// SHOULD WE OVERWRITE THE FILE? IF NOT
        if (empty($overwrite))
        {
            $upload_success = 0;

// IF WE SHOULD OVERWRITE THE FILE, TRY TO MAKE A BACKUP
        } else
        {
            $now    = date('Y-m-d');
            $my_bak = $my_new . '.' . $now . '.bak';
            if (!copy($my_new, $my_bak))
            {
                echo "<b>Attempted Backup Failed!</b>\n";
            }
            if (move_uploaded_file($_FILES['userfile']['tmp_name'], $my_new))
            {
                $upload_success = 2;
            } else
            {
                $upload_success = -1;
            }
        }
    }

// REPORT OUR SUCCESS OR FAILURE
    if ($upload_success == 2) { echo "It has been overwritten.\n"; }
    if ($upload_success == 1) { echo "<b><i>$my_file</i></b> has been saved.\n"; }
    if ($upload_success == 0) { echo "<b>It was NOT overwritten.</b>\n"; }

    if ($upload_success > 0)
    {
        echo "$file_size bytes uploaded.\n";
        chmod ($my_new, 0755);
    }

    echo "</p>\n";

// RESIZE THE FILE TO FIT PAGE WIDTH, IF NECESSARY
    if ($upload_success > 0)
    {
        if ($imageblob    = create_right_size_image($my_new,50))
        {
            imagejpeg($imageblob, $my_new);
        }
    }

    echo "<p><a href=\"$my_file\">See the file</a></p>\n";


    die('Done');
}
