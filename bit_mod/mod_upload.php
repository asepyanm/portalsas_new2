<?php
/**
 *
 * class uploader
 * 
 * Copyright 1999, 2002, 2003 David Fox, Dave Tufts
 * 
 * Usage, setup, and license at the bottom of this page (README)
 * 
 * @version:      2.15
 * @last_update:  2004-02-18
 * @description:  PHP file upload class
 * @requires:	  PHP 4.1 or higher
 * 
 * 
 *	METHODS:
 *		uploader()	 	- constructor, sets error message language preference
 *		max_filesize() 		- set a max filesize in bytes
 *		max_image_size() 	- set max pixel dimenstions for image uploads
 *		upload() 		    - checks if file is acceptable, uploads file to server's temp directory
 *		save_file() 		- moves the uploaded file and renames it depending on the save_file($overwrite_mode)
 *	
 *		cleanup_text_file()	- (PRIVATE) convert Mac and/or PC line breaks to UNIX
 *		get_error() 		- (PRIVATE) gets language-specific error message
 * 
 *	Error code: 
 *		[0] - "No file was uploaded"
 *		[1] - "Maximum file size exceeded"
 *		[2] - "Maximum image size exceeded"
 *		[3] - "Only specified file type may be uploaded"
 *		[4] - "File already exists" (save only)
 *		[5] - "Permission denied. Unable to copy file"
 *
 */
class uploader {

	var $file;
	var $path;
	var $language;
	var $acceptable_file_types;
	var $error;
	var $errors; // Depreciated (only for backward compatability)
	var $accepted;
	var $max_filesize;
	var $max_image_width;
	var $max_image_height;
	var $error_code = -1;


	/**
	 * object uploader ([string language]);
	 * 
	 * Class constructor, sets error messaging language preference
	 * 
	 * @param language		(string) defaults to en (English).
	 * 
	 * @examples:	$f = new uploader(); 		// English error messages
	 *				$f = new uploader('fr');	// French error messages
	 * 
	 */
	function uploader ( $language = 'en' ) {
		$this->language = strtolower($language);
		$this->error   = '';
	}
	
	
	/**
	 * void max_filesize ( int size);
	 * 
	 * Set the maximum file size in bytes ($size), allowable by the object.
	 * NOTE: PHP's configuration file also can control the maximum upload size, which is set to 2 or 4 
	 * megs by default. To upload larger files, you'll have to change the php.ini file first.
	 * 
	 * @param size 			(int) file size in bytes
	 * 
	 */
	function max_filesize($size){
		$this->max_filesize = (int) $size;
	}


