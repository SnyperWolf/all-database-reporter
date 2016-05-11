<?php

$host = 'hostname';
$user = 'username';
$password = 'password';

$filename = 'report.csv';

echo("\nHost : ");
echo($host);
echo("\nFile : ");
echo($filename);
echo("\n");

//database
$pdo = new pdo('mysql:dbname=information_schema;host=' . $host, $user, $password);
$statement = $pdo->prepare("SELECT schema_name from information_schema.schemata where schema_name not in ('mysql','information_schema');");
$statement->execute();
$databases = $statement->fetchAll(pdo::FETCH_ASSOC);

//file
$file = fopen($filename, 'w');

foreach ($databases as $database)
{
	echo("\nSTART: ");
	echo($database[schema_name]);
	echo("\n");

	$pdo = new pdo('mysql:dbname=' . $database[schema_name] . ';host=' . $host, $user, $password);

	$statement = $pdo->prepare(<<<SQL

-- replace SQL below
select database();

SQL
	);

	$statement->execute();

	while($row = $statement->fetch(pdo::FETCH_ASSOC))
	{
		fputcsv($file, $row);
	}

	echo("END  : ");
	echo($database[schema_name]);
	echo("\n");
}

fclose($file);

echo("\nDone\n");
