<?php
session_start();

if (!isset($_SESSION['token'])) {
    header("Location: ../index.php");
}

$id = $_GET['id'];
$vehicule = null;
$update_result = [];

require_once('../database/db.php');
$conn = connectDB();

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

function updateVehicule(\mysqli $conn, int $id, string $immatriculation, string $marque, string $modele, int $annee, ?int $client_id)
{
    $sql = "UPDATE vehicules SET marque = ?, modele = ?, immatriculation = ?, annee = ?, client_id = ? WHERE id = ?";

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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $marque = $_POST['marque'];
    $modele = $_POST['modele'];
    $immatriculation = $_POST['immatriculation'];
    $annee = $_POST['annee'];
    $client_id = $_POST['client_id'] == '' ? null : $_POST['client_id'];

    $update_result = updateVehicule($conn, $id, $marque, $modele, $immatriculation, $annee, $client_id);

    if (!isset($update_result['error'])) {
        header("Location: index.php");
    }
} else {
    $vehicule = getVehiculeById($conn, $id);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - Véhicules</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/styles.min.css">
</head>

<body>
    <section class="container my-4">
        <h2>Mise à jour d'un véhicule</h2>
        <?php
        if (isset($update_result['error'])) {
        ?><div class="alert alert-danger" role="alert">
                <?= $update_result['error'] ?>
            </div>
        <?php
        }
        ?>
        <form method="POST">
            <div class="mb-3">
                <label for="marque" class="form-label">Marque</label>
                <input type="text" class="form-control" id="marque" name="marque" value="<?= $vehicule['marque'] ?>" required>
            </div>

            <div class="mb-3">
                <label for="modele" class="form-label">Modèle</label>
                <input type="text" class="form-control" id="modele" name="modele" value="<?= $vehicule['modele'] ?>" required>
            </div>

            <div class="mb-3">
                <label for="immatriculation" class="form-label">Immatriculation</label>
                <input type="text" class="form-control" id="immatriculation" name="immatriculation" value="<?= $vehicule['immatriculation'] ?>" required>
            </div>

            <div class="mb-3">
                <label for="annee" class="form-label">Année</label>
                <input type="number" class="form-control" id="annee" name="annee" value="<?= $vehicule['annee'] ?>" required>
            </div>

            <div class="mb-3">
                <label for="client_id" class="form-label">ID Client</label>
                <input type="number" class="form-control" id="client_id" name="client_id" value="<?= $vehicule['client_id'] ?>">
            </div>

            <button type="submit" class="btn btn-primary">Modifier le véhicule</button>
        </form>

    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>