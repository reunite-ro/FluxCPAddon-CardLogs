<?php
// FluxCP Addon: Mini-Boss Card Logs - Module
// Create this file at: addons/mvpcard/modules/mvpcard/miniboss.php

if (!defined('FLUX_ROOT')) exit;

// Set page title
$title = 'Mini-Boss Card Drop Logs';

// Initialize parameters
$bind = array();
$sqlpartial = '';

// Filter parameters
$charName = $params->get('char_name');
$mvpId = $params->get('mini_boss_id');
$cardId = $params->get('card_id');
$cardName = $params->get('card_name');
$map = $params->get('map');
$dateStart = $params->get('from_date');
$dateEnd = $params->get('to_date');

// Build SQL WHERE clause based on filters
if ($charName) {
    $sqlpartial .= "AND char_name LIKE '%".$server->loginDatabase->escapeString($charName)."%' ";
}

if ($mvpId) {
    $sqlpartial .= "AND mini_boss_id = ? ";
    $bind[] = $mvpId;
}

if ($cardId) {
    $sqlpartial .= "AND card_id = ? ";
    $bind[] = $cardId;
}

if ($cardName) {
    $sqlpartial .= "AND card_name LIKE '%".$server->loginDatabase->escapeString($cardName)."%' ";
}

if ($map) {
    $sqlpartial .= "AND drop_map LIKE '%".$server->loginDatabase->escapeString($map)."%' ";
}

if ($dateStart) {
    $sqlpartial .= "AND drop_date >= ? ";
    $bind[] = $dateStart;
}

if ($dateEnd) {
    $sqlpartial .= "AND drop_date <= ? ";
    $bind[] = $dateEnd . ' 23:59:59';
}

// Get top 10 characters with most Mini-Boss card drops
$sql = "SELECT 
    char_name, 
    COUNT(*) as drop_count,
    MIN(drop_date) as first_drop,
    MAX(drop_date) as last_drop 
FROM 
    {$server->charMapDatabase}.dropped_mini_boss_card_log 
WHERE 1=1 $sqlpartial 
GROUP BY 
    char_name
ORDER BY 
    drop_count DESC
LIMIT 10";
$sth = $server->connection->getStatement($sql);
$sth->execute($bind);
$topPlayers = $sth->fetchAll();

// Count total records for pagination
$sql = "SELECT COUNT(*) AS total FROM {$server->charMapDatabase}.dropped_mini_boss_card_log WHERE 1=1 $sqlpartial";
$sth = $server->connection->getStatement($sql);
$sth->execute($bind);
$total = $sth->fetch()->total;

// Pagination setup
$perPage = 20;
$paginator = $this->getPaginator($perPage);

// Calculate offset for pagination
$offset = ($paginator->currentPage - 1) * $perPage;
if ($offset < 0) $offset = 0;

// Get individual Mini-Boss card drops with pagination
// Ensure we're explicitly selecting mini_boss_id for images
$sql = "SELECT 
    id,
    char_name, 
    mini_boss_id,
    mini_boss_name, 
    card_id,
    card_name, 
    drop_map, 
    drop_date
FROM 
    {$server->charMapDatabase}.dropped_mini_boss_card_log 
WHERE 1=1 $sqlpartial 
ORDER BY id DESC 
LIMIT $offset, $perPage";
$sth = $server->connection->getStatement($sql);
$sth->execute($bind);
$dropLogs = $sth->fetchAll();

// Unique Mini-Bosses for filter
$sql = "SELECT DISTINCT mini_boss_id, mini_boss_name FROM {$server->charMapDatabase}.dropped_mini_boss_card_log ORDER BY mini_boss_name ASC";
$sth = $server->connection->getStatement($sql);
$sth->execute();
$miniBosses = $sth->fetchAll();

// Unique cards for filter
$sql = "SELECT DISTINCT card_id, card_name FROM {$server->charMapDatabase}.dropped_mini_boss_card_log ORDER BY card_name ASC";
$sth = $server->connection->getStatement($sql);
$sth->execute();
$cards = $sth->fetchAll();