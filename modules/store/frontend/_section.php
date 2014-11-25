<?php
global $CONFIG;
global $ImagesModel;
global $ModuleStoreProductsModel;

$id = explode("_", $_GET["id"]);
$id = $id[1];

$Product = $ModuleStoreProductsModel->find("WHERE id = '{$id}'", false);


$tax = number_format(($Product["price"]+$Product["shipping"]+$Product["handling"])*15/100,2, ".", "");
?>

<style type="text/css">

#pricing_holder {
	width: 40%; 
	float: right; 
	padding-right: 30px; 
	padding-top: 15px; 
}

#pricing_holder b {
	color: #00CDFF;
	font-size: 24px;
}


#pricing_holder p {
	margin-top: 0px;
}

</style>

<div id="pricing_holder">
	<b>$<?= parse_content($Product["price"]) ?>&nbsp;<?=$CONFIG["module"]["store"]["pricing"]?></b>
	<p>
		<?php if ($Product["shipping"]) : ?>+ $<?= parse_content(number_format($Product["shipping"]+$Product["handling"], 2, ".", ",")) ?>&nbsp;<?=$CONFIG["module"]["store"]["pricing"]?> S &amp; H<?php endif; ?> 
	</p>
	<p>
	<form method="post" action="https://www.paypal.com/cgi-bin/webscr">
		<input type="hidden" name="cmd" value="_cart">
		<input type="hidden" name="add" value="1">
		<input type="hidden" name="business" value="bigsoulproductions@gmail.com">
		<input type="hidden" name="item_name" value="<?= parse_content($Product["name"]) ?>">
		<input type="hidden" name="item_number" value="<?= parse_content($Product["id"]) ?>">
		<input type="hidden" name="amount" value="<?= parse_content($Product["price"]) ?>">
		<input type="hidden" name="shipping" value="<?= parse_content($Product["shipping"]) ?>">
		<input type="hidden" name="handling" value="<?= parse_content($Product["handling"]) ?>">
		<input type="hidden" name="tax" value="<?=$tax?>">
		
		<input type="hidden" name="currency_code" value="<?=$CONFIG["module"]["store"]["pricing"]?>">
		<?php if (false) : ?>
		<input type="hidden" name="return" value="http://www.yoursite.com/thankyou.htm">
		<?php endif; ?>
		<input type="image" src="http://images.paypal.com/en_US/i/btn/x-click-but22.gif" border="0" name="submit"  alt="Make payments with PayPal - it's fast, free and secure!">
	</form>
	</p>
</div>

<?php if($Product["image_id"]) : ?>
	<img src="<?=$ImagesModel->get_url($Product["image_id"], "medium")?>"  alt="Image <?=$Product["image_id"]?>" align="left" style="margin-right: 10px;" />
<?php else : ?>
	<div style="width:194px; height: 120px; background: #666;">&nbsp;</div>
<?php endif;?>
<div style="clear: both;"></div>
<h3><?= parse_content($Product["name"]) ?></h3>
<p style="magin-top: -30px;">
	<?= truncate(parse_content($Product["description"]), 800, "..."); ?><br /><br />
</p>

<p>&nbsp;</p>

