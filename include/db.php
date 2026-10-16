<?php
// One shared database connection for all pages (SQLite, no server needed)
$dbFile = __DIR__ . '/../database.sqlite';
$isNewDatabase = !file_exists($dbFile);

$pdo = new PDO('sqlite:' . $dbFile);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

// First start: create the tables from schema.sql
if ($isNewDatabase) {
    $pdo->exec(file_get_contents(__DIR__ . '/../schema.sql'));
}

// Escape text before printing it into HTML (protects against XSS)
function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
