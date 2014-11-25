<?PHP
global $ModuleStoreCategoriesModel;
global $ModuleStoreProductsModel;

if ($_POST) {
    if ($_POST["op"]) {
        if (is_array($_POST["ids"])) {
            foreach($_POST["ids"] as $id) {
                if ($_POST["op"]=="delete") $ModuleStoreProductsModel->delete(str2int($id));
                else if ($_POST["op"]=="disable") $ModuleStoreProductsModel->disable(str2int($id));
                else if ($_POST["op"]=="activate") $ModuleStoreProductsModel->activate(str2int($id));
            }
        }
    }
}

$StoreCategories = $ModuleStoreCategoriesModel->find("WHERE id=id", true);



?>
<h2><a href="<?= get_uri("admin_index_url") ?>">&lt;&lt; Back to CMS</a> | Store</h2>

<?php if (config("editMode") == "wizard"):?>
	<p class="message info">All products on your website are listed here.</p>
<?php endif; ?>
<form  method="POST">
<h3>
    <a href="<?= get_uri("admin_module_store_category_add_url") ?>">Add product category</a>
    Search Store
</h3>
<table cellspacing="0" class="list">
    <tr class="options">
            <td colspan="5">
                <label>Search</label>
                <input type="text" name="keywords" value="<?= $_POST["keywords"] ?>" size="30" />
                <button type="submit">Find</button>
            </td>
        </tr>
</table>
	

<?php foreach($StoreCategories as $category) :
    $Products = $ModuleStoreProductsModel->find("WHERE category_id=".$category["id"], true);
    
    if ($_POST["keywords"]!="") {
        foreach($Products as $key => $product) {
            if (preg_match("/{$_POST["keywords"]}/i", $product["name"]) || 
                preg_match("/{$_POST["keywords"]}/i", $product["description"])) {
            } else {
                unset($Products[$key]);
            }
        }
    }
?>
<h3>
	<a href="<?= get_uri("module_store_cms_category_edit_url", array("id" => $category["id"])) ?>">Edit Category</a>
	<a href="<?= get_uri("module_store_cms_products_add_url", array("category_id" => $category["id"])) ?>">Add Product to Category</a>
    <?= parse_content($category["name"]) ?>
</h3>
<table cellspacing="0" class="list">
	<thead>
		<tr>
		    <th width="1%">&nbsp;</th>
			<th>Product</th>
			<th>Price</th>
			<th>Has&nbsp;Image</th>
		</tr>
	</thead>
	<tbody>
	<?PHP if (count($Products)>0) : ?>
		<?php foreach($Products as $p): ?>
		<tr>
		    <td><input type="checkbox" name="ids[]" value="<?=$p["id"]?>" /></td>
			<td>
			     <a href="<?= get_uri("module_store_cms_products_edit_url", array("id" => $p["id"])) ?>">
		              <?=parse_content($p["name"])?>
		         </a>
		    </td>
			<td>$<?=parse_content($p["price"])?>&nbsp;USD</td>
			<td>
				<?PHP if($p["image_id"]) : ?>
				Yes
				<?php else : ?>
				No
				<?php endif; ?>
			</td>

		</tr>
		<?php endforeach; ?>
	<?php else:?>
		<tr>
			<td colspan="4">There are no products in this category.</td>
		</tr>
	<?php endif;?>
	</tbody>
</table>
<p>
    <select name="op">
        <option value="">--With Selected--</option>
        <option value="activate">Activate Selected</option>
        <option value="disable">Disable Selected</option>
        <option value="delete">Delete Selected</option>
    </select>
    <button type="submit">Go</button>
</p>
<?php endforeach; ?>

</form>