	/**
	 * void max_image_size ( int width, int height );
	 * 
	 * Sets the maximum pixel dimensions. Will only be checked if the 
	 * uploaded file is an image
	 * 
	 * @param width			(int) maximum pixel width of image uploads
	 * @param height		(int) maximum pixel height of image uploads
	 * 
	 */
	function max_image_size($width, $height){
		$this->max_image_width  = (int) $width;
		$this->max_image_height = (int) $height;
	}
	
	
	/**
	 * bool upload (string filename[, string accept_type[, string extension]]);
	 * 
	 * Checks if the file is acceptable and uploads it to PHP's default upload diretory
	 * 
	 * @param filename		(string) form field name of uploaded file
	 * @param accept_type	(string) acceptable mime-types
	 * @param extension		(string) default filename extenstion
	 * 
	 */
	function upload($filename='', $accept_type='', $extention='') {
		
		$this->acceptable_file_types = trim($accept_type); // used by error messages

		if (!isset($_FILES) || !is_array($_FILES[$filename]) || !$_FILES[$filename]['name']) {
			$this->error = $this->get_error(0);
			$this->accepted  = FALSE;
			return FALSE;
		}
				
		// Copy PHP's global $_FILES array to a local array
		$this->file = $_FILES[$filename];
		$this->file['file'] = $filename;
		
		// Initialize empty array elements
		if (!isset($this->file['extention'])) $this->file['extention'] = "";
		if (!isset($this->file['type']))      $this->file['type']      = "";
		if (!isset($this->file['size']))      $this->file['size']      = "";
		if (!isset($this->file['width']))     $this->file['width']     = "";
		if (!isset($this->file['height']))    $this->file['height']    = "";
		if (!isset($this->file['tmp_name']))  $this->file['tmp_name']  = "";
		if (!isset($this->file['raw_name']))  $this->file['raw_name']  = "";
				
		// test max size
		if($this->max_filesize && ($this->file["size"] > $this->max_filesize)) {
			$this->error = $this->get_error(1);
			$this->accepted  = FALSE;
			return FALSE;
		}
		
		if(stristr($this->file["type"], "image")) {
			
			/* IMAGES */
			$image = getimagesize($this->file["tmp_name"]);
			$this->file["width"]  = $image[0];
			$this->file["height"] = $image[1];
			
			// test max image size
			if(($this->max_image_width || $this->max_image_height) && (($this->file["width"] > $this->max_image_width) || ($this->file["height"] > $this->max_image_height))) {
				$this->error = $this->get_error(2);
				$this->accepted  = FALSE;
				return FALSE;
			}
			// Image Type is returned from getimagesize() function
			switch($image[2]) {
				case 1:
					$this->file["extention"] = ".gif"; break;
				case 2:
					$this->file["extention"] = ".jpg"; break;
				case 3:
					$this->file["extention"] = ".png"; break;
				case 4:
					$this->file["extention"] = ".swf"; break;
				case 5:
					$this->file["extention"] = ".psd"; break;
				case 6:
					$this->file["extention"] = ".bmp"; break;
				case 7:
					$this->file["extention"] = ".tif"; break;
				case 8:
					$this->file["extention"] = ".tiff"; break;
				case 13:
					$this->file["extention"] = ".swf"; break;
				default:
					$this->file["extention"] = $extention; break;
			}
		} elseif(!preg_match("/(\.)([a-z0-9]{3,5})$/", $this->file["name"]) && !$extention) {
			// Try and autmatically figure out the file type
			// For more on mime-types: http://httpd.apache.org/docs/mod/mod_mime_magic.html
			switch($this->file["type"]) {
				case "text/plain":
					$this->file["extention"] = ".txt"; break;
				case "text/richtext":
					$this->file["extention"] = ".txt"; break;
				default:
					break;
			}
		} else {
			$this->file["extention"] = $extention;
		}
		
		// check to see if the file is of type specified
		if($this->acceptable_file_types) {
			if(trim($this->file["type"]) && (stristr($this->acceptable_file_types, $this->file["type"]) || stristr($this->file["type"], $this->acceptable_file_types)) ) {
				$this->accepted = TRUE;
			} else { 
				$this->accepted = FALSE;
				$this->error = $this->get_error(3);
			}
		} else { 
			$this->accepted = TRUE;
		}
		
		return (bool) $this->accepted;
	}
	
	function uploadArr($filename='', $accept_type='', $extention='',$iC) {
		$this->acceptable_file_types = trim($accept_type); // used by error messages

		if (!isset($_FILES) || !is_array($_FILES[$filename]) || !$_FILES[$filename]['name'][$iC]) {
			$this->error = $this->get_error(0);
			$this->accepted  = FALSE;
			return FALSE;
		}
				
		// Copy PHP's global $_FILES array to a local array
		$this->file = $_FILES[$filename];
		
		// Initialize empty array elements
		if (!isset($this->file['extention'][$iC])) $this->file['extention'] = "";
		if (!isset($this->file['type'][$iC]))      $this->file['type']      = "";
		if (!isset($this->file['size'][$iC]))      $this->file['size']      = "";
		if (!isset($this->file['width'][$iC]))     $this->file['width']     = "";
		if (!isset($this->file['height'][$iC]))    $this->file['height']    = "";
		if (!isset($this->file['tmp_name'][$iC]))  $this->file['tmp_name']  = "";
		if (!isset($this->file['raw_name'][$iC]))  $this->file['raw_name']  = "";
				
		// test max size
		if($this->max_filesize && ($this->file["size"][$iC] > $this->max_filesize)) {
			$this->error = $this->get_error(1);
			$this->accepted  = FALSE;
			return FALSE;
		}
		
		if(stristr($this->file["type"][$iC], "image")) {
			
			/* IMAGES */
			$image = getimagesize($this->file["tmp_name"][$iC]);
			$this->file["width"][$iC]  = $image[0];
			$this->file["height"][$iC] = $image[1];
			
			// test max image size
			if(($this->max_image_width || $this->max_image_height) && (($this->file["width"][$iC] > $this->max_image_width) || ($this->file["height"][$iC] > $this->max_image_height))) {
				$this->error = $this->get_error(2);
				$this->accepted  = FALSE;
				return FALSE;
			}
			// Image Type is returned from getimagesize() function
			switch($image[2]) {
				case 1:
					$this->file["extention"][$iC] = ".gif"; break;
				case 2:
					$this->file["extention"][$iC] = ".jpg"; break;
				case 3:
					$this->file["extention"][$iC] = ".png"; break;
				case 4:
					$this->file["extention"][$iC] = ".swf"; break;
				case 5:
					$this->file["extention"][$iC] = ".psd"; break;
				case 6:
					$this->file["extention"][$iC] = ".bmp"; break;
				case 7:
					$this->file["extention"][$iC] = ".tif"; break;
				case 8:
					$this->file["extention"][$iC] = ".tiff"; break;
				case 13:
					$this->file["extention"][$iC] = ".swf"; break;
				default:
					$this->file["extention"][$iC] = $extention; break;
			}
		} elseif(!preg_match("/(\.)([a-z0-9]{3,5})$/", $this->file["name"][$iC]) && !$extention) {
			// Try and autmatically figure out the file type
			// For more on mime-types: http://httpd.apache.org/docs/mod/mod_mime_magic.html
			switch($this->file["type"][$iC]) {
				case "text/plain":
					$this->file["extention"][$iC] = ".txt"; break;
				case "text/richtext":
					$this->file["extention"][$iC] = ".txt"; break;
				default:
					break;
			}
		} else {
			$this->file["extention"][$iC] = $extention;
		}
		
		// check to see if the file is of type specified
		if($this->acceptable_file_types) {
			if(trim($this->file["type"][$iC]) && (stristr($this->acceptable_file_types, $this->file["type"][$iC]) || stristr($this->file["type"][$iC], $this->acceptable_file_types)) ) {
				$this->accepted = TRUE;
			} else { 
				$this->accepted = FALSE;
				$this->error = $this->get_error(3);
			}
		} else { 
			$this->accepted = TRUE;
		}
		
		return (bool) $this->accepted;
	}

