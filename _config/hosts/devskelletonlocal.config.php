<?PHP
// CONFIG FILE

$CONFIG["sitename"] = "skelleton";
$CONFIG["template"] = "frontend";

// Database Access Information
$CONFIG["site"]["mySQL_hostname"] = "127.0.0.1";
$CONFIG["site"]["mySQL_database"] = "_skelleton_release_database";
$CONFIG["site"]["mySQL_username"] = "root";
$CONFIG["site"]["mySQL_password"] = "";
$CONFIG["site"]["mySQL_client"] = "mysql";
$CONFIG["site"]["mySQL_socket"] = "";
$CONFIG["site"]["mySQL_dump"] = "mysqldump";

$CONFIG["common"]["mySQL_hostname"] = "127.0.0.1";
$CONFIG["common"]["mySQL_database"] = "_common";
$CONFIG["common"]["mySQL_username"] = "root";
$CONFIG["common"]["mySQL_password"] = "";


// Email Settings
$CONFIG["email"] = array(
    "general" => "joey.caughey@gmail.com", 
    "support" => "joey.caughey@gmail.com",
    "errors" => "joey.caughey@gmail.com",

    "smtp" => array(
        "host" => "www.sitemafia.com",
        "from" => "skelleton@sitemafia.com",
        "user" => "skelleton",
        "pass" => "framework"
    )
);

$CONFIG["debug"] = true;
$CONFIG["local"] = true;

// File Upload Directory
$CONFIG["uploads_dir"] = $_SERVER["DOCUMENT_ROOT"]."/files/";




