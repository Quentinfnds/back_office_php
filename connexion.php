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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer le corps de la requête en JSON
    $input = json_decode(file_get_contents('php://input'), true);

    // Récupérer les données envoyées depuis le front-end
    $email = $input['emailUser'];
    $motdepasse = $input['passwordUser'];

    try {
        // Préparer la requête SQL pour récupérer l'utilisateur avec l'email donné
        $sql = "SELECT * FROM user WHERE emailUser = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);

        // Vérifier si un utilisateur a été trouvé
        if ($stmt->rowCount() > 0) {
            // Récupérer les données de l'utilisateur
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Vérifier si le mot de passe est correct
            if ($motdepasse ===$user['passwordUser']) {
                // Supprimer le mot de passe avant de renvoyer la réponse
                unset($user['passwordUser']);

                // Répondre avec les informations de l'utilisateur
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Connexion réussie',
                    'user' => $user
                ]);
            } else {
                // Mot de passe incorrect
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Mot de passe incorrect'
                ]);
            }
        } else {
            // Aucune correspondance trouvée pour cet email
            echo json_encode([
                'status' => 'error',
                'message' => 'Email non trouvé'
            ]);
        }
    } catch (PDOException $e) {
        // Gestion des erreurs SQL
        echo json_encode([
            'status' => 'error',
            'message' => 'Erreur lors de la requête SQL: ' . $e->getMessage()
        ]);
    }
}
