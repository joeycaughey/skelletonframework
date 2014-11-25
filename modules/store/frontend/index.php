<?php 
global $ImagesModel;
global $ModuleStoreCategoriesModel;
global $ModuleStoreProductsModel;
$StoreCategories = $ModuleStoreCategoriesModel->find("WHERE id=id", true);


?>
<style>

table.productions_holder {

}

table.productions_holder tr td {
	border-top: solid 1px #202020;
	padding-top: 15px;
	padding-bottom: 20px;
}

table.productions_holder tr td.details {
	padding-left: 20px;
	padding-top: 15px;
}

table.productions_holder tr td img {
	border: solid 2px #fff;
}


table.productions_holder tr td.details  h3 {
	font-size: 18px;
	font-weight: bold;
	margin-top: 0;
	margin-bottom: 5px;
}

table.productions_holder tr td.details p {
	margin-top: 5px;
}
</style>
<div class="navigation-layout">
	<div class="column first">
		<ul>
			<?php $first = true; foreach($StoreCategories as $category) : 
			
			$category["products"] = $ModuleStoreProductsModel->find("WHERE category_id = '{$category["id"]}' ORDER BY production_id, name", true);
			?>
			<li class="header" id="section_<?=$category["id"]?>" ><?= parse_content($category["name"]) ?></li>
			<?php foreach($category["products"] as $product) : ?>
				<li class="<?= ($first) ? 'on' : '' ?> product" id="product_<?=$product["id"]?>"><?= parse_content($product["name"]) ?></li>
				<?php if ($first) $content = "product_{$product["id"]}"; ?>
			<?php endforeach; ?>
			<?php $first = false; endforeach; ?>
		</ul>
	</div>
	<div id="product_content" class="column">
		
	</div>
	<div style="clear: both;"></div>
</div>

<p><strong>FOR INSTITUTIONAL/EDUCATIONAL PRICING, PLEASE CONTACT US  	AT (416) 598-7762 OR BY EMAIL AT <a href="mailto:comments@bigsoul.net">COMMENTS@BIGSOUL.NET</a></strong></p>
<p>WE ARE PLEASED TO OFFER ALL OF OUR PRODUCTS THROUGH 	<a href="http://www.paypal.com"><img src="/_templates/frontend/images/storeimages/paypal_logo.gif" border="2" alt="" width="100" align="absmiddle" /></a></p>


<script>
$(document).ready(function() {
	$("li.product").click(function() {
		$(this).parent().children("li").removeClass("on");
		$(this).addClass("on");
		$.get("<?= get_uri("module_store_product_section_url")?>", { id: $(this).attr("id") }, function(html) { $("#product_content").html(html); });
	});

	$.get("<?= get_uri("module_store_product_section_url")?>", { id: '<?=$content?>' }, function(html) { $("#product_content").html(html); });
});
</script>
