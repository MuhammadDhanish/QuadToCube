<?php
/**
 * CRMS Root Forwarder
 * Automatically routes root requests to public/index.php
 */

$query = $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '';
header('Location: public/index.php' . $query);
exit;
