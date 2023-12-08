<?php
    session_start();
    include("connexion.php");
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    //echo "affichage de vardump de la sessions  :<br> ",var_dump($_SESSION),"<br>";

    
    if(isset($_SESSION["mail"]) && isset($_SESSION["password"])){ //vérification de la session
        if(isset($_POST["select_hotel"])){
            
            $selected_hotel_id = $_POST["hotel_id"];
            $_SESSION["selected_hotel_id"] = $selected_hotel_id;
            // récuperation les chambres associées à l'hôtel
            $sql_rooms = "SELECT * FROM room WHERE ID_HOTEL = '$selected_hotel_id'";
            $result_rooms = mysqli_query($conn, $sql_rooms);
            if (mysqli_num_rows($result_rooms) > 0) {
                
                //affichage du nom de l'hôtel
                $selected_hotel_id = $_POST["hotel_id"];
                $sql_hotel = "SELECT LIBELLE FROM hotel WHERE ID = '$selected_hotel_id'";
                $result_hotel = mysqli_query($conn, $sql_hotel);
                $row_hotel = mysqli_fetch_assoc($result_hotel);
                $libelle_hotel = $row_hotel["LIBELLE"];
                echo "<h2>Chambres disponibles de l'hotel : {$libelle_hotel}</h2>";

                //section de form qui parcours chaque chambre de l'hotel
                echo "<form action='reservation.php' method='post'>";
                while($room_row = mysqli_fetch_assoc($result_rooms)) {
                    echo "<input type='radio' name='room_id' value='" . $room_row["ID"] . "'>";
                    echo "<label>" . $room_row["LIBELLE"] . "</label><br>";
                

                    // Ajouts les champs pour la date de début et de fin pour les réservation 
                    echo "<label for='start_date'>Date de début :</label>";
                    echo "<input type='date' name='start_date' required><br>";
                    echo "<label for='end_date'>Date de fin :</label>";
                    echo "<input type='date' name='end_date' required><br>";
                    echo "<input type='submit' name='submit_reservation' value='Réserver'>";
                    echo "</form>";
                }

                //Section requete sql
                if(isset($_POST["submit_reservation"])){
                    $client_id = $_SESSION["client_id"];
                    $room_id = $_POST["room_id"];
                    $start_date = $_POST["start_date"];
                    $end_date = $_POST["end_date"];
                    $creation_date = date('Y-m-d'); // Date de création automatique
                    sleep(5);
                    echo"$client_id, $room_id, $start_date, $start_date, $end_date, $creation_date";
                    
                    echo "Requête SQL : " . $sql . "<br>"; // Débogage de la requête SQL
                    // Assurez-vous que les valeurs ne sont pas null
                    if($room_id && $start_date && $end_date && $creation_date){
                        // Insérez la réservation dans la base de données
                        $sql = "INSERT INTO book (ID_CLIENT, ID_ROOM, ARRIVALDATE, DEPARTUREDATE, CREATIONDATE) 
                                VALUES ('$client_id', '$room_id', '$start_date', '$end_date', '$creation_date')";
                        
                        echo "Requête SQL : " . $sql . "<br>"; // Débogage de la requête SQL


                        if (mysqli_query($conn, $sql)) {
                            echo "Réservation réussie.";
                        } else {
                            echo "Erreur lors de la réservation : " . mysqli_error($conn);
                        }
                    } else {
                        echo "Veuillez remplir tous les champs.";
                    }
                }

            } else {
                echo "<p>Aucune chambre disponible pour cet hôtel.</p>";
            }
        }
    } else {
        echo "Vous devez être connecté pour accéder à cette page.";
    }

?>

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

    <form action="liste.php" method="post">
        <input type="submit" name="logout" value="Deconnexion">
    </form>
   </body>
</html>

<?php
    if(isset($_POST["logout"])){
        session_destroy();
        header("Location: index.php");
    }
?>
