<?php

if (!file_exists("db.sqlite")) {
    touch("db.sqlite");
}

$db = new PDO("sqlite:db.sqlite");

$db->exec("
	CREATE TABLE IF NOT EXISTS tokens (
		id INTEGER PRIMARY KEY AUTOINCREMENT,
		user_id INTEGER,
		token VARCHAR(255),
		expired_at DATETIME,
		created_at DATETIME DEFAULT CURRENT_TIMESTAMP
	);
");

echo "db initialized successfully\n";