<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");

IncludeModuleLangFile(__FILE__);

global $APPLICATION;

use \Bitrix\Main\Loader;
use \Bitrix\Main\Application;

Loader::includeModule('itdelta.valutes');

use \ITDelta\Valutes\ValutesTable as Valutes;

$POST_RIGHT = $APPLICATION->GetGroupRight('itdelta.valutes');
if ($POST_RIGHT == "D") $APPLICATION->AuthForm(GetMessage('ACCESS_DENIED'));

$sTableID	 = 'tbl_valutes';
$oSort		 = new CAdminSorting($sTableID, 'ID', 'DESC');
$lAdmin		 = new CAdminList($sTableID, $oSort);

// ******************************************************************** //
//                ОБРАБОТКА ДЕЙСТВИЙ НАД ЭЛЕМЕНТАМИ СПИСКА              //
// ******************************************************************** //
//////////////// ///////////////////////
if ($lAdmin->EditAction() && $POST_RIGHT == "W")
{	
	foreach($FIELDS as $ID => $arFields)
	{
		if(!$lAdmin->IsUpdated($ID)) continue;
		$DB->StartTransaction();
			//$arData = array('DATE_MODIFY' => Bitrix\Main\Type\Date::createFromTimestamp(time()));
			foreach($arFields as $key => $value)
			{
				if (!empty($value)) $arData[$key] = $value;
			}
			$res = Valutes::update($ID, $arData);
			if(!$res->isSuccess())
			{
				$DB->Rollback();
				$lAdmin->AddGroupError(implode('<br>', $res->getErrorMessages()), $ID);
			}
		$DB->Commit();
	}
}

if (($arID = $lAdmin->GroupAction()) && $POST_RIGHT == "W")
{
	if($_REQUEST['action_target'] == 'selected')
	{
		$arId = array();
		$ids = Valutes::getList()->fetchAll();
		foreach ($ids as $id) $arID[] = $id['ID'];
	}
	
	foreach($arID as $ID)
	{
		switch($_REQUEST['action'])
		{
			case 'delete':
				$DB->StartTransaction();
				$res = Valutes::Delete($ID);
				if(!$res->isSuccess())
				{
					$DB->Rollback();
					$lAdmin->AddGroupError(implode('<br>', $res->getErrorMessages()), $ID);
				}
				$DB->Commit();
			break;
		
			case 'activate':
			case 'deactivate':
				//$arFields['DATE_MODIFY'] = Bitrix\Main\Type\Date::createFromTimestamp(time());
				$arFields['ACTIVE'] = $_REQUEST['action'] == 'activate' ? 'Y' : 'N';
				$res = Valutes::update($ID, $arFields);
				if(!$res->isSuccess())
				{
					$DB->Rollback();
					$lAdmin->AddGroupError(implode('<br>', $res->getErrorMessages()), $ID);
				}
				$DB->Commit();
			break;
		}	
	}	
}

// ******************************************************************** //
//                ВЫБОРКА ЭЛЕМЕНТОВ СПИСКА                              //
// ******************************************************************** //
$FilterArr = array(
	'find_type',
	'find_active',
);
$lAdmin->InitFilter($FilterArr);

$arFilter = array();

if (!empty($find_type) && $find_type = 'ID' && !empty($find))
	$arFilter['ID'] =  $find;

if (!empty($find_id))
	$arFilter['ID'] =  $find_id;

if (!empty($find_active))
	$arFilter['ACTIVE'] = $find_active;


$arOrder = !empty($by) && !empty($order) ? array($by => $order) : array('ID' => 'ASC');

$req = Application::getInstance()->getContext()->getRequest();
$items = Valutes::getList();

$rsData = new CAdminResult($items, $sTableID);
$rsData->NavStart();
$lAdmin->NavText($rsData->GetNavPrint('Страницы'));


// ******************************************************************** //
//                ���������� ������ � ������                            //
// ******************************************************************** //
$lAdmin->AddHeaders(array(
	array(
		'id' => 'ID',
		'content' => 'ID',
		'sort' => 'ID',
		'align' => 'right',
		'default' => true,
	),
	array(
		'id' => 'ACTIVE',
		'content' => 'Активность',
		'sort' => 'ACTIVE',
		'default' => true,
	),
	array(
		'id' => 'NAME',
		'content' => 'Валюта',
		'sort' => 'NAME',
		'default' => true,
	),
	array(
		'id' => 'VALUE',
		'content' => 'Курс',
		'sort' => 'VALUE',
		'default' => true,
	),
));

while ($arRes = $rsData->NavNext()):

	$row = $lAdmin->AddRow($arRes['ID'], $arRes);
	
	$row->AddCheckField('ACTIVE');
	$row->AddInputField('NAME'); 
	$row->AddInputField('VALUE');
	
	
	$arActions = array();
	
	$arActions[] = array(
		"ICON" => "edit",
		"DEFAULT" => true,
		"TEXT" => 'Редактировать',
		"ACTION" => $lAdmin->ActionRedirect('itdelta_valutes_detail.php?id=' . $arRes['ID'])
	);
	
	$arActions[] = array(
		"ICON" => "active",
		"DEFAULT" => true,
		"TEXT" => 'Активировать',
		"ACTION" => $lAdmin->ActionDoGroup($arRes['ID'], "activate")
	);
	
	$arActions[] = array(
		"ICON" => "active",
		"DEFAULT" => true,
		"TEXT" => 'Деактививровать',
		"ACTION" => $lAdmin->ActionDoGroup($arRes['ID'], "deactivate")
	);
	
	if ($POST_RIGHT >= "W")
		$arActions[] = array(
			"ICON" => "delete",
			"TEXT" => 'Удалить',
			"ACTION" => "if(confirm('Вы действительно хотите удалить запись?')) " . $lAdmin->ActionDoGroup($arRes['ID'], "delete")
		);
	
	$arActions[] = array("SEPARATOR"=>true);
	
	$row->AddActions($arActions);
	
endwhile;

$lAdmin->AddGroupActionTable(Array(
	"delete"=> 'Удалить выбранные элементы', 
	"activate"=> 'Активировать выбранные элементы', 
	"deactivate"=> 'Деактивировать выбранные элементы',
));

/*$aContext = array(
	array(
		"TEXT" => 'Добавить элемент',
		"LINK" => "itdelta_valutes_detail.php?id=0",
		"TITLE" => Добавить элемент',
		"ICON" => "btn_new",
	),
);*/

$lAdmin->AddAdminContextMenu();

$lAdmin->CheckListMode();

$APPLICATION->setTitle('Список валют');

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php';

include __DIR__ . '/filter.php';

$lAdmin->DisplayList();

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php';
?>