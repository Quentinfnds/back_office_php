<?php
// Inclure le fichier de configuration pour la connexion à la base de données
require 'config.php';

// Ajouter des en-têtes CORS pour permettre les requêtes cross-origin
header("Access-Control-Allow-Origin: *"); // Autoriser toutes les origines
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Méthodes autorisées
header("Access-Control-Allow-Headers: Content-Type"); // En-têtes autorisés

// Vérifier si la requête est une requête OPTIONS (pré-vérification CORS)
if ($_SERVER["REQUEST_METHOD"] == "OPTIONS") {
    // Répondre à la pré-vérification sans exécuter le code principal
    exit(0);
}

// Vérifier que les données ont été envoyées
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = json_decode(file_get_contents('php://input'), true);

    $nom = $input['nomUser'];
    $prenom = $input['firstNameUser'];
    $email = $input['emailUser'];
    $motdepasse = $input['passwordUser'];
    $adresse = $input['adressUser'];
    $dob = $input['dobUser'];

    try {
        // Préparer la requête SQL avec des paramètres positionnels
        $sql = "INSERT INTO user (nomUser, firstNameUser, emailUser, adressUser, passwordUser, dobUser) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nom, $prenom, $email, $adresse, $motdepasse, $dob]);

        echo "Inscription réussie";
    } catch (PDOException $e) {
        echo "Erreur: " . $e->getMessage();
    }
}
