<?php
// Inclure le fichier de connexion à la base de données
require_once("dbConnection.php");

session_start(); // Démarrer une session pour la gestion de l'utilisateur connecté

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer et sécuriser les données du formulaire
    $email = mysqli_real_escape_string($mysqli, $_POST['email']);
    $password = mysqli_real_escape_string($mysqli, $_POST['password']);

    // Vérifier que les champs ne sont pas vides
    if (empty($email) || empty($password)) {
        echo "<font color='red'>L'email ou le mot de passe est vide.</font><br/>";
    } else {
        // Vérifier si l'utilisateur existe dans la base de données
        $result = mysqli_query($mysqli, "SELECT * FROM users WHERE email = '$email'");

        // Si l'utilisateur existe
        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            // Vérifier le mot de passe
            if (password_verify($password, $user['password'])) {
                // Mot de passe correct, connecter l'utilisateur
                $_SESSION['user_id'] = $user['id']; // Sauvegarder l'id de l'utilisateur dans la session
                $_SESSION['email'] = $user['email']; // Sauvegarder l'email dans la session

                // Rediriger vers la page de réservation
                header("Location: Terrain.html");
                exit();
            } else {
                // Mot de passe incorrect
                echo "<font color='red'>Mot de passe incorrect.</font><br/>";
            }
        } else {
            // L'utilisateur n'existe pas
            echo "<font color='red'>Aucun utilisateur trouvé avec cet email.</font><br/>";
        }
    }
}
?>
