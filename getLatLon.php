<?php
//var_dump($_SERVER);
if ($_SERVER["REQUEST_METHOD"] == "GET"):
	require "func.php";

	
	echo json_encode(getLatLong());

endif;

?>
