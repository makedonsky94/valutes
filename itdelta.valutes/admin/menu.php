<?php
$aMenu[] = array(
	"parent_menu" => "global_menu_services",
	"section" => "valutes",
	"sort" => 300,
	'text' => 'Валюты',
	'title' => 'Валюты',
	"icon" => "currency_menu_icon",
	"page_icon" => "currency_menu_icon",
	"items_id" => "valutes",
	"module_id" => "itdelta.valutes",
	"items" => array(
		array(
			'text' => 'Управление валютами',
			'title' => 'Управление валютами',
			'url' => 'itdelta_valutes.php',
		),
	),
);

return $aMenu;
?>