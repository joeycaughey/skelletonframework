<?php
$ModuleBlogModel = new ModuleBlogModel($CONFIG["site"], "tbl_module_blog");

class ModuleBlogModel extends Model {
	var $width = '540';
	var $height = '250';
	var $resource = "blog-images";
	
	var $schema = array(
		"structure" => array(
			array("user_id", "int", 12),
			array("file_id", "int", 12),
			array("type", "set", "'video', 'image', 'flash'"),
			array("title", "varchar", 255),
			array("article", "longtext"),
			array("link", "varchar", 255),
			array("meta_keywords", "varchar", 255),
			array("meta_description", "varchar", 255),
			array("status", "set", "'Active','Archived','Disabled'"),
			array("date_added", "bigint"),
			array("views", "bigint")
		),
		"index" => array(
			"user_id",
			"file_id"
		)
	);
	
	function archives() {
		$archives = array();
		
		$Posts = $this->find("WHERE id = id ORDER BY date_added", true);
		
		foreach($Posts as $post) {
			
			
			$date_key = date("M Y", $post["date_added"]);
			
			$date = mktime(0, 0, 0, date("m", $post["date_added"]), 1, date("Y", $post["date_added"]));
			if (!$archives[$date_key]) $archives[$date_key] = $date;	
		}
		return $archives;
	}
	
	function most_popular() {
		return $this->find("WHERE id = id ORDER BY views", true);	
	}
	
	function viewed($id) {
		$Post = $this->find("WHERE id = '{$id}'", false, array("views"));	
		
		$v["views"] = $Post["views"]++;
		$this->update($v, "WHERE id = '{$id}'");
	}
	
	function insert($values) {
		global $UsersModel;
		if ($UsersModel->ACTIVE_USER()) {
			$values["user_id"] = $UsersModel->ACTIVE_USER["id"];	
		}	
		return parent::insert($values);
	}

	
}