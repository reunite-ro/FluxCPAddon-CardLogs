<?php if (!defined('FLUX_ROOT')) exit;
return array(
	'modules' => array(
		'mvpcard' => array(
			'index' => AccountLevel::ANYONE,
			'miniboss' => AccountLevel::ANYONE,
		)
	)
);
?>