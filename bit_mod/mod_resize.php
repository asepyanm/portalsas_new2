<?php

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
function create_right_size_image($image, $width=720,$ext)
{
// IS GD HERE?
   $gdv = get_gd_info();
   if (!$gdv) return FALSE;

// GET AN IMAGE THING
   switch(strtolower($ext))
   {
	  case '.jpg':
		$source = imagecreatefromjpeg($image) ; //  replace source  with $file
		break;
	  
	  case 'jpeg':
		$source = imagecreatefromjpeg($image) ; //  replace source  with $file
		break;
	  
	  case '.gif':
		$source = imagecreatefromgif($image) ; //  replace source  with $file
		break;
	 
	  case '.png':
		$source = imagecreatefrompng($image) ; //  replace source  with $file
		break;
	}



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

?>