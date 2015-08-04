<?php
$oFilter = new CAdminFilter(
	$sTableID . "_filter", 
	array(
		'Поиск',
		'ID',
		'Активность',
		'Страница'
	)
);
?>
<form name="find_form" method="get" action="<?= $APPLICATION->GetCurPage(); ?>">
<? $oFilter->Begin(); ?>
	<tr>
		<td><b>Искать по:</b></td>
		<td>
			<input type="text" size="25" name="find" value="<?= htmlspecialchars($find) ?>" title="">
			<?
			$arr = array(
				"reference" => array(
					"ID",
				),
				"reference_id" => array(
					"ID",
				)
			);
			echo SelectBoxFromArray('find_type', $arr, $find_type, '', '');
			?>
		</td>
	</tr>
	<tr>
		<td>ID:</td>
		<td>
		  <input type="text" name="find_id" size="47" value="<?= htmlspecialchars($find_id)?>">
		</td>
	</tr>
	<tr>
		<td>Активность:</td>
		<td>
		<?
		$arr = array(
			"reference" => array(
				'Любая',
				'Да',
				'Нет',
			),
			"reference_id" => array(
				'',
				"Y",
				"N",
			)
		);
		echo SelectBoxFromArray("find_active", $arr, $find_active, '', '');
		?>
		</td>
	</tr>
<?
$oFilter->Buttons(array("table_id" => $sTableID, "url" => $APPLICATION->GetCurPage(), "form" => "find_form"));
$oFilter->End();
?>
</form>