	/**
	 * bool save_file ( string path[, int overwrite_mode] );
	 * 
	 * Cleans up the filename, copies the file from PHP's temp location to $path, 
	 * and checks the overwrite_mode
	 * 
	 * @param path				(string) File path to your upload directory
	 * @param overwrite_mode	(int) 	1 = overwrite existing file
	 * 									2 = rename if filename already exists (file.txt becomes file_copy0.txt)
	 * 									3 = do nothing if a file exists
	 * 
	 */
	function save_file($path, $overwrite_mode="2", $overwrite_name=""){
		if ($this->error) {
			return false;
		}
		
		if (strlen($path)>0) {
			if ($path[strlen($path)-1] != "/") {
				$path = $path . "/";
			}
		}
		$this->path = $path;	
		$copy       = "";	
		$n          = 1;	
		$success    = false;	
				
		if($this->accepted) {
			if (!empty($overwrite_name)) {
			    $overwrite_mode = "1";
			    
			    $this->file["name"] = $overwrite_name . $this->file["extention"];
			}

			// Clean up file name (only lowercase letters, numbers and underscores)
			$this->file["name"] = preg_replace("/[^a-z0-9._]/", "", str_replace(" ", "_", str_replace("%20", "_", strtolower($this->file["name"]))));
			
			// Clean up text file breaks
			if(stristr($this->file["type"], "text")) {
				$this->cleanup_text_file($this->file["tmp_name"]);
			}
			
			// get the raw name of the file (without its extenstion)
			if(preg_match("/(\.)([a-z0-9]{2,5})$/", $this->file["name"])) {
				$pos = strrpos($this->file["name"], ".");
				if(!$this->file["extention"]) { 
					$this->file["extention"] = substr($this->file["name"], $pos, strlen($this->file["name"]));
				}

				$this->file['raw_name'] = substr($this->file["name"], 0, $pos);
			} else {
				$this->file['raw_name'] = $this->file["name"];
				if ($this->file["extention"]) {
					$this->file["name"] = $this->file["name"] . $this->file["extention"];
				}
			}
			
			switch((int) $overwrite_mode) {
				case 1: // overwrite mode
					if (@file_exists($this->path . $this->file["name"])) {
					    if (!is_writeable($this->path . $this->file["name"])) {
					        @chmod($this->path . $this->file["name"], 0777);
					    }
					}
					
					if (@copy($this->file["tmp_name"], $this->path . $this->file["name"])) {
						$success = true;
					} else {
						$success     = false;
						$this->error = $this->get_error(5);
					}
					
					@chmod($this->path . $this->file["name"], 0775);
					break;
				case 2: // create new with incremental extention
					while(file_exists($this->path . $this->file['raw_name'] . $copy . $this->file["extention"])) {
						$copy = "_copy" . $n;
						$n++;
					}
					$this->file["name"]  = $this->file['raw_name'] . $copy . $this->file["extention"];
					if (@copy($this->file["tmp_name"], $this->path . $this->file["name"])) {
						$success = true;
					} else {
						$success     = false;
						$this->error = $this->get_error(5);
					}
					break;
				default: // do nothing if exists, highest protection
					if(file_exists($this->path . $this->file["name"])){
						$this->error = $this->get_error(4);
						$success     = false;
					} else {
						if (@copy($this->file["tmp_name"], $this->path . $this->file["name"])) {
							$success = true;
						} else {
							$success     = false;
							$this->error = $this->get_error(5);
						}
					}
					break;
			}
			
			if(!$success) { unset($this->file['tmp_name']); }
			return (bool) $success;
		} else {
			$this->error = $this->get_error(3);
			return FALSE;
		}
	}
	
	
	/**
	 * string get_error(int error_code);
	 * 
	 * Gets the correct error message for language set by constructor
	 * 
	 * @param error_code		(int) error code
	 * 
	 */
	function get_error($error_code='') {
		$error_message = array();
		$error_code    = (int) $error_code;
		
		$this->error_code = $error_code;
		
		switch ( $this->language ) {
			// Indonesia (id)
			case 'id':
				$error_message[0] = "Tidak ada file yang diupload";
				$error_message[1] = "File tidak boleh lebih dari " . $this->max_filesize/1000 . " KB (" . $this->max_filesize . " bytes).";
				$error_message[2] = "Image pixel tidak boleh lebih dari " . $this->max_image_width . " x " . $this->max_image_height . " pixels.";
				$error_message[3] = "Hanya " . str_replace("|", " or ", $this->acceptable_file_types) . " file yang diperkenankan.";
				$error_message[4] = "File '" . $this->path . $this->file["name"] . "' already exists.";
				$error_message[5] = "Permintaan ditolak. Tidak dapat mengupload ke direktori  '" . $this->path . "'";
			break;
			// English
			default:
				$error_message[0] = "No file was uploaded";
				$error_message[1] = "Maximum file size exceeded. File may be no larger than " . $this->max_filesize/1000 . " KB (" . $this->max_filesize . " bytes).";
				$error_message[2] = "Maximum image size exceeded. Image may be no more than " . $this->max_image_width . " x " . $this->max_image_height . " pixels.";
				$error_message[3] = "Only " . str_replace("|", " or ", $this->acceptable_file_types) . " files may be uploaded.";
				$error_message[4] = "File '" . $this->path . $this->file["name"] . "' already exists.";
				$error_message[5] = "Permission denied. Unable to copy file to '" . $this->path . "'";
			break;
		}
		
		// for backward compatability:
		$this->errors[$error_code] = $error_message[$error_code];
		
		return $error_message[$error_code];
	}


	/**
	 * void cleanup_text_file (string file);
	 * 
	 * Convert Mac and/or PC line breaks to UNIX by opening
	 * and rewriting the file on the server
	 * 
	 * @param file			(string) Path and name of text file
	 * 
	 */
	function cleanup_text_file($file){
		// chr(13)  = CR (carridge return) = Macintosh
		// chr(10)  = LF (line feed)       = Unix
		// Win line break = CRLF
		$new_file  = '';
		$old_file  = '';
		$fm_contents = file($file);
		while (list ($line_num, $line) = each($fm_contents)) {
			$old_file .= $line;
			$new_file .= str_replace(chr(13), chr(10), $line);
		}
		if ($old_file != $new_file) {
			// Open the uploaded file, and re-write it with the new changes
			$fp = fopen($file, "w");
			fwrite($fp, $new_file);
			fclose($fp);
		}
	}

}


/*
<readme>
	
	SETUP:
		Make sure that the directory that you plan on uploading 
		files to has enough permissions for your web server to 
		write/upload to it. (usually, this means making it world writable)
			- cd /your/web/dir
			- chmod 777 <fileupload_dir>
		
		The HTML FORM used to upload the file should look like this:
		<form method="post" action="upload.php" enctype="multipart/form-data">
			<input type="file" name="userfile"> 
			<input type="submit" value="Submit">
		</form>


	USAGE:
		// Create a new instance of the class
		$my_uploader = new uploader;
		
		// OPTIONAL: set the max filesize of uploadable files in bytes
		$my_uploader->max_filesize(90000);

		// OPTIONAL: if you're uploading images, you can set the max pixel dimensions 
		$my_uploader->max_image_size(150, 300); // max_image_size($width, $height)
		
		// UPLOAD the file
		$my_uploader->upload("userfile", "", ".jpg");

		// MOVE THE FILE to its final destination
		//	$mode = 1 ::	overwrite existing file
		//	$mode = 2 ::	rename new file if a file
		//	       			with the same name already 
		//         			exists: file.txt becomes file_copy0.txt
		//	$mode = 3 ::	do nothing if a file with the
		//	       			same name already exists
		$my_uploader->save_file("/your/web/dir/fileupload_dir", int $mode);
		
		// Check if everything worked
		if ($my_uploader->error) {
			echo $my_uploader->error . "<br>";
		} else {
			// Successful upload!
			$file_name = $my_uploader->file['name'];
			print($file_name . " was successfully uploaded!");
		}
		
</readme>


*/
?>