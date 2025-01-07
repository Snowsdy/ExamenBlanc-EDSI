<?php
session_start();

if (!isset($_SESSION['token'])) {
    header("Location: index.php");
}

require_once('database/db.php');
$conn = connectDB();

$result = $conn->query("SELECT COUNT(*) AS total_clients FROM clients");
$row = $result->fetch_assoc();
$totalClients = $row['total_clients'];

$result = $conn->query("SELECT COUNT(*) AS total_vehicules FROM vehicules");
$row = $result->fetch_assoc();
$totalVehicules = $row['total_vehicules'];

$result = $conn->query("SELECT COUNT(*) AS total_rendezvous FROM rendezvous");
$row = $result->fetch_assoc();
$totalRendezvous = $row['total_rendezvous'];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Garage Train</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/styles.min.css">
</head>

<body>
    <h1>Tableau de Bord Garage Train</h1>
    <div>
        <h2>Clients</h2>
        <p>Total Clients: <?= $totalClients ?></p>
    </div>
    <div>
        <h2>Véhicules</h2>
        <p>Total Véhicules: <?= $totalVehicules ?></p>
        <a href="/vehicules/index.php" class="btn btn-info link-underline-dark">Gestion des véhicules</a>
    </div>
    <div>
        <h2>Rendez-vous</h2>
        <p>Total Rendez-vous: <?= $totalRendezvous ?></p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>