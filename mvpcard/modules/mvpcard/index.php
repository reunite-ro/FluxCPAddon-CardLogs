<?php
// FluxCP Addon: MVP Card Logs - Main Module
// Create this file at: addons/mvpcard/modules/mvpcard/index.php

if (!defined('FLUX_ROOT')) exit;

// Set page title
$title = 'MVP Card Drop Logs';

// Initialize parameters
$bind = array();
$sqlpartial = '';

// Filter parameters
$charName = $params->get('char_name');
$mvpId = $params->get('mvp_id');
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
    $sqlpartial .= "AND mvp_id = ? ";
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

// Get top 10 characters with most MVP card drops
$sql = "SELECT 
    char_name, 
    COUNT(*) as drop_count,
    MIN(drop_date) as first_drop,
    MAX(drop_date) as last_drop 
FROM 
    {$server->charMapDatabase}.dropped_mvp_card_log 
WHERE 1=1 $sqlpartial 
GROUP BY 
    char_name
ORDER BY 
    drop_count DESC
LIMIT 10";
$sth = $server->connection->getStatement($sql);
$sth->execute($bind);
$topPlayers = $sth->fetchAll();

// Stats for all MVP card drops (paginated)
// Pagination setup for card stats
$perPage = 20;
$paginator = $this->getPaginator($perPage);

// Count total MVP card drop records
$sql = "SELECT COUNT(*) AS total 
FROM {$server->charMapDatabase}.dropped_mvp_card_log 
WHERE 1=1 $sqlpartial";
$sth = $server->connection->getStatement($sql);
$sth->execute($bind);
$total = $sth->fetch()->total;

// Calculate offset for pagination
$offset = ($paginator->currentPage - 1) * $perPage;
if ($offset < 0) $offset = 0;

// Get individual MVP card drops with pagination
// Ensure we're selecting all necessary fields including mvp_id for the image display
$sql = "SELECT 
    id,
    char_name, 
    mvp_id,
    mvp_name, 
    card_id,
    card_name, 
    drop_map, 
    drop_date
FROM 
    {$server->charMapDatabase}.dropped_mvp_card_log 
WHERE 1=1 $sqlpartial 
ORDER BY id DESC 
LIMIT $offset, $perPage";
$sth = $server->connection->getStatement($sql);
$sth->execute($bind);
$dropLogs = $sth->fetchAll();

// Get MVP card drop statistics
$sql = "SELECT 
    mvp_id,
    mvp_name, 
    card_id,
    card_name,
    char_name,
    COUNT(*) as drop_count,
    DROP_date as drop_time
FROM 
    {$server->charMapDatabase}.dropped_mvp_card_log 
WHERE 1=1 $sqlpartial 
GROUP BY 
    mvp_id, mvp_name, card_id, card_name, char_name 
ORDER BY 
    drop_count DESC 
LIMIT 10";
$sth = $server->connection->getStatement($sql);
$sth->execute($bind);
$dropStats = $sth->fetchAll();

// Unique MVPs for filter
$sql = "SELECT DISTINCT mvp_id, mvp_name FROM {$server->charMapDatabase}.dropped_mvp_card_log ORDER BY mvp_name ASC";
$sth = $server->connection->getStatement($sql);
$sth->execute();
$mvps = $sth->fetchAll();

// Unique cards for filter
$sql = "SELECT DISTINCT card_id, card_name FROM {$server->charMapDatabase}.dropped_mvp_card_log ORDER BY card_name ASC";
$sth = $server->connection->getStatement($sql);
$sth->execute();
$cards = $sth->fetchAll();

// Summary statistics
$sql = "SELECT 
    mvp_id,
    mvp_name, 
    card_id,
    card_name, 
    COUNT(*) as drop_count,
    MIN(drop_date) as first_drop, 
    MAX(drop_date) as last_drop 
FROM 
    {$server->charMapDatabase}.dropped_mvp_card_log 
GROUP BY 
    mvp_id, mvp_name, card_id, card_name 
ORDER BY 
    drop_count DESC 
LIMIT 10";
$sth = $server->connection->getStatement($sql);
$sth->execute();
$dropStats = $sth->fetchAll();