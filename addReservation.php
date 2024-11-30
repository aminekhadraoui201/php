<?php
// Inclure le fichier de connexion à la base de données
require_once("dbConnection.php");

// Vérifier si le formulaire a été soumis
if (isset($_POST['submit'])) {
    // Récupérer les données du formulaire
    $name = mysqli_real_escape_string($mysqli, $_POST['name']);
    $date = mysqli_real_escape_string($mysqli, $_POST['date']);
    $telephone = mysqli_real_escape_string($mysqli, $_POST['telephone']);

    // Convertir le format de date s'il contient un "T"
    $date = str_replace('T', ' ', $date);

    // Validation des champs vides
    if (empty($name) || empty($date) || empty($telephone)) {
        if (empty($name)) {
            echo "<font color='red'>Le champ Nom est vide.</font><br/>";
        }
        if (empty($date)) {
            echo "<font color='red'>Le champ Date est vide.</font><br/>";
        }
        if (empty($telephone)) {
            echo "<font color='red'>Le champ Téléphone est vide.</font><br/>";
        }
        echo "<br/><a href='javascript:self.history.back();'>Retourner</a>";
    } else {
        // Validation du format de la date
        $dateTime = DateTime::createFromFormat('Y-m-d H:i', $date);
        if ($dateTime && $dateTime->format('Y-m-d H:i') === $date) {
            // Vérifier si l'heure est comprise entre 00:00 et 23:00
            $hour = (int)$dateTime->format('H');
            if ($hour >= 0 && $hour <= 23) {
                // Insérer les données dans la base de données
                $result = mysqli_query($mysqli, "INSERT INTO reservation (name, date, telephone) VALUES ('$name', '$date', '$telephone')");

                if ($result) {
                    echo "<p><font color='green'>Réservation ajoutée avec succès !</font></p>";
                    echo "<a href='Terrain.html'>Retourne</a>";
                } else {
                    echo "<font color='red'>Erreur lors de l'insertion des données : " . mysqli_error($mysqli) . "</font>";
                }
            } else {
                echo "<font color='red'>L'heure doit être comprise entre 00:00 et 23:00.</font><br/>";
                echo "<br/><a href='javascript:self.history.back();'>Retourner</a>";
            }
        } else {
            echo "<font color='red'>Format de date invalide. Veuillez utiliser le format 'AAAA-MM-JJ HH:MM'.</font><br/>";
            echo "<br/><a href='javascript:self.history.back();'>Retourner</a>";
        }
    }
}
?>
