<?php
global $CONFIG;
global $ProductionsModel;
global $ModuleStoreProductsModel;
global $ModuleStoreCategoriesModel;

$form = new FormHelper("");
$form->legend("Product Information");
$form->input("name", array("label" => "Name", "required" => VALID_notnull));
$form->select("category_id", array("label" => "Category", "options" => $ModuleStoreCategoriesModel->options("id", "name")));
$form->select("production_id", array("label" => "Production", "options" => $ProductionsModel->options("id", "title")));
$form->input("price", array("label" => "Price"));
$form->currency("shipping", array("label" => "Shipping"));
$form->currency("handling", array("label" => "Handling"));
$form->file("image", array("label" => "Image"));
$form->textarea("description", array("label" => "Description", "required" => VALID_notnull));


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
                "label" => "Add another",
                "value" => ""
            )
        )
    )
);

if ($_POST) {
    $form->data = $_POST;
    if ($form->validates()) {
    
        if ($_GET["id"]) {      
            $ModuleStoreProductsModel->update($_POST, str2int($_GET["id"]));
        } else {
            $_GET["id"] = $ModuleStoreProductsModel->insert($_POST);  
        }
        
        $ModuleStoreProductsModel->upload_file($_FILES["image"], 
            array(
                "description" => $_POST["name"],
                "key" => "image_id",
                "value" => $form->data["image_id"]
            )
        );
        
        if ($_POST["add-option"]) {
            unset($_POST);
        } else {
            header("Location: ".get_uri("module_store_cms_url"));
        }
    }
}

if ($_GET["id"]) {
    $form->data = $ModuleStoreProductsModel->find(str2int($_GET["id"]));
} 


$sizes = array(
    "thumb" => array("width" => 82, "height" => 60, "crop" => false),
    "medium" => array("width" => 194, "height" => 120, "crop" => false)
);

?>
<h2><a href="<?= get_uri("module_store_cms_url") ?>">&lt;&lt; Back to Store</a> | <?= ($_GET["id"]) ? "Edit" : "Add" ?> Product</h2>
<?php if (config("editMode") == "wizard"):?>
    <p class="message info">
        Please fill in the information regarding your product.
    </p>
<?php endif; ?>
<?= $form->render() ?>




