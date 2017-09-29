<?
class SectionStringPropWithDescription extends CUserTypeInteger {
	function GetUserTypeDescription() { // инициализация пользовательского свойства для главного модуля
		return array(
			"USER_TYPE_ID" => "stringdescription",
			"CLASS_NAME" => "SectionStringPropWithDescription",
			"DESCRIPTION" => "Ссылка",
			"BASE_TYPE" => "string",
		);
	}
	function GetIBlockPropertyDescription() { // инициализация пользовательского свойства для инфоблока
		return array(
			"PROPERTY_TYPE" => "S",
			"USER_TYPE" => "stringdescription",
			"DESCRIPTION" => "Ссылка",
			'GetPropertyFieldHtml' => array('SectionStringPropWithDescription', 'GetPropertyFieldHtml'),
			'GetAdminListViewHTML' => array('SectionStringPropWithDescription', 'GetAdminListViewHTML'),
		);
	}
	function getViewHTML($name, $value) { // представление свойства
		list($link, $linktext) = explode('|', $value);
		return '<a href="'.$link.'">'.$linktext.'</a>';
	}
	function getEditHTML($name, $value, $is_ajax = false) { // редактирование свойства
		list($link, $linktext) = explode('|', $value);
		CJSCore::Init(array("jquery"));
		return <<<SSS
			<input placeholder="Ссылка" size="30" onkeyup="$(this).parent().find('#stringdescription').val($(this).val()+'|'+$(this).parent().find('.stringdescription_text').val());" type="text" class="stringdescription_link" value="{$link}">  
			<input placeholder="Текст ссылки" size="25" onkeyup="$(this).parent().find('#stringdescription').val($(this).parent().find('.stringdescription_link').val()+'|'+$(this).val());" type="text" class="stringdescription_text" value="{$linktext}">
			<input type="hidden" id="stringdescription" name="{$name}" value="{$value}">
SSS;
	}
	function GetEditFormHTML($arUserField, $arHtmlControl) { // редактирование свойства в форме (главный модуль)
		return self::getEditHTML($arHtmlControl['NAME'], $arHtmlControl['VALUE'], false);
	}
	function GetAdminListEditHTML($arUserField, $arHtmlControl) { // редактирование свойства в списке (главный модуль)
		return self::getViewHTML($arHtmlControl['NAME'], $arHtmlControl['VALUE'], true);
	}
	function GetAdminListViewHTML($arProperty, $value, $strHTMLControlName) { // представление свойства в списке (главный модуль, инфоблок)
		return self::getViewHTML($strHTMLControlName['VALUE'], $value['VALUE']);
	}
	function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName) { // редактирование свойства в форме и списке (инфоблок)
		return $strHTMLControlName['MODE'] == 'FORM_FILL'
			? self::getEditHTML($strHTMLControlName['VALUE'], $value['VALUE'], false)
			: self::getViewHTML($strHTMLControlName['VALUE'], $value['VALUE'])
		;
	}
}
AddEventHandler("iblock", "OnIBlockPropertyBuildList", array("SectionStringPropWithDescription", "GetIBlockPropertyDescription")); // добавляем тип для инфоблока
AddEventHandler("main", "OnUserTypeBuildList", array("SectionStringPropWithDescription", "GetUserTypeDescription")); // добавляем тип для главного модуля
?>
