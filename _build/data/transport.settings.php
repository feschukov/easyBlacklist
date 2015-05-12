<?php

$settings = array();

$tmp = array(
	/*'active' => array(
		'xtype' => 'combo-boolean',
		'value' => true,
		'area' => 'ebl_main',
	),*/
);

foreach ($tmp as $k => $v) {
	/* @var modSystemSetting $setting */
	$setting = $modx->newObject('modSystemSetting');
	$setting->fromArray(array_merge(
		array(
			'key' => PKG_NAME_LOWER . '_' . $k,
			'namespace' => PKG_NAME_LOWER,
		), $v
	), '', true, true);

	$settings[] = $setting;
}

unset($tmp);
return $settings;
