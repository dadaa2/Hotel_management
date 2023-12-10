
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation</title>
    <link rel="stylesheet" href="reservation.css">
</head>
<body>
    <h1>Réservation</h1>

    
   </body>
</html>

<?php
    if(isset($_POST["logout"])){
        session_destroy();
        header("Location: index.php");
    }
?>


<?php
    session_start();
    include("connexion.php");
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    //echo "affichage de vardump de la sessions  :<br> ",var_dump($_SESSION),"<br>";

    echo "<form action='liste.php' method='post'>";
    echo "<input type='submit' name='logout' value='Deconnexion'>";
    echo "</form>";

    if(isset($_SESSION["mail"]) && isset($_SESSION["password"])){ //vérification de la session
        if(isset($_POST["select_hotel"])){
            $selected_hotel_id = $_POST["hotel_id"];
            $_SESSION["selected_hotel_id"] = $selected_hotel_id;
            // récuperation les chambres associées à l'hôtel
            $sql_rooms = "SELECT * FROM room WHERE ID_HOTEL = '$selected_hotel_id'";
            $result_rooms = mysqli_query($conn, $sql_rooms);
            if (mysqli_num_rows($result_rooms) > 0) {                
                while ($room_row = mysqli_fetch_assoc($result_rooms)){
                    echo "<div class='room'>";
                    echo "<h3>" . $room_row["LIBELLE"] . "</h3>";
                
                    echo "<form action='reservation.php' method='post'>";
                    echo "<input type='hidden' name='room_id' value='" . $room_row["ID"] . "'>";
                    echo "<label for='start_date'>Date de début :</label>";
                    echo "<input type='date' name='start_date' required><br>";
                    echo "<label for='end_date'>Date de fin :</label>";
                    echo "<input type='date' name='end_date' required><br>";
                    echo "<input type='submit' name='submit_reservation' value='Réserver'>";
                    echo "</form>";
                
                    echo "</div>";
                }
                    } else {
                        echo "<p>Aucune chambre disponible pour cet hôtel.</p>";
                    }
            }
        } else {
            echo "Vous devez être connecté pour accéder à cette page.";
        }

?>
