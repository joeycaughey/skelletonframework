<?PHP
global $CONFIG;
global $ModuleBlogModel;
global $FilesModel;

$form = new FormHelper("");
$form->legend("Blog IPost Information");
$form->input("title", array("label" => "title", "required" => VALID_notnull));
$form->input("link", array("label" => "Link"));
$form->file("image", array("label" => "Image"));
$form->textarea("article", array("label" => "Article", "required" => VALID_notnull));
$form->textarea("meta_keywords", array("label" => "Meta Keywords"));
$form->textarea("meta_description", array("label" => "Meta Description", "required" => VALID_notnull));

$form->buttons(
    array(
        array(
            "save-button", 
            array(
                "label" => "Save",
                "class" => "save add"
            )
        ),
        array(
            "add-option", 
            array(
                "type" => "option",
                "label" => "Add another post",
                "value" => ""
            )
        )
    )
);

if ($_POST) {
    $form->data = $_POST;
    if ($form->validates()) {
        if ($_GET["id"]) {
            $ModuleBlogModel->update($_POST, str2int($_GET["id"]));
        } else {
            $_GET["id"] = $ModuleBlogModel->insert($_POST);
        }
        
        $ModuleBlogModel->upload_file($_FILES["image"], 
            array(
                "description" => $_POST["name"],
                "key" => "file_id",
                "value" => $form->data["file_id"]
            )
        );
        
        if ($_POST["add-option"]) {
            unset($_POST);
        } else {
            header("Location: ".get_uri("module_blog_cms_url"));
        }
    }
} 

if ($_GET["id"]) {
    $form->data = $ModuleBlogModel->find(str2int($_GET["id"]));
} 
?>

<h2><a href="<?= get_uri("module_blog_cms_url") ?>">&lt;&lt; Back to Blog Management</a> | Blog</h2>

    
<h3><?= ($_GET["id"]) ? 'Edit' : 'Add' ?> Blog Post</h3>
 
<?= $form->render() ?>
