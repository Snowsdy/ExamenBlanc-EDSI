<?php
session_start();

require_once('database/db.php');
$conn = connectDB();

function getAllVehicules(\mysqli $conn)
{
    $result = $conn->query("SELECT * FROM vehicules");
    $vehicules = $result->fetch_all();
    return $vehicules;
}

function getVehiculeById(\mysqli $conn, int $id)
{
    $stmt = $conn->prepare("SELECT * FROM vehicules WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $vehicule = $result->fetch_assoc();
    } else {
        // Aucun véhicule trouvé
        $vehicule = null;
    }

    $stmt->close();

    return $vehicule;
}

function addVehicule(\mysqli $conn, string $marque, string $modele, string $immatriculation, int $annee, int $client_id)
{
    $stmt = $conn->prepare("INSERT INTO vehicules (marque, modele, immatriculation, annee, client_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssii", $marque, $modele, $immatriculation, $annee, $client_id);

    if (!$stmt->execute()) {
        return ["error", "Erreur lors de l'ajout du véhicule: " . $stmt->error];
    }

    $stmt->close();

    return ['success', 'Le nouveau véhicule a bien été ajouté !'];
}

function updateVehicule(\mysqli $conn, int $id, string $immatriculation, string $marque, string $modele, int $annee, int $client_id)
{
    $sql = "UPDATE vehicule SET marque = ?, modele = ?, immatriculation = ?, annee = ?, client_id = ? WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssiii", $marque, $modele, $immatriculation, $annee, $client_id, $id);

        if (!$stmt->execute()) {
            return ["error", "Erreur lors de la mise à jour du véhicule: " . $stmt->error];
        }

        $stmt->close();

        return ["success", "Véhicule mis à jour avec succès."];
    } else {
        return ["error", "Erreur de préparation de la requête: " . $conn->error];
    }
}
