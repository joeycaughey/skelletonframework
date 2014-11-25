<?php


function FORMAT_filesize($bytes) {
    //CHECK TO MAKE SURE A NUMBER WAS SENT
    if(!empty($bytes)) {

    	//SET TEXT TITLES TO SHOW AT EACH LEVEL
        $s = array('bytes', 'kb', 'MB', 'GB', 'TB', 'PB');
        $e = floor(log($bytes)/log(1024));
 
        //CREATE COMPLETED OUTPUT
        $output = sprintf('%.2f '.$s[$e], ($bytes/pow(1024, floor($e))));
 
        //SEND OUTPUT TO BROWSER
        return $output;
 
    }
}

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

function FORMAT_date_ago($tm,$rcs = 0) {
    $cur_tm = time(); 
    $dif = $cur_tm-$tm;
    if ($dif<0) {
    	$dif = -$dif;
    	$future = true;
    }
    $pds = array('second','minute','hour','day','week','month','year','decade');
    $lngh = array(1,60,3600,86400,604800,2630880,31570560,315705600);
    for($v = sizeof($lngh)-1; ($v >= 0)&&(($no = $dif/$lngh[$v])<=1); $v--); if($v < 0) $v = 0; $_tm = $cur_tm-($dif%$lngh[$v]);
    
    $no = floor($no); if($no <> 1) $pds[$v] .='s'; 
    $x=sprintf("%d %s ",$no,$pds[$v]);
    if(($rcs == 1)&&($v >= 1)&&(($cur_tm-$_tm) > 0)) 
    	$x .= time_ago($_tm);
    return $x;
}

function FORMAT_forurl($input) {
	$remove = array("'", '"', "`", ",", ".", ":", ">", "<", "?", "!");

	$output = str_replace(" - ", "-", $input);
	$output = str_replace(" ", "-", $output);
	$output = str_replace("/", "-", $output);
	$output = str_replace("\"", "-", $output);
	$output = str_replace("$", "-", $output);
	$output = str_replace("--", "-", $output);
	$output = str_replace("-&-", "-and-", $output);
	$output = str_replace("(", "", $output);
	$output = str_replace(")", "", $output);
	
	
	foreach($remove as $r) {
		$output = str_replace($r, "", $output);
	}
	return strtolower($output);
}

function FORMAT_camelcase($str, $capitalise_first_char = false) {
	$output = array();
	$words = explode(" ", $str);
	foreach($words as $key => $word) {
		$length = strlen($word);
		$first = strtoupper(substr($word, 0, 1));
		$rest =  strtolower(substr($word, 1, $length));
		$output[] = $first.$rest;
	}
	
	return implode(" ", $output);

}

function FORMAT_for_filesystem($value) {
	$value = strtolower(str_replace(" ","-", $value));
	$value = strtolower(str_replace("/","", $value));
	$value = strtolower(str_replace("'","", $value));
	$value = strtolower(str_replace('"',"", $value));
	return $value;
}

function FORMAT_for_section($value) {
	$value = str_replace("'", "", $value);
	$value = str_replace('"', "", $value);
	$value = str_replace('*', "", $value);
	$value = str_replace('.', "", $value);
	$value = str_replace('-', "", $value);
	$value = str_replace('/', "", $value);
	
	return trim($value);	
}

function FORMAT_remove_quotes($value) {
	$value = str_replace("'", "", $value);
	$value = str_replace('"', "", $value);	
	return trim($value);
}

function FORMAT_date_for_save($date) {
	return mktime($date["hour"], $date["minute"], $date["second"], $date["month"], $date["day"], $date["year"]);
}

function FORMAT_time($date, $type = false) {
	
	if (!$date) return 'Unknown';

	if (!$type) return date("H:i", $date);
	
	$hour = date("g", $date);
	$min = date("i", $date);
	$sec = date("s", $date);
	
	if (strtolower($type) == "short") {
		$output = $hour;
		if ($min!=0) $output.=":".$min;
		if ($sec!=0) $output.=":".$sec;
		$output.=date("a", $date);
		return $output;
	} else if (strtolower($type) == "age") {
		
	} 
	return 'Undefined';

}


