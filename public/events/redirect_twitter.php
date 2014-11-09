<?php
include 'common.php';
$id = $utilityObj->checkTwitterToken($_GET["token"]);
if($id > 0) {
	//setto l'admin_id e lo porto all'admin alla pagina dell'approvazione
	$_SESSION["admin_id"] = 2;
	$_SESSION["admin_name"]="comune";
	header('Location: admin/twitter_approvation.php?id='.$id);
} else {
	echo "token non valido";
}
?>