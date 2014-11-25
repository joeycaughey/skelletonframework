<?php
load_asset("jquery.popup")
?>
<button onclick="popup_url('<?= get_uri("compose_url", array("user_type" => $_GET["user_type"])) ?>', false)">Compose</button>

<?php if ($_GET["type"]=="sent") : ?>
	<button href="<?= get_uri("messaging_url", array("user_type" => $_GET["user_type"])) ?>" class="<?= is_uri(get_uri("messaging_url", array("user_type" => $_GET["user_type"])), "on") ?>">Sent Mail</button>
<?php else : ?>
	<button href="<?= get_uri("messaging_url", array("user_type" => $_GET["user_type"])) ?>" class="<?= is_uri(get_uri("messaging_url", array("user_type" => $_GET["user_type"])), "on") ?>">View Inbox</button>
<?php endif; ?>

<br /><br />
