<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des réservations</title>
    <link rel="stylesheet" href="listebook.css">

</head>
<body>
    <h1>Vos réservation</h1>
    <a href="liste.php">Retour vers la liste des hôtels</a>

</body>
</html>
<?php
    include("connexion.php");
    // Vérification de la session ou de l'authentification
    session_start();
    if(isset($_SESSION["mail"]) && isset($_SESSION["password"])){
        echo "Vous êtes bien connecté";
    } else {
        echo "<p>Vous devez être connecté pour accéder à cette page.</p>";
        echo "<a href='index.php'>Retour à la page d'accueil</a>";
        exit();
    }
    //récupérer book depuis la base de données
    $sql_reservations = "       SELECT book.ID, room.LIBELLE 
                                AS nom_chambre, hotel.LIBELLE 
                                AS nom_hotel, book.ARRIVALDATE, book.DEPARTUREDATE
                                FROM book
                                JOIN room ON book.ID_ROOM = room.ID
                                JOIN hotel ON room.ID_HOTEL = hotel.ID
                                WHERE book.ID_CLIENT = '{$_SESSION['client_id']}'";   
     $result_reservations = mysqli_query($conn, $sql_reservations);
    //vérif si y a des réservations
    if (mysqli_num_rows($result_reservations) > 0) {
        echo "<table>";
        echo "<tr><th>Nom de la chambre</th><th>Nom de l'hôtel</th><th>Date d'arrivée</th><th>Date de départ</th><th>Action</th></tr>";
        while ($row = mysqli_fetch_assoc($result_reservations)) {
            echo "<tr>";
            //echo "<td>" . $row['ID'] . "</td>";
            echo "<td>" . $row['nom_chambre'] . "</td>";
            echo "<td>" . $row['nom_hotel'] . "</td>";
            echo "<td>" . $row['ARRIVALDATE'] . "</td>";
            echo "<td>" . $row['DEPARTUREDATE'] . "</td>";
            echo "<td>
                <form action='listebook.php' method='post'>
                    <input type='date' name='nouvelle_date_arrivee' placeholder='Nouvelle date de départ'>
                    <input type='date' name='nouvelle_date_depart' placeholder='Nouvelle date d'arrivée'>
                    <input type='hidden' name='reservation_id' value='" . $row['ID'] . "'>
                    <input type='submit' name='modifier_reservation' value='Modifier la réservation'>
                </form>
                |
                <form action='listebook.php' method='post'>
                    <input type='hidden' name='reservation_id' value='" . $row['ID'] . "'>
                    <input type='submit' name='supprimer_reservation' value='Supprimer'>
                </form>
                  </td>";
            echo "</tr>";
            }
            echo "</table>";
    } else {
        echo "Aucune réservation trouvée.";
    }
    //Suppression réservation
    if(isset($_POST["supprimer_reservation"])){
        $reservation_id = $_POST["reservation_id"];
        $sql_suppression = "DELETE FROM book WHERE ID = '$reservation_id'";
        if (mysqli_query($conn, $sql_suppression)) {
            header("Location: " . $_SERVER['PHP_SELF']);
            echo "Réservation supprimée avec succès";
        } else {
            echo "Erreur lors de la suppression de la réservation : " . mysqli_error($conn);
        }
    }
    // Fonction pour modifier une réservation
    if(isset($_POST["modifier_reservation"])){
        $nouvelle_arrivee = $_POST["nouvelle_date_arrivee"];
        $nouveau_depart = $_POST["nouvelle_date_depart"];
        $reservation_id = $_POST["reservation_id"];
        // Mettre à jour les dates d'arrivée et de départ dans la base de données
        $sql_update_reservation = "UPDATE book SET ARRIVALDATE = '$nouvelle_arrivee', DEPARTUREDATE = '$nouveau_depart' WHERE ID = '$reservation_id'";
        // Exécuter la requête de mise à jour
        if (mysqli_query($conn, $sql_update_reservation)) {
            header("Location: " . $_SERVER['PHP_SELF']);
            echo "La réservation a été modifiée avec succès.";
        } else {
            echo "Erreur lors de la modification de la réservation : " . mysqli_error($conn);
        }
}
?>