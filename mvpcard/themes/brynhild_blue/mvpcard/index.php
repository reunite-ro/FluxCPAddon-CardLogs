<?php
// FluxCP Addon: MVP Card Logs - View Template
// Create this file at: addons/mvpcard/themes/default/mvpcard/index.php

if (!defined('FLUX_ROOT')) exit;
?>

<h2>MVP Card Drop Logs</h2>
<div class="log-count">
    <p><b>Displaying Top 10 Players MVP Card Drops & latest MVP card drop logs in this server.</b></p>
</div>

<div class="search-form">
    <form action="<?php echo $this->url ?>" method="get">
        <div class="search-container">
            <div class="field-row">
                <label for="char_name">Character:</label>
                <input type="text" name="char_name" id="char_name" value="<?php echo htmlspecialchars($params->get('char_name')) ?>" placeholder="Enter character name" />
            </div>

            <div class="field-row">
                <label for="mvp_id">MVP Monster:</label>
                <select name="mvp_id" id="mvp_id">
                    <option value="">All MVPs</option>
                    <?php foreach ($mvps as $mob): ?>
                        <option value="<?php echo $mob->mvp_id ?>" <?php if ($params->get('mvp_id') == $mob->mvp_id) echo 'selected="selected"' ?>>
                            <?php echo htmlspecialchars($mob->mvp_name) ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="field-row">
                <label for="card_id">Card:</label>
                <select name="card_id" id="card_id">
                    <option value="">All Cards</option>
                    <?php foreach ($cards as $card): ?>
                        <option value="<?php echo $card->card_id ?>" <?php if ($params->get('card_id') == $card->card_id) echo 'selected="selected"' ?>>
                            <?php echo htmlspecialchars($card->card_name) ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>
            
            <div class="field-row">
                <label for="map">Map:</label>
                <input type="text" name="map" id="map" value="<?php echo htmlspecialchars($params->get('map')) ?>" placeholder="Enter map name" />
            </div>
        </div>

        <div class="search-container">
            <div class="field-row">
                <label for="from_date">Date From:</label>
                <input type="date" name="from_date" id="from_date" value="<?php echo htmlspecialchars($params->get('from_date')) ?>" />
            </div>

            <div class="field-row">
                <label for="to_date">Date To:</label>
                <input type="date" name="to_date" id="to_date" value="<?php echo htmlspecialchars($params->get('to_date')) ?>" />
            </div>
            
            <div class="field-row">
                <label>&nbsp;</label>
                <div class="button-group">
                    <input type="submit" value="Search" class="primary-button" />
                    <input type="button" value="Reset" class="secondary-button" onclick="window.location.href='<?php echo $this->url ?>'" />
                </div>
            </div>
        </div>
    </form>
</div>

<h3>Top 10 Players with MVP Card Drops</h3>
<?php if (count($topPlayers) > 0): ?>
    <div class="table-container">
        <table class="horizontal-table">
            <thead>
                <tr>
                    <th>Character</th>
                    <th>Total Drops</th>
                    <th>First Drop</th>
                    <th>Last Drop</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($topPlayers as $player): ?>
                    <tr>
                        <td>
                            <?php if ($auth->actionAllowed('character', 'view') && $auth->allowedToViewCharacter): ?>
                                <?php echo $this->linkToCharacter($player->char_name) ?>
                            <?php else: ?>
                                <?php echo htmlspecialchars($player->char_name) ?>
                            <?php endif ?>
                        </td>
                        <td align="center"><?php echo number_format($player->drop_count) ?></td>
                        <td><?php echo $this->formatDateTime($player->first_drop) ?></td>
                        <td><?php echo $this->formatDateTime($player->last_drop) ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <p class="no-data">No MVP card drop data found.</p>
<?php endif ?>

