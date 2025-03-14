<?php
if (!defined('FLUX_ROOT')) exit;

// Simple addon configuration
return array(
    'MenuItems' => array(
        'Other' => array(
            'MVPCards' => array(
                'module' => 'mvpcard'
            )
        )
    ),
    	'SubMenuItems'	=> array(
		'mvpcard'	=> array(
			'index' => 'MVP Cards',
			'miniboss' => 'Mini Boss Cards',
		)
	),
);
?>