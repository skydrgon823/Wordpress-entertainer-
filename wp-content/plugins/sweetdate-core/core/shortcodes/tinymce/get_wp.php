<?php

// Access WordPress
$wp_load = "wp-load.php";
$count = 0;
while(!file_exists($wp_load)) {
	$count++;
	if ($count > 12) { 
		break;
	}
	$wp_load = '../' . $wp_load;
}

if (file_exists($wp_load)) {
	require_once(realpath($wp_load));
}

?>