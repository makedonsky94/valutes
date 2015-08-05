# valutes
Модуль валюты для всех редакций "Битрикс"
# Как использовать
Нужно закинуть в папку /bitrix/modules/ либо /local/modules/
Затем нужно модуль установить из пункта matketplace
```php
if(CModule::IncludeModule('itdelta.valutes')) {
	echo ITDelta\Valutes\ValutesTable::getTableName();
	ITDelta\Valutes\ValutesTable::add(array('NAME' => 'TEST', 'VALUE' => '123.33'));
}
```
