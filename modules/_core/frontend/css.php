<?php
global $CONFIG;
global $css_files;

header("Content-type: text/css; charset: UTF-8");

$write_file = $CONFIG["uploads_dir"]."compressed.css";


if (file_exists($write_file) || false) {
	die(file_get_contents($write_file));
} else {
	ob_start();
	
	$css_files = array();
	$css_files[] = "_templates/frontend/css/base.css";
	$css_files[] = "_templates/frontend/css/structure.css";
	$css_files[] = "_templates/frontend/css/layouts.css";
	$css_files[] = "_templates/frontend/css/forms.css";
	$css_files[] = "_templates/frontend/css/tables-lists.css";
	//$css_files[] = "http://www.jqueryui.com/themes/base/jquery.ui.all.css";
	
	foreach($css_files as $file) {
		if (file_exists($file)) {
			include($file);	
		}
	}
	
	$content = ob_get_clean();
	$content = str_replace("../images/", "/_templates/frontend/images/", $content);
	$content = minify($content);
	
	echo $content;
	
	$fp = fopen($write_file, "w");
	fwrite($fp, $content);
	fclose($fp);
}

function minify($css) {
	$css = preg_replace( '#\s+#', ' ', $css );
	$css = preg_replace( '#/\*.*?\*/#s', '', $css );
	$css = str_replace( '; ', ';', $css );
	$css = str_replace( ': ', ':', $css );
	$css = str_replace( ' {', '{', $css );
	$css = str_replace( '{ ', '{', $css );
	$css = str_replace( ', ', ',', $css );
	$css = str_replace( '} ', '}', $css );
	$css = str_replace( ';}', '}', $css );
	return trim( $css );
}