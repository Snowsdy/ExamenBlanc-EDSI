<?php
session_start();

if (!isset($_SESSION['token'])) {
    header("Location: ../index.php");
}

require_once('../database/db.php');
$conn = connectDB();

$sql = "DELETE FROM vehicules WHERE id = ?";

$id = $_GET['id'];

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: index.php");
    } else {
        die("Erreur lors de la suppression du véhicule : " . $stmt->error);
    }

    $stmt->close();
} else {
    die("Erreur de préparation de la requête : " . $conn->error);
}
