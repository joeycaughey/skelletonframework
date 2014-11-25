<?php
global $ModuleBlogModel;

$REMOTE_CONFIG = array(
    "mySQL_hostname" => "localhost",
    "mySQL_database" => "bigsoul_database",
    "mySQL_username" => "bigsoul_user",
    "mySQL_password" => "james12",
);

$News = new FromRemoteDataBase($REMOTE_CONFIG, "tbl_main_news", "WHERE id = id");

if (!is_array($News->entries)) $News->entries = array($News->entries);

foreach($News->entries as $key => $entry) {
    
    $values["title"] = $entry["title"];
    $values["article"] = $entry["text"];
    
    $values["date_added"] = $entry["date_added"];
    print_r($entry);
    die();
    //$ModuleBlogModel->insert($values);
    //unset($News->entries[$key]);
    
    
}
