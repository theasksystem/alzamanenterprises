<?php
session_start();
//error_reporting(0);
include('../include/db.class.php');

if(isset($_POST['query'])){
	$keyword = strval($_POST['query']);
	$search_param = "{$keyword}%";

	$sql = $conn->prepare("SELECT product_name_en FROM products WHERE status = '1' AND product_name_en LIKE '%$search_param%'");
//	$sql->bind_param("s",$search_param);			
	$sql->execute();
	$result = $sql->fetch(PDO::FETCH_ASSOC);

	if ($sql->rowCount() > 0) {
	    echo '<ul class="typeahead dropdown-menu" role="listbox" style="top: 40px; left: 0px; display: block;">';
		while($row = $sql->fetch(PDO::FETCH_ASSOC)) {
		$countryResult2 = $row["product_name_en"];
		echo '<li class="active"><a class="dropdown-item" href="products?s='.$countryResult2.'" role="option">'.$countryResult2.'</a></li>';
		}
		echo '</ul>';
	}
	else{
	    
	    echo '<ul class="typeahead dropdown-menu" role="listbox" style="top: 40px; left: 0px; display: block;">';
	    echo '<li class="active"><a class="dropdown-item" href="javascript:;" role="option">No product found for “'.$_POST["query"].'”</a></li>';
		echo '</ul>';
	    
	}
	
}
if(isset($_POST['query2'])){
	$keyword = strval($_POST['query2']);
	$search_param = "{$keyword}%";

	$sql = $conn->prepare("SELECT product_name_ar,product_name_en FROM products WHERE status = '1' AND product_name_en LIKE '%$search_param%' or product_name_ar LIKE '%$search_param%'");
//	$sql->bind_param("s",$search_param);			
	$sql->execute();
	$result = $sql->fetch(PDO::FETCH_ASSOC);

	if ($sql->rowCount() > 0) {
	    echo '<ul class="typeahead dropdown-menu" role="listbox" style="top: 40px; left: 0px; display: block;">';
		while($row = $sql->fetch(PDO::FETCH_ASSOC)) {
		$countryResult = $row["product_name_ar"];
		$countryResult2 = $row["product_name_en"];
		echo '<li class="active"><a class="dropdown-item" href="products?s='.$countryResult2.'" role="option">'.$countryResult.'</a></li>';
		}
		echo '</ul>';
	}
	else{
	    
	    echo '<ul class="typeahead dropdown-menu" role="listbox" style="top: 40px; left: 0px; display: block;">';
	    echo '<li class="active"><a class="dropdown-item" href="javascript:;" role="option">No Result Found!</a></li>';
		echo '</ul>';
	}
}