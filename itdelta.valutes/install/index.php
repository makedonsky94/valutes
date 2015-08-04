<?

Class itdelta_valutes extends CModule
{
	public $MODULE_ID = 'itdelta.valutes';
	public $MODULE_VERSION;
	public $MODULE_VERSION_DATE;
	public $MODULE_NAME;
	public $MODULE_DESCRIPTION;
	
	public function __construct()
	{
		$arModuleVersion = array();
		
        include __DIR__ . '/version.php';

        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->PARTNER_NAME = 'Интернет агенство IT-Delta';
        $this->PARTNER_URI = 'http://it-delta.ru';

        $this->MODULE_NAME = 'Валюты';
        $this->MODULE_DESCRIPTION = 'Валюты';
	}
	
	/**
	 * 
	 * 
	 * @global \CDatabase $DB
	 * @global string $DBType
	 * @global \CMain $APPLICATION
	 * @return boolean
	 */
	public function InstallDB()
	{
		global $DB, $DBType, $APPLICATION;

		$errors = $DB->RunSQLBatch(__DIR__ . "/db/{$DBType}/install.sql");
		if ($errors)
		{
			$APPLICATION->ThrowException(implode('', $errors));
			return false;
		}
		
		return true;
	}
	
	/**
	 * 
	 * 
	 * @global \CDatabase $DB
	 * @global string $DBType
	 * @global \CMain $APPLICATION
	 * @return boolean
	 */
	public function UnInstallDB($arParams = array())
	{
		global $DB, $DBType, $APPLICATION;
		
		if(!isset($_REQUEST['savedata']))
		{
			$errors = $DB->RunSQLBatch(__DIR__ . "/db/{$DBType}/uninstall.sql");
			if ($errors)
			{
				$APPLICATION->ThrowException(implode('', $errors));
				return false;
			}
		}
		
		return true;
	}
	
	function InstallFiles()
	{
		CopyDirFiles(__DIR__ . '/admin', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin');
		return true;
	}

	function UnInstallFiles()
	{
		DeleteDirFiles(__DIR__ . '/admin', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin');
		return true;
	}
	
	public function DoInstall()
	{
		$this->InstallDB();
		$this->InstallFiles();
		RegisterModule($this->MODULE_ID);
	}

	public function DoUninstall()
	{
		global $APPLICATION, $step;
		
		if($step < 2)
			$APPLICATION->IncludeAdminFile('Подтвердите удаление', __DIR__ . '/unstep1.php');
		else if($step == 2)
		{
			$this->UnInstallDB();
			$this->UnInstallFiles();
			UnRegisterModule($this->MODULE_ID);
		}
	}
}
?>