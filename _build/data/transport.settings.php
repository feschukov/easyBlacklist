<?php

$settings = array();

$tmp = array(
	'blockpage' => array(
		'xtype' => 'textfield',
		'value' => '1',
		'area'  => 'ebl_main',
	),
    'log_request' => array(
		'xtype' => 'combo-boolean',
		'value' => false,
		'area'  => 'ebl_main',
	),
);

foreach ($tmp as $k => $v) {
	/* @var modSystemSetting $setting */
	$setting = $modx->newObject('modSystemSetting');
	$setting->fromArray(array_merge(
		array(
			'key' => 'ebl_' . $k,
			'namespace' => PKG_NAME_LOWER,
		), $v
	), '', true, true);

	$settings[] = $setting;
}

unset($tmp);
return $settings;
