<?php
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$isProduction = strpos($_SERVER['HTTP_HOST'] ?? '', 'hartreeworks.org') !== false;
$basePath = $isProduction ? '/forethought/2026-01/experiments' : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'January experiments' ?> — Forethought Research</title>
    <link rel="stylesheet" href="<?= $basePath ?>/assets/style.css?v=<?= time() ?>">
</head>
<body>
    <main>
