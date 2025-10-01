<?php
// Script to reset the database with the correct schema
// Run this file once to fix the database structure

require_once __DIR__ . '/../config/db.php';

echo "Resetting database...\n";

$db = Database::getInstance();
if ($db->resetDatabase()) {
    echo "Database reset successfully!\n";
    echo "Default admin account created:\n";
    echo "Username: Admin\n";
    echo "Password: Est1a#System@25!\n";
} else {
    echo "Error resetting database.\n";
}
