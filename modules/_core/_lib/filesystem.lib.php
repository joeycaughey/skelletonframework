<?php

// Get Directories
function get_directories($dir) {
	$directories = array();
	if (is_dir($dir)) {
	   if ($dh = opendir($dir)) {
		   while (($item = readdir($dh)) == true) {
				if ($item!=".." and $item!=".") {
					$directories[] = $item;
				} 
		   }
		   closedir($dh);
	   }
	}	
	return $directories;
}

// Get Files
function get_files($dir, $filetypes = array()) {
	$files = array();
	if (is_dir($dir)) {
	   if ($dh = opendir($dir)) {
		   while (($item = readdir($dh)) !== false) {
				if ($item!=".." and $item!=".") {
					
					if (count($filetypes)>0) {
						$ext=explode(".",$item);	
						$ext = $ext[count($ext)-1];
						if (in_array($ext, $filetypes)) $files[] = $item;
					} else {
						$files[] = $item;
					}
				} 
		   }
		   closedir($dh);
	   }
	}	
	return $files;
}

function rm_recurse($file) {
    if (is_dir($file) && !is_link($file)) {
        foreach(glob($file.'/*') as $sf) {
            if ( !rm_recurse($sf) ) {
                error_log("Failed to remove $sf\n");
                return false;
            }
        }
        return rmdir($file);
    } else {
        return unlink($file);
    }
}