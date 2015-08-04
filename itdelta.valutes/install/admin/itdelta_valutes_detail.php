<?
$f = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/itdelta.valutes/admin/itdelta_valutes_detail.php';
$l = $_SERVER['DOCUMENT_ROOT'] . '/local/modules/itdelta.valutes/admin/itdelta_valutes_detail.php';
if (file_exists($f))
	require $f;
else if (file_exists($l))
	require $l;
else 
	echo 'Не найден файл для админки';
?>