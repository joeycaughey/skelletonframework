<?PHP

/** Configure Your Session Site Name **/
$sitename = "skelleton";    


error_reporting(E_ALL & ~E_NOTICE);

ini_set('memory_limit', '1024M');

set_time_limit(0);

  // Configuration
$CONFIG=array();
$CONFIG["path"] = $path;
$CONFIG["host"] = ($host) ? $host : $_SERVER['SERVER_NAME'];

// LOOK FOR HOST CONFIG FILE: /_config/hosts/
if (file_exists($_SERVER["DOCUMENT_ROOT"]."/_config/hosts/".str_replace(".","",$CONFIG["host"]).".config.php")) {
    require $CONFIG["path"]."_config/hosts/".str_replace(".","",$CONFIG["host"]).".config.php";
} else if (file_exists($CONFIG["path"]."/_config/hosts/".str_replace(".","",$CONFIG["host"]).".config.php")) {
    require $CONFIG["path"]."_config/hosts/".str_replace(".","",$CONFIG["host"]).".config.php";
} else {
    if (file_exists($_SERVER["DOCUMENT_ROOT"]."/_config/hosts/all.config.php")) {
        require $CONFIG["path"]."_config/hosts/all.config.php";
    } else if (file_exists($CONFIG["path"]."/_config/hosts/all.config.php")) {
        require $CONFIG["path"]."_config/hosts/all.config.php";
    } else {
        die('Site has not been configured. Please create :'. $CONFIG["path"]."_config/hosts/".str_replace(".","",$CONFIG["host"]).".config.php");
    }
}

session_start();

$_SESSION[$sitename]["referer"] = $_SERVER["REQUEST_URI"];

  // MySQL-Listing Libs
include $CONFIG["path"]."modules/_core/_lib/mySQL.php";
include $CONFIG["path"]."modules/_core/_lib/config.lib.php";
include $CONFIG["path"]."modules/_core/_lib/filesystem.lib.php";
include $CONFIG["path"]."modules/_core/_lib/quick_template.lib.php";  
//include $CONFIG["path"]."modules/_core/_lib/phprails.lib.php";  
include $CONFIG["path"]."modules/_core/_lib/table.lib.php";
include $CONFIG["path"]."modules/_core/_lib/model.lib.php";
include $CONFIG["path"]."modules/_core/_lib/functions_general.lib.php";
include $CONFIG["path"]."modules/_core/_lib/lorumipsum.lib.php";

// Include Form and Validation Libraries
include $CONFIG["path"]."modules/_core/_lib/forms.lib.php";
include $CONFIG["path"]."modules/_core/_lib/validation.lib.php";  
include $CONFIG["path"]."modules/_core/_lib/format.lib.php"; 
//include $CONFIG["path"]."modules/_core/_lib/xml.lib.php";

// Include and Create Routes
include $CONFIG["path"]."modules/_core/_lib/routes.lib.php";
include $CONFIG["path"]."_config/routes.php";

load_asset("php.phpmailer-lite");

// Common Global Variables
date_default_timezone_set("America/New_York");
define("TODAYSDATE", mktime(date('H'),date('i'),date('s'),date('m'), date('d'), date('Y')));
$MONTHS = array("January" => 1, "February" => 2, "March" => 3, "April" => 4, "May" => 5, "June" => 6, "July" => 7, "August" => 8, "September" => 9, "October" => 10, "November" => 11, "December" => 12);


  // Load Standard Data Models
global $disable_models;
if (!$disable_models) {
    $dir=$path."_config/models/";
    foreach (get_files($dir, array("php")) as $file) {
        include($dir.$file);
    }
}


$navigation_types = array();
$css_files = array();
$js_files = array();

  // Load Modules
global $disable_modules;

$disabled_modules = array("_skeleton");

//$disable_modules = true;
if (!$disable_modules) {
    $dir=$path."modules/";
    //echo $dir."\n\n";
    foreach (get_directories($dir) as $module_dir) {
        
        if (!in_array($module_dir, $disabled_modules)) {            
            
            if (!$disable_models) {
                $model_dir=$dir.$module_dir."/_models/";
                foreach (get_files($model_dir, array("php")) as $file) {
                    //echo $model_dir.$file."\n";
                    include($model_dir.$file);
                }
            }
                    
            // Include Routes
            $route_file = $dir.$module_dir."/routes.php";
            if (file_exists($route_file)) include($route_file);
            
            // Include Routes
            $init_file = $dir.$module_dir."/init.php";
            if (file_exists($init_file)) include($init_file);
            
            $navigation_details_file = $dir.$module_dir."/navigation/details.php";
            if (file_exists($navigation_details_file)) include($navigation_details_file);
            
            $css_file = $dir.$module_dir."/_assets/default.css";
            if (file_exists($css_file)) $css_files[] = $css_file;
            
            $js_file = $dir.$module_dir."/_assets/default.js";
            if (file_exists($js_file)) $js_files[] = $js_file;
            
        }
    }
}

if (!$_COOKIE[$sitename]["user_session"]) $_COOKIE[$sitename]["user_session"] = md5($_SERVER['REMOTE_ADDR']);

if (function_exists("geoip_db_get_all_info")) {
    $GEOIP = geoip_record_by_name($_SERVER['REMOTE_ADDR']);
} else {
    $GEOIP = array(
        "city"      => "Toronto",
        "region"    => "ON",
        "latitude"  => "43.652527",
        "longitude" => "-79.381961"
    );
}

