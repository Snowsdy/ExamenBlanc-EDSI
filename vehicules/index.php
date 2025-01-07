<?php
session_start();

if (!isset($_SESSION['token'])) {
    header("Location: ../index.php");
}

$add_result = [];

require_once('../database/db.php');
$conn = connectDB();

function getAllVehicules(\mysqli $conn)
{
    $result = $conn->query("SELECT * FROM vehicules");
    $vehicules = $result->fetch_all(1);
    return $vehicules;
}

function getClientById(\mysqli $conn, int $id)
{
    $stmt = $conn->prepare("SELECT * FROM clients WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $client = $result->fetch_assoc();
    } else {
        // Aucun client trouvé
        $client = null;
    }

    $stmt->close();

    return $client;
}

function addVehicule(\mysqli $conn, string $marque, string $modele, string $immatriculation, int $annee, ?int $client_id)
{
    $stmt = $conn->prepare("INSERT INTO vehicules (marque, modele, immatriculation, annee, client_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssii", $marque, $modele, $immatriculation, $annee, $client_id);

    if (!$stmt->execute()) {
        return ["error", "Erreur lors de l'ajout du véhicule: " . $stmt->error];
    }

    $stmt->close();

    return ['success', 'Le nouveau véhicule a bien été ajouté !'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $marque = $_POST['marque'];
    $modele = $_POST['modele'];
    $immatriculation = $_POST['immatriculation'];
    $annee = $_POST['annee'];
    $client_id = $_POST['client_id'] == '' ? null : $_POST['client_id'];

    $add_result = addVehicule($conn, $marque, $modele, $immatriculation, $annee, $client_id);
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
        <h2>Liste des véhicules</h2>
        <div class="row row-cols-3 justify-content-md-center gap-2">
            <?php foreach (getAllVehicules($conn) as $key => $value): ?>
                <div class="card col" style="width: 18rem;">
                    <div class="card-body">
                        <h3 class="card-title"><?= $value['marque'] ?></h3>
                        <h5 class="card-subtitle mb-2 text-body-secondary"><?= $value['annee'] ?></h5>

                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><?= $value['modele'] ?></li>
                            <li class="list-group-item"><?= $value['immatriculation'] ?></li>
                            <?php
                            $client = getClientById($conn, (int) $value['client_id']);
                            if (!is_null($client)): ?>
                                <li class="list-group-item">Appartient à <?= $client['nom'] ?>.</li>
                            <?php endif; ?>
                        </ul>
                        <a href="delete.php?id=<?= $value['id'] ?>" class="btn btn-danger mb-2">Supprimer</a>
                        <a href="update.php?id=<?= $value['id'] ?>" class="btn btn-warning mb-2">Mettre à jour</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="container mb-4">
        <h2>Ajout d'un véhicule</h2>
        <?php
        if (isset($add_result['error'])) {
        ?><div class="alert alert-danger" role="alert">
                <?= $add_result['error'] ?>
            </div>
        <?php
        }
        ?>
        <form method="POST">
            <div class="mb-3">
                <label for="marque" class="form-label">Marque</label>
                <input type="text" class="form-control" id="marque" name="marque" required>
            </div>

            <div class="mb-3">
                <label for="modele" class="form-label">Modèle</label>
                <input type="text" class="form-control" id="modele" name="modele" required>
            </div>

            <div class="mb-3">
                <label for="immatriculation" class="form-label">Immatriculation</label>
                <input type="text" class="form-control" id="immatriculation" name="immatriculation" required>
            </div>

            <div class="mb-3">
                <label for="annee" class="form-label">Année</label>
                <input type="number" class="form-control" id="annee" name="annee" required>
            </div>

            <div class="mb-3">
                <label for="client_id" class="form-label">ID Client</label>
                <input type="number" class="form-control" id="client_id" name="client_id">
            </div>

            <button type="submit" class="btn btn-primary">Ajouter le véhicule</button>
        </form>

    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>