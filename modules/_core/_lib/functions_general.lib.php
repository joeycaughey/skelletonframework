<?php
// v2.0
//****************************************************
// General Function Library
// Last Modified: Aug 2008
//****************************************************
$cycle_values = array();

function load_asset($name) {
	global $CONFIG;
	$file = "_assets/".$name."/init.php";
	if (file_exists($file)) {
		include($file);
		return true;
	}
	
	$dir=$CONFIG["path"]."modules/";
	foreach (get_directories($dir) as $module_dir) {
		$file = "/_assets/".$name."/init.php";
		//echo $dir.$module_dir.$file."<br />";
		if (file_exists($dir.$module_dir.$file)) {
			include($dir.$module_dir.$file);
			return true;
		}

	}
	return false;
}

function parse_content($content) {
	$output = stripslashes($content);
	//$output = htmlentities($output, ENT_QUOTES);
	return $output;
}

function file_contents($file) {
	$fp = fopen($file, "r");
	return fread($fp, filesize($file));
	fclose($fp);
}

function parse_link($link) {
	return $link;
}


function cycle($name, $values=array()) {
	global $cycle_values;

	$return = false;
	foreach($values as $value) {
		if ($return) {
			$cycle_values[$name] = $value;
			return $value;
			echo 'true';
		}
		if ($cycle_values[$name]==$value) {
			$return = true;
		}
	}
	$cycle_values[$name] = $values[0];
	return $values[0];
}

function remote_file_exists($path, $port = 80, $get = false) {
	preg_match("/^http:\/\/([a-zA-Z._-]+)\//", $path, $domain);
	$domain = $domain[1];
	preg_match("/^http:\/\/[a-zA-Z._-]+\/(.*)$/", $path, $p);
	//print_r($domain);

	
	$path = $p[1];
	$path = (substr($path, 0, 1) != '/') ? '/'.$path : $path;
	if (substr($path, strlen($path)-1, strlen($path)) == "/") return false;

	$sock = fsockopen($domain, $port, $errno, $errstr, 5); //5s timeout
	if (!$sock) return false;
	
	$cmd = ($get === true) ? "GET ".$path." HTTP/1.1\r\n" : "HEAD ".$path." HTTP/1.1\r\n";
	
	$cmd .= "Host: ".$domain."\r\n";
	
	$cmd .= "Connection: Close\r\n\r\n";
	
	fwrite($sock, $cmd);
	
	$output = NULL;
	
	while (!feof($sock))
	
	$output .= fgets($sock, 128);
	
	fclose($sock);
	
	if(preg_match('#HTTP/1.1 200 OK#', $output))return true;
	return false;

}

function include_if_exists($file) {
	if(file_exists($file)) include($file);
	else echo "$file does not exist." ;
}

function str2int($string, $concat = true) {
    $length = strlen($string);   
    for ($i = 0, $int = '', $concat_flag = true; $i < $length; $i++) {
        if (is_numeric($string[$i]) && $concat_flag) {
            $int .= $string[$i];
        } elseif(!$concat && $concat_flag && strlen($int) > 0) {
            $concat_flag = false;
        }       
    }
   
    return (int) $int;
}

function truncate($input, $amount, $trail="...") {
	$output = substr($input,0, $amount);
	
	if (strlen($input)>$amount) $output.=$trail;
	return $output;
}


function currency_krange($input_min, $input_max) {
	$min = number_format(str2int($input_min), 0, "", "");
	$max = number_format(str2int($input_max), 0, "", "");
	
	$min = substr($min, 0, strlen($min)-3);
	$max = substr($max, 0, strleValidationn($max)-3);
	
	return $min."-".$max."k";
}


function create_random_string($limit = 7) {
    $chars = "abcdefghijkmnopqrstuvwxyz023456789";
    srand((double)microtime()*1000000);
    $i = 0;
    $pass = '' ;

    while ($i <= $limit) {
        $num = rand() % 33;
        $tmp = substr($chars, $num, 1);
        $pass = $pass . $tmp;
        $i++;
    }
    return $pass;
}

function create_unique_random_md5($OBJ, $table, $field, $limit) {
	global $CONFIG;
	
	$code = MD5(create_random_string($limit));
	$result = DBQUERY($OBJ, "SELECT $field FROM $table WHERE $field = '$code'");
	while (mysql_num_rows($result)==1) {
		$result = DBQUERY($OBJ, "SELECT $field FROM $table WHERE $field = '$code'");
	}
	$dump = mysql_fetch_assoc($result);
	DBQUERY($CONFIG["site"], "UPDATE $table SET $field = '$code' WHERE id = '".$dump["id"]."'");
	return $code;
}

