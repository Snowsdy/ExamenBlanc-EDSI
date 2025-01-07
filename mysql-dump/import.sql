-- Créer la base de données 'mydatabase' si elle n'existe pas
CREATE DATABASE IF NOT EXISTS mydatabase;

-- Sélectionner la base de données à utiliser
USE mydatabase;

START TRANSACTION;

-- Création de la table 'clients'
CREATE TABLE IF NOT EXISTS clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    email VARCHAR(50),
    telephone VARCHAR(15)
);

-- Création de la table 'vehicules'
CREATE TABLE IF NOT EXISTS vehicules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    marque VARCHAR(50) NOT NULL,
    modele VARCHAR(50) NOT NULL,
    immatriculation VARCHAR(50) NOT NULL UNIQUE,
    annee INT,
    client_id INT,
    FOREIGN KEY (client_id) REFERENCES clients(id)
);

-- Création de la table 'rendezvous'
CREATE TABLE IF NOT EXISTS rendezvous (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date_heure DATETIME NOT NULL,
    vehicule_id INT,
    description TEXT,
    FOREIGN KEY (vehicule_id) REFERENCES vehicules(id)
);

-- Création de la table 'administrateurs'
CREATE TABLE IF NOT EXISTS administrateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password_hash VARCHAR(255) NOT NULL
);

-- Création de la table 'tokens'
CREATE TABLE IF NOT EXISTS tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    token VARCHAR(255) NOT NULL,
    expiration_date DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES administrateurs(id)
);

-- Insertion des administrateurs
INSERT INTO administrateurs (username, password_hash) VALUES
('admin1', '$2y$10$TOBO0ipevsQEWJ7oME7iEegjPT7s3HL9K5PJB.qIiXwj1ED2ZhvTi'),
('admin2', '$2y$10$TOBO0ipevsQEWJ7oME7iEegjPT7s3HL9K5PJB.qIiXwj1ED2ZhvTi');

-- Insertion des clients
INSERT INTO clients (nom, email, telephone) VALUES
('John Doe', 'john.doe@email.com', '123456789'),
('Jane Smith', 'jane.smith@email.com', '987654321'),
('Bob Johnson', 'bob.johnson@email.com', '555555555');

-- Insertion des véhicules
INSERT INTO vehicules (marque, modele, immatriculation, annee, client_id) VALUES
('Toyota', 'Camry', 'ED-480-XQ', 2015, 1),
('Honda', 'Civic', 'TP-012-VK', 2020, 2),
('Ford', 'Focus', 'FO-001-GG', 2018, 3);

-- Insertion des rendez-vous
INSERT INTO rendezvous (date_heure, vehicule_id, description) VALUES
('2023-12-01 10:00:00', 1, "Entretien régulier"),
('2023-12-15 14:30:00', 2, "Changement d'huile"),
('2023-12-20 09:15:00', 3, "Diagnostic moteur");

-- Insertion des tokens
INSERT INTO tokens (user_id, token, expiration_date) VALUES
(1, 'abcdef12faketoken3456', '2023-12-31 23:59:59');

COMMIT;
