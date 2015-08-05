<?php

namespace ITDelta\Valutes;

use \Bitrix\Main\Application;
use \Bitrix\Main\Entity;

class ValutesTable extends Entity\DataManager {
	public static function getFilePath() {
		return __FILE__;
	}

	public static function getTableName() {
		return 'itdelta_valutes';
	}
	
	public static function getMap() {
		return array(
			'ID' => array(
				'data_type' => 'integer',
				'primary' => true,
				'autocomplete' => true,
				'title' => 'ID',
			),
			'ACTIVE' => array(
				'data_type' => 'boolean',
				'values' => array('N','Y'),
				'title' => 'Активность',
			),
			'NAME' => array(
				'data_type' => 'string',
				'required' => true,
				'title' => 'Валюта',
			),
			'VALUE' => array(
				'data_type' => 'double',
				'required' => true,
				'title' => 'Курс',
			)
		);
	}
	
	public static function getById($id) {
		global $DB;
		$sql = 'SELECT * FROM `' . ValutesTable::getTableName() . '` WHERE `ID`=' . htmlspecialchars($id);
		$res = $DB->Query($sql);
		return $res;
	}
	
	public static function getList($arFilter) {
		global $DB;
		
		if($arFilter) {
			$where = "WHERE ";
			foreach($arFilter as $key=>$arItem) {
				$where .= "`$key`=\"$arItem\" ";
				if($arItem != end($arFilter)) {
					$where .= 'AND ';
				}
			}
		}
		$sql = 'SELECT * FROM `' . ValutesTable::getTableName() . '` ' . $where;
		$res = $DB->Query($sql);
		return $res;
	}
	
	public static function update($id, $arFields) {
		global $DB;
		$where = "WHERE `ID`=\"$id\"";
		$set = "";
		foreach($arFields as $key=>$arItem) {
			$set .= "`$key`=\"$arItem\" ";
			if($arItem != end($arFields)) {
				$set .= ', ';
			}
		}
		print_r($sql);
		$sql = 'UPDATE `' . ValutesTable::getTableName() . '` SET ' . $set . $where;
		$res = $DB->Query($sql);
		$res->Fetch();
	}
	
	public static function add($arFields) {
		global $DB;
		$arFields['ACTIVE'] = $arFields['ACTIVE'] ? $arFields['ACTIVE'] : 'N';
		$sql = 'INSERT INTO `' . ValutesTable::getTableName() . '` ';
		$map = ValutesTable::getMap();
		$sql .= '(';
		foreach($map as $key=>$element) {
			$sql .= '`' . $key . '`';
			if($element != end($map)) {
				$sql .= ', ';
			}
		}
		$sql .= ') ';
		$sql .= 'VALUES(';
		foreach($map as $key=>$element) {
			$sql .= "'" . $arFields[$key] . "'";
			if($element != end($map)) {
				$sql .= ', ';
			}
		}
		$sql .= ') ';
		$res = $DB->Query($sql);
		$res->Fetch();
	}
}
