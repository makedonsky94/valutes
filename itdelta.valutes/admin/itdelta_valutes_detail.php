<?php
// подключим все необходимые файлы:
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php"); // первый общий пролог

global $APPLICATION;

use \Bitrix\Main\Loader;
use \Bitrix\Main\Application;

Loader::includeModule('itdelta.valutes');

use \ITDelta\Valutes\ValutesTable as Valutes;

// подключим языковой файл
IncludeModuleLangFile(__FILE__);

// получим права доступа текущего пользователя на модуль
$POST_RIGHT = $APPLICATION->GetGroupRight("subscribe");
// если нет прав - отправим к форме авторизации с сообщением об ошибке
if ($POST_RIGHT == "D") $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

if($REQUEST_METHOD == "POST") {
	$application = Application::getInstance();
	$context = Application::getInstance()->getContext();
	$request = $context->getRequest();
	
	$save = $request->getPost('save');
	$name = $request->getPost('name');
}
$actionAdd = false;
if($request->getQuery('action') == 'add') {
	$actionAdd = true;
}
?>
<?php
$aTabs = array(
  array("DIV" => "edit", "TAB" => 'Редактирование', "ICON"=>"main_user_edit", "TITLE"=>'Редактирование')
);
$tabControl = new CAdminTabControl("tabControl", $aTabs);

$ID = (int) $_REQUEST['id'];		
$message = null;
if(
    $REQUEST_METHOD == "POST" // проверка метода вызова страницы
    &&
    ($save!="" || $apply!="") // проверка нажатия кнопок "Сохранить" и "Применить"
    &&
    $POST_RIGHT=="W"          // проверка наличия прав на запись для модуля
)
{
	$ACTIVE = $request->getPost("ACTIVE");
	$NAME = $request->getPost('NAME');
	$VALUE = $request->getPost('VALUE');
	$arFields = Array(
		'ACTIVE' => ($ACTIVE != 'on' ? 'N' : 'Y'),
		'NAME' => $NAME,   
		'VALUE'  => $VALUE,
	);
	//добавляем элемент
	if($actionAdd) {
		Valutes::add($arFields);
	}
	// сохранение данных
	else if($ID >= 0)
	{
		Valutes::update($ID, $arFields);
	}

	if ($apply != "")
		LocalRedirect("/bitrix/admin/itdelta_valutes_detail.php?ID=".$ID."&mess=ok=".LANG."&".$tabControl->ActiveTabParam());
	else
		LocalRedirect("/bitrix/admin/itdelta_valutes.php?lang=".LANG);
}



?>
<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php"); // второй общий пролог
?>
<?php
$result = \ITDelta\Valutes\ValutesTable::getById($ID);
while($res = $result->Fetch()) {
	$arResult = $res;
}
?>

	<form method="POST">
		<div class="adm-detail-content-wrap">
			<div class="adm-detail-content">
				<div class="adm-detail-title">Редактирование валюты <?=$arResult['NAME']?></div>
				<div class="adm-detail-content-item-block">
					<table class="adm-detail-content-table edit-table">
						<tr>
							<td class="adm-detail-content-cell-l">
								<p>Активность:</p>
							</td>
							<td  class="adm-detail-content-cell-r">
								<input class="adm-designed-checkbox-label" type="checkbox" name="ACTIVE" <?=$arResult['ACTIVE'] == "Y" ? "checked" : ""?>>
							</td>
						</tr>
						<? if($actionAdd): ?>
						<tr>
							<td class="adm-detail-content-cell-l">
								<p>Название:</p>
							</td>
							<td  class="adm-detail-content-cell-r">
								<input type="text" name="NAME" value="<?=$arResult['NAME']?>" >
							</td>
						</tr>
						<? endif; ?>
						<tr>
							<td class="adm-detail-content-cell-l">
								<p>Курс:</p>
								<? if(!$actionAdd): ?>
								<input type="hidden" name="NAME" value="<?=$arResult['NAME']?>" />
								<? endif; ?>
							</td>
							<td  class="adm-detail-content-cell-r">
								<input name="VALUE" type="text" value="<?=$arResult['VALUE']?>" /> <span>руб.</span>
							</td>
						</tr>
					</table>
				</div>
				<div class="adm-detail-content-btns-wrap" id="form_element_12_buttons_div" style="left: 0px;">
					<div class="adm-detail-content-btns">
						<input type="submit" class="adm-btn-save" name="save" id="save" value="Сохранить">
						<input type="submit" class="button" name="apply" id="apply" value="Применить">
						<input type="button" class="button" onclick="window.location.href='/bitrix/admin/itdelta_valutes.php'" value="Отмена" >
					</div>
				</div>
			</div>
		</div>
	</form>
	

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>
