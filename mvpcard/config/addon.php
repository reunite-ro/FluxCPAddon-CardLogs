<?php
if (!defined('FLUX_ROOT')) exit;

// Simple addon configuration
return array(
    'MenuItems' => array(
        'Tools' => array(
            'MVPCardLogs' => array(
                'name' => 'MVP Card Logs',
                'module' => 'mvpcard',
                'action' => 'index'
            ),
            'MiniBossCardLogs' => array(
                'name' => 'Mini-Boss Card Logs',
                'module' => 'mvpcard',
                'action' => 'miniboss'
            )
        )
    )
);
?>