<?php
global $ModuleBlogModel;

$REMOTE_CONFIG = array(
    "mySQL_hostname" => "localhost",
    "mySQL_database" => "",
    "mySQL_username" => "",
    "mySQL_password" => "",
);

$Blog = new FromRemoteDataBase($REMOTE_CONFIG, "tbl_module_blog", "WHERE id = id");

if (!is_array($Blog->entries)) $Blog->entries = array($Blog->entries);

foreach($Blog->entries as $key => $entry) {
    
    $values["title"] = $entry["title"];
    $values["article"] = $entry["article"];
    
    $values["date_added"] = $entry["date_added"];
    print_r($entry);
    die();
    //$ModuleBlogModel->insert($values);
    
}
