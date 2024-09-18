<?php
$host = 'localhost';
$db   = 'cms_db';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

function getSkillsForHeader($pdo) {
    $stmt = $pdo->prepare("SELECT id, title FROM skills ORDER BY id ASC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getSkillDetails($pdo, $skillId) {
    $stmt = $pdo->prepare("SELECT id, title, icon, image, description FROM skills WHERE id = ?");
    $stmt->execute([$skillId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
