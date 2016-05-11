<?php
	//echo password_hash($_GET["s"], PASSWORD_DEFAULT, ["cost" => 12]);
	echo password_hash(123, PASSWORD_DEFAULT, ["cost" => 12]);
?>