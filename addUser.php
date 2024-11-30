<?php
// Inclure le fichier de connexion à la base de données
require_once("dbConnection.php");

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer et sécuriser les données du formulaire
    $firstName = mysqli_real_escape_string($mysqli, $_POST['name-1']);
    $lastName = mysqli_real_escape_string($mysqli, $_POST['name-2']);
    $email = mysqli_real_escape_string($mysqli, $_POST['email']);
    $city = mysqli_real_escape_string($mysqli, $_POST['city']);
    $country = mysqli_real_escape_string($mysqli, $_POST['country']);
    $phone = mysqli_real_escape_string($mysqli, $_POST['phone']);
    $password = mysqli_real_escape_string($mysqli, $_POST['password']);

    // Vérifier les champs vides
    $errors = [];
    if (empty($firstName)) $errors[] = "Le champ Prénom est vide.";
    if (empty($lastName)) $errors[] = "Le champ Nom est vide.";
    if (empty($email)) $errors[] = "Le champ Email est vide.";
    if (empty($phone)) $errors[] = "Le champ Téléphone est vide.";
    if (empty($password)) $errors[] = "Le champ Mot de Passe est vide.";

    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<font color='red'>$error</font><br/>";
        }
        echo "<br/><a href='javascript:self.history.back();'>Retourner</a>";
    } else {
        // Hachage du mot de passe
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Préparer la requête SQL avec des paramètres liés pour plus de sécurité
        $stmt = $mysqli->prepare("INSERT INTO users (first_name, last_name, email, city, country, phone, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $firstName, $lastName, $email, $city, $country, $phone, $hashedPassword);

        // Exécuter la requête
        if ($stmt->execute()) {
            echo "<p><font color='green'>Utilisateur ajouté avec succès !</font></p>";
            echo "<a href='Log-in.html'>Login</a>";
        } else {
            echo "<font color='red'>Erreur lors de l'insertion des données : " . $stmt->error . "</font>";
        }

        // Fermer la déclaration
        $stmt->close();
    }
}
?>