<h3>MVP Card Drop Logs</h3>
<?php if ($total): ?>
    <div class="log-count">
        <p><b>Displaying the latest MVP card drop logs in this server.</b></p>
    </div>
    
    <div class="table-container">
        <table class="horizontal-table">
            <thead>
                <tr>
                    <th>Character</th>
                    <th>MVP Monster</th>
                    <th>Card</th>
                    <th>Map</th>
                    <th>Date/Time</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dropLogs as $log): ?>
                    <tr>
                        <td>
                            <?php if ($auth->actionAllowed('character', 'view') && $auth->allowedToViewCharacter): ?>
                                <?php echo $this->linkToCharacter($log->char_name) ?>
                            <?php else: ?>
                                <?php echo htmlspecialchars($log->char_name) ?>
                            <?php endif ?>
                        </td>
                        <td>
                            <?php if ($auth->actionAllowed('monster', 'view')): ?>
                                <?php echo htmlspecialchars($log->mvp_name) ?>
                            <?php else: ?>
                                <?php echo htmlspecialchars($log->mvp_name) ?>
                            <?php endif ?>
                        </td>
                        <td>
                            <?php if ($auth->actionAllowed('item', 'view')): ?>
                                <?php echo htmlspecialchars($log->card_name) ?>
                            <?php else: ?>
                                <?php echo htmlspecialchars($log->card_name) ?>
                            <?php endif ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($log->drop_map) ?>
                        </td>
                        <td><?php echo $this->formatDateTime($log->drop_date) ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
    
    <div class="pagination">
        <?php echo $paginator->getHTML() ?>
    </div>
<?php else: ?>
    <p class="no-data">No MVP card drop logs found.</p>
<?php endif ?>

<style type="text/css">
/* Enhanced styles for MVP Card Logs */
.search-form {
    margin-bottom: 30px;
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.15);
}

.search-container {
    display: flex;
    flex-wrap: wrap;
    margin-bottom: 15px;
    gap: 15px;
}

.field-row {
    flex: 1 0 200px;
    margin-bottom: 15px;
}

.field-row label {
    display: block;
    font-weight: 600;
    margin-bottom: 5px;
    color: #444;
}

.field-row input[type="text"],
.field-row input[type="date"],
.field-row select {
    width: 100%;
    padding: 8px 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    transition: border-color 0.2s;
}

.field-row input[type="text"]:focus,
.field-row input[type="date"]:focus,
.field-row select:focus {
    border-color: #6c7ae0;
    outline: none;
    box-shadow: 0 0 0 2px rgba(108, 122, 224, 0.2);
}

.button-group {
    display: flex;
    gap: 10px;
}

input[type="submit"],
input[type="button"] {
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: 600;
    transition: background 0.2s, transform 0.1s;
}

input[type="submit"] {
    background: #6c7ae0;
    color: white;
}

input[type="button"] {
    background: #f1f1f1;
    color: #444;
}

input[type="submit"]:hover,
input[type="button"]:hover {
    transform: translateY(-1px);
}

input[type="submit"]:hover {
    background: #5563c1;
}

input[type="button"]:hover {
    background: #e5e5e5;
}

.log-count {
    margin-bottom: 15px;
    color: #555;
}

h2 {
    color: #333;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #6c7ae0;
}

h3 {
    margin-top: 30px;
    margin-bottom: 15px;
    color: #444;
    border-bottom: 1px solid #ddd;
    padding-bottom: 8px;
    font-size: 1.3em;
}

.no-data {
    color: #888;
    font-style: italic;
    padding: 20px;
    text-align: center;
    background: #f9f9f9;
    border-radius: 4px;
}

/* Table Container */
.table-container {
    overflow-x: auto;
    margin-bottom: 20px;
}

/* Modern Table Styling */
.horizontal-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.horizontal-table thead tr {
    color: white;
    text-align: left;
    box-shadow: 0 4px 6px rgba(0,0,0,0.3); /* Visible shadow under header */
    position: relative; /* For the shadow to work properly */
}

.horizontal-table th {
    padding: 15px;
    border-bottom: 2px solid #3a49b1;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    font-size: 0.9em;
}

.horizontal-table td {
    padding: 12px 15px;
    border-bottom: 1px solid #ddd;
}

.horizontal-table tbody tr:nth-child(even) {
    background-color: #f0f2fa;
}

/* Add background color to table header cells */
.horizontal-table thead th:nth-child(1) {
    background-color: #3949ab; /* Character column */
}

.horizontal-table thead th:nth-child(2) {
    background-color: #1e88e5; /* Total Drops column */
}

.horizontal-table thead th:nth-child(3) {
    background-color: #00acc1; /* First Drop column */
}

.horizontal-table thead th:nth-child(4) {
    background-color: #00897b; /* Last Drop column */
}

.horizontal-table thead th:nth-child(5) {
    background-color: #43a047; /* Date/Time column */
}

.horizontal-table tbody tr:hover {
    background-color: #f1f3ff;
}

.horizontal-table tbody tr:last-child td {
    border-bottom: none;
}

/* Pagination styling */
.pagination {
    margin-top: 20px;
    text-align: center;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .search-container {
        flex-direction: column;
    }
    
    .field-row {
        flex: 1 0 100%;
    }
}
</style>