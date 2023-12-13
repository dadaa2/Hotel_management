<?php
    session_start();
    include("connexion.php");
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    //echo "affichage de vardump email et password<br> : ",var_dump($_SESSION),"<br>";
    
    if(isset($_SESSION["mail"]) && isset($_SESSION["password"])){
        echo "Vous êtes bien connecté";
    } else {
        echo "<p>Vous devez être connecté pour accéder à cette page.</p>";
        echo "<a href='index.php'>Retour à la page d'accueil</a>";
        exit();
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page des hôtels</title>
    <link rel="stylesheet" href="liste.css">
</head>
<body>
    <form class="deconnexion" action="liste.php" method="post">
        <input type="submit" name="logout" value="Déconnexion">
    </form>
    <a href="listebook.php">Voir les réservations</a>


<h1 class="titre" >La page des hôtels</h1>
    <main> 
            <section id="hotel-list">
            <h2 class="titre2" >Liste des Hôtels</h2>
            <?php
                // Récupérer la liste des hôtels depuis la base de données
                $sql = "SELECT * FROM hotel";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<div class='hotel'>";
                        echo "<h3>" . $row["LIBELLE"] . "</h3>";
                        echo "<p>" . $row["ADRESSE"] . "</p>";

                        // Récupérer la liste des chambres pour cet hôtel
                        $hotel_id = $row["ID"];
                        $sql_rooms = "SELECT * FROM room WHERE ID_HOTEL = '$hotel_id'";
                        $result_rooms = mysqli_query($conn, $sql_rooms);
                    
                        if (mysqli_num_rows($result_rooms) > 0) {
                            echo "<ul>";
                            while($room_row = mysqli_fetch_assoc($result_rooms)) {
                                echo "<li>" . $room_row["LIBELLE"] . "</li>";
                            }
                            echo "</ul>";
                        } else {
                            echo "<p>Aucune chambre disponible pour cet hôtel.</p>";
                        }
                    
                        echo "<form action='reservation.php' method='post'>";
                        echo "<input type='hidden' name='hotel_id' value='" . $row["ID"] . "'>";
                        echo "<input type='submit' name='select_hotel' value='Sélectionner'>";
                        echo "</form>";
                        echo "</div>";
                    }
                } else {
                    echo "Aucun hôtel disponible.";
                }
            ?>
        </section>
    </main>
</body>
</html>


<?php
    if(isset($_POST["logout"])){
        session_destroy();
        header("Location: index.php");
    }
?>
