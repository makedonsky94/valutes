# valutes
Модуль валюты для всех редакций "Битрикс"
# Как использовать
Нужно закинуть в папку /bitrix/modules/ либо /local/modules/
Затем нужно модуль установить из пункта matketplace.
Для полключения модуля нужно вызвать функцию
```php
CModule::IncludeModule('itdelta.valutes')
```

```php
if(CModule::IncludeModule('itdelta.valutes')) {
	echo ITDelta\Valutes\ValutesTable::getTableName();
	ITDelta\Valutes\ValutesTable::add(array('NAME' => 'TEST', 'VALUE' => '123.33'));
}
```
# Функции
##getTableName - Получаем название таблицы
```php
if(CModule::IncludeModule('itdelta.valutes')) {
	echo ITDelta\Valutes\ValutesTable::getTableName();
}
```

##getById - Получить запись по Id
```php
if(CModule::IncludeModule('itdelta.valutes')) {
	print_r(ITDelta\Valutes\ValutesTable::getById(1)->Fetch());
}
```

##getList - Получить список записей
```php
if(CModule::IncludeModule('itdelta.valutes')) {
	
	print_r(ITDelta\Valutes\ValutesTable::getList()->Fetch());
	print_r(ITDelta\Valutes\ValutesTable::getList(array("ID" => 1))->Fetch());
}
```

##add - Добавление в таблицу
```php
if(CModule::IncludeModule('itdelta.valutes')) {
	ITDelta\Valutes\ValutesTable::add(array('NAME' => 'TEST', 'VALUE' => '123.33'));
}
```

##update - Обновить запись по ID
```php
if(CModule::IncludeModule('itdelta.valutes')) {
	$ID = 1;
	$arFields = array(
		"NAME" => "123"
	);
	ITDelta\Valutes\ValutesTable::update($ID, $arFields);
}
```

##delete - Удалить запись по ID
```php
if(CModule::IncludeModule('itdelta.valutes')) {
	ITDelta\Valutes\ValutesTable::delete(1);
}
```