function FORMAT_date($date, $type = false) {
	
	if (!$date) return 'Unknown';
	if (!$type) return date("M, d, Y", $date);
	
	$today_day = date("d");
	$today_month = date("m");
	$today_year = date("Y");
	
	$day = date("d", $date);
	$month = date("m", $date);
	$year = date("Y", $date);
	
	if (strtolower($type) == "short") {
		if ($today_year==$year && $month>$today_month) {
			if ($today_month==$month) {
				if ($today_day==$day) {
					return '2pm';
				}	
			} 
			return date("M d", $date);
		} else {
			return date("M Y", $date);
		}

		
	} else if (strtolower($type) == "age") {
		
	} else {
		return date("M d, Y", $date);
	}
	return 'Undefined';
	
}

function FORMAT_date_range($date1, $date2) {
	if ($date1==$date2) return date("M d, Y", $date1);
	
	$date1 = is_int($date1) ? $date1 : strtotime($date1);
	$date2 = is_int($date2) ? $date2 : strtotime($date2);
	
	
	//echo strtotime("2009-11-24"); //date("M d, Y", $date1);
	
	$first_day = date("d", $date1);
	$first_month = date("M", $date1);
	$first_year = date("Y", $date1);
	
		
	$second_day = date("d", $date2);
	$second_month = date("M", $date2);
	$second_year = date("Y", $date2);
	
	if ($first_year == $second_year) {
	 	$year = $first_year;
		if ($first_month==$second_month) {
			$month = $first_month;	
			if ($first_day==$second_day) {
				return "$month $first_day $year";
			} else {
				return "<nowrap>$month $first_day <b>to</b> $second_day,  $year</nowrap>";
			}
		} 
	}
	
	return "$first_month $first_day, $first_year <b> to </b>  <br />&nbsp;&nbsp; $second_month $second_day, $second_year";
	
}

function FORMAT_shorten($input) {
	return str_replace(" ", "", strtolower($input));
}


function FORMAT_currency($number, $currency = '$') {
	return $currency.number_format($number, 2, ".", ",");	
}

function FORMAT_phone($input, $divider = ".") {
	$phone = str_replace(".", "", $input);
	$phone = str_replace("-", "", $phone);
	$phone = str_replace("(", "", $phone);
	$phone = str_replace(")", "", $phone);
	
	if (strlen($phone)==11) {
		return substr($phone,1,3).$divider.substr($phone,5,3).$divider.substr($phone,7,4);
	}
	return substr($phone,0,3).$divider.substr($phone,4,3).$divider.substr($phone,6,4);
}

function FORMAT_standardize_date($date) {
	return strtotime($date);	
}

function FORMAT_standardize_time($time) {
	$afterNoon = false;
	$seconds = 0;
	$time = str_replace(' ', '', $time);
	if ( substr($time, strlen($time)-2) == 'PM' ) {
		$afterNoon = true;
	}
	$time = substr($time, 0, -2);
	$data = explode(':', $time);
	$seconds = $data[1] * 60;
	if ( $afterNoon == true ) {	
    	$data[0] += 12;
    }
    $seconds += $data[0] * 60 * 60;
    return $seconds;
}



function FORMAT_zip_postal_code($input) {
	$code = strtoupper($input);
	
	if (preg_match("/\d\d\d\d\d/", $input)) {
			return $code;
	} else {
		if (preg_match("/[A-z]\d[A-z]\d[A-z]\d/", $code)) {
			return substr($code, 0, 3)." ".substr($code, 3, 6);
		} else {
			return $code;
		}
		//return preg_replace("/[A-z]\d[A-z]\d[A-z]\d/", "${1} ${2}", $code) ;
	}
}