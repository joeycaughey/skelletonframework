<?php
// v2.0
//****************************************************
// Upload Function Library
// Last Modified: Aug 2008
//****************************************************

function clean_image_name($name) {
	$file_name = explode(".", $name);
	array_pop($file_name);
	$file_name= strtolower(implode("_", $file_name)).".jpg";
	$file_name = str_replace(" ", "_", $file_name);
	return $file_name;
}

function clean_file_name($name) {
	$file_name = explode(".", $name);
	$ext = array_pop($file_name);
	$file_name= strtolower(implode("_", $file_name));
	$file_name = str_replace(" ", "_", $file_name);
	return $file_name.".{$ext}";
}

function upload($file_data, $location) {
	//$log = fopen($_SERVER['DOCUMENT_ROOT'].'/_config/logs/uploads.log.txt', 'a');
	
	if (isset($file_data) ) {
		//fputs($log, "Uploading to: ".$_SERVER['DOCUMENT_ROOT']." === ");
		$file = $file_data['tmp_name'];
		
		$file_name = clean_image_name($file_data["name"]);

		$error = false;
	 
		/**
		 * THESE ERROR CHECKS ARE JUST EXAMPLES HOW TO USE THE REPONSE HEADERS
		 * TO SEND THE STATUS OF THE UPLOAD, change them!
		 *
		 * If you don't change this example-file and ask me later why your
		 * uploader can't upload other files than images I'll not answer! Thank you!
		 */
	 
		if (!is_uploaded_file($file) || ($file_data['size'] > 2 * 1024 * 1024) ) // Example Validation: Need file < 2Mb
		{
			$error = '400 Bad Request';
		}
		if (!$error && !($size = @getimagesize($file))) // Example Validation: Needs an image
		{
			$error = '409 Conflict';
		}
		if (!$error && !in_array($size[2], array(1, 2, 3, 7, 8) ) ) // Example Validation: Needs a jpeg
		{
			$error = '415 Unsupported Media Type';
		}
		if (!$error && ($size[0] < 25) || ($size[1] < 25)) // Example Validation: Needs dimensions > 25px
		{
			$error = '417 Expectation Failed';
		}
	 	
		/**
		 * This simply writes a log entry
		 */
		$addr = gethostbyaddr($_SERVER['REMOTE_ADDR']);
	 
		//fputs($log, ($error ? 'FAILED' : 'SUCCESS') . ' - ' . preg_replace('/^[^.]+/', '***', $addr) . ": {$file_name} - {$file_data['size']} byte\n" );
		
	 
		if ($error)
		{
			/**
			 * ERROR DURING UPLOAD, one of the validators failed
			 *
			 * see FancyUpload.js - onError for header handling
			 */
			header('HTTP/1.0 ' . $error);
	 
			/**
			 * Abort execution and output something.
			 *
			 * FLASH NEEDS A CONTENT IN THE RESPONSE OR WILL IGNORE IT
			 */
			return false;
			//die('Error ' . $error);
		}
	 
		/**
		 * UPLOAD SUCCESSFULL AND VALID
		 *
		 * Use move_uploaded_file here to save the uploaded file in your directory like:
		 *
		 */

		move_uploaded_file($file, $location.$file_name);
		
		$file = str_replace("/private", "", $file);
		
		$f = $location.$file_name;
		
		echo $f."<br />";
		

		exec("convert $f -resize 70x100 ".$location."thumb_".$file_name);
		exec("convert $f -resize 250x250 ".$location."medium_".$file_name);

		
		return true;
		echo('Upload Successfull');
	} 
	fclose($log);
}
?>