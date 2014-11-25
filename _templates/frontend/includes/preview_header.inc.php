<?PHP if ($_GET["preview"]=="true") : ?>
<style>

#preview {
	background: #000;
	padding: 10px;
}

#preview button {
	border: solid 2px #ccc;
	color: #ccc;
	font-weight: bold;
	background: #000;
}

</style>

<div id="preview" align="center">
	<button onclick="history.back();" class=""><< Back</button>
	<button href="<?= get_uri("admin_content_edit_url", array("id" => $_GET["id"])) ?>" class="">Edit this page</button>
	<button href="<?= get_uri("admin_content_publish_url", array("id" => $_GET["id"])) ?>" class="">Publish to live site</button>
</div>
<?PHP endif; ?>