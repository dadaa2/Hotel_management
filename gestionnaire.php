<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des hôtels et des chambres</title>
    <h1>Gestion des hôtels et des chambres</h1>
    <script>
        //JS actualisation de la page lors de changement dans la base de donnée
        
        //function actualiserPage() {
        //    setTimeout(function(){
        //        location.reload(true);
        //    }, 5000);
        //}
    </script>
    <link rel="stylesheet" href="gestionnaire.css">

</head>
<body>
    <!-- Formulaire pour ajouter un hôtel -->
    <form action='gestionnaire.php' method='post' onsubmit='actualiserPage()'>
        <input type='text' class="input_text_" name='nouveau_nom_hotel' placeholder="Nom de l\'hôtel">
        <input type='text' class="input_text_" name='nouvelle_adresse_hotel' placeholder="Adresse de l\'hôtel">
        <input type='submit' name='ajouter_hotel' value="Ajouter l\'hôtel">
    </form>

    <form action="liste.php" method="post">
        <input type="submit" name="logout" class="logout_button" value="Deconnexion">
    </form>
</body>
</html>
<?php
    session_start();
    include("connexion.php");
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    //echo "affichage de vardump email,  password après connexion admin: <br> : ",var_dump($_SESSION),"<br>","<br>";

    //Session verification
    if(isset($_SESSION["user_id"])){
        $user_id = $_SESSION["user_id"];
        // Récupérer les ID des hôtels associés à l'utilisateur
        $sql_manage = "SELECT ID FROM manage WHERE ID_USER = '$user_id'";
        $result_manage = mysqli_query($conn, $sql_manage);
    
        if(mysqli_num_rows($result_manage) > 0){
            while($row_manage = mysqli_fetch_assoc($result_manage)){
                $hotel_id = $row_manage["ID"];
            
                // Récupérer les détails de l'hôtel
                $sql_hotel = "SELECT * FROM hotel WHERE ID = '$hotel_id'";
                $result_hotel = mysqli_query($conn, $sql_hotel);
            
                if(mysqli_num_rows($result_hotel) > 0){
                    $row_hotel = mysqli_fetch_assoc($result_hotel);
                    $nom_hotel = $row_hotel["LIBELLE"];
                    $adresse_hotel = $row_hotel["ADRESSE"];
            
                    // Afficher les détails de l'hôtel et les chambres
                    echo "<div class='hotel-info'>";        //début conteneur hotel-info
                    echo "<h3>Nom de l'hôtel : $nom_hotel</h3>";
                    echo "<p>Adresse de l'hôtel : $adresse_hotel</p>";

                    // Formulaire pour modifier le nom et l'adresse de l'hôtel
                    echo "<form action='gestionnaire.php' method='post' onsubmit='actualiserPage()'>";
                    echo "<input type='hidden' name='hotel_id' value='$hotel_id'>";
                    echo "<input type='text' class='input_text' name='nouveau_nom_hotel' placeholder='Nouveau nom de cet hôtel'>";
                    echo "<input type='text' class='input_text' name='nouvelle_adresse_hotel' placeholder='Nouvelle adresse de cet hôtel'>";
                    echo "<input type='submit' name='modifier_hotel' value=Modifier cet hôtel>";
                    echo "</form>";

                    // Formulaire pour ajouter une chambre
                    echo "<form action='gestionnaire.php' method='post' onsubmit='actualiserPage()'>";
                    echo "<input type='hidden' name='hotel_id' value='$hotel_id'>";
                    echo "<input type='text' class='input_text' name='nouveau_nom_chambre' placeholder='Nom du nouveau chambre'>";
                    echo "<input type='submit' name='ajouter_chambre' value='Ajouter chambre'>";
                    echo "</form>";

                    // Formulaire pour supprimer l'hôtel
                    echo "<form action='gestionnaire.php' method='post' onsubmit='actualiserPage()'>";
                    echo "<input type='hidden' name='hotel_id' value='$hotel_id'>";
                    echo "<input type='submit' name='supprimer_hotel' value='Supprimer cet hôtel'>";
                    echo "</form>";
            
                    // Récupérer les chambres associées à l'hôtel
                    $sql_chambres = "SELECT * FROM room WHERE ID_HOTEL = '$hotel_id'";
                    $result_chambres = mysqli_query($conn, $sql_chambres);
            
                    if(mysqli_num_rows($result_chambres) > 0){
                        echo "<div class='chambres'>";
                        while($row_chambre = mysqli_fetch_assoc($result_chambres)){
                            $libelle_chambre = $row_chambre["LIBELLE"];
                            $chambre_id = $row_chambre["ID"];
            
                            // Afficher les détails de la chambre 
                            echo "<div class='chambre'>";       //début conteneur chambre
                            
                                echo "<div class='nom'>";   
                                echo "<span id='chambre_$chambre_id'>$libelle_chambre</span><br>"; //nom de la chambre
                                echo "</div class='nom'>";   
                                
                                echo "<div class='formulaire'>";   
                                // Formulaire pour modifier le nom de la chambre
                                echo "<form action='gestionnaire.php' method='post' onsubmit='actualiserPage()'>";
                                echo "<input type='text' class='new_name_chamber' name='nouveau_nom_chambre' placeholder='Nouveau nom'>";
                                echo "<input type='hidden' name='chambre_id' value='$chambre_id'>";
                                echo "<input type='submit' name='modifier_nom_chambre' value='Modifier le nom'>";
                                // Formulaire pour supprimer la chambre
                                echo "<form action='gestionnaire.php' method='post' onsubmit='actualiserPage()'>";
                                echo "<input type='hidden' name='chambre_id' value='$chambre_id'>";
                                echo "<input type='submit' name='supprimer_chambre' value='Supprimer la chambre'>";
                                echo "</form>";
                                echo "</div class='formulaire'>";   
                                
                            echo "</div>"; // Fin du conteneur pour la chambre
                        }
                        echo "</div>"; // Fin conteneur chambres
                    } else {
                        echo "Cet hôtel n'a pas de chambres.";
                    }
            
                    echo "</div>"; // Fin conteneur hôtel-info
                }
            }
        } else {
            echo "Cet utilisateur n'est associé à aucun hôtel.";
        }

    } else {
        echo "Vous devez être connecté pour accéder à cette page.";
    }
?>
<?php
    //Fonction modifier chambre
    if(isset($_POST["modifier_nom_chambre"])){
        $nouveau_nom_chambre = $_POST["nouveau_nom_chambre"];
        $chambre_id = $_POST["chambre_id"];
        // Mettez à jour le nom de la chambre dans la base de données
        $sql_update_chambre = "UPDATE room SET LIBELLE = '$nouveau_nom_chambre' WHERE ID = '$chambre_id'";
        if (mysqli_query($conn, $sql_update_chambre)) {
            header("Location: " . $_SERVER['PHP_SELF']);
            echo "Le nom de la chambre a été modifié avec succès.";
        } else {
            echo "Erreur lors de la modification du nom de la chambre : " . mysqli_error($conn);
        }
    }

    //Fonction ajouter chambre
    if(isset($_POST["ajouter_chambre"])){
        $hotel_id = $_POST["hotel_id"];
        $nouveau_nom_chambre = $_POST["nouveau_nom_chambre"];
        // Vérification des valeurs non nulles
        if($hotel_id && $nouveau_nom_chambre){
            // Insertion de la nouvelle chambre dans la base de données
            $sql = "INSERT INTO room (ID_HOTEL, LIBELLE) 
                    VALUES ('$hotel_id', '$nouveau_nom_chambre')";
            if (mysqli_query($conn, $sql)) {
                header("Location: " . $_SERVER['PHP_SELF']);
                echo "Chambre ajoutée avec succès.";
            } else {
                echo "Erreur lors de l'ajout de la chambre : " . mysqli_error($conn);
            }
        } else {
            echo "Veuillez remplir tous les champs.";
        }
    }
    //Fonction suppriemr chambre
    if(isset($_POST["supprimer_chambre"])){
        $chambre_id = $_POST["chambre_id"];
        // Vérification que l'ID de la chambre n'est pas nul
        if($chambre_id){
            // Suppression de la chambre dans la bdd
            $sql = "DELETE FROM room WHERE ID = '$chambre_id'";
            if (mysqli_query($conn, $sql)) {
                header("Location: " . $_SERVER['PHP_SELF']);
                echo "Chambre supprimée avec succès.";
            } else {
                echo "Erreur lors de la suppression de la chambre : " . mysqli_error($conn);
            }
        } else {
            echo "ID de la chambre non spécifié.";
        }
    }
    //Fonction ajouter Hotel
    if(isset($_POST["ajouter_hotel"])){
        $nouveau_nom_hotel = $_POST["nouveau_nom_hotel"];
        $nouvelle_adresse_hotel = $_POST["nouvelle_adresse_hotel"];

        // Vérifiez que les champs ne sont pas vides
        if(!empty($nouveau_nom_hotel) && !empty($nouvelle_adresse_hotel)){
            $sql_ajout_hotel = "INSERT INTO hotel (LIBELLE, ADRESSE) VALUES ('$nouveau_nom_hotel', '$nouvelle_adresse_hotel')";
            if(mysqli_query($conn, $sql_ajout_hotel)){
                // Récupérez l'ID de l'hôtel nouvellement ajouté
                $nouvel_hotel_id = mysqli_insert_id($conn);
                header("Location: " . $_SERVER['PHP_SELF']);

                // Ajoutez une entrée dans la table manage
                $sql_ajout_manage = "INSERT INTO manage (ID, ID_USER) VALUES ('$nouvel_hotel_id', '$user_id')";
                if(mysqli_query($conn, $sql_ajout_manage)){
                    echo "L'hôtel a été ajouté avec succès.";
                } else {
                    echo "Erreur lors de l'ajout dans la table manage : " . mysqli_error($conn);
                }
            } else {
                echo "Erreur lors de l'ajout de l'hôtel : " . mysqli_error($conn);
        }
        } else {
            echo "Veuillez remplir tous les champs.";
        }
    }


    //Fonction modifier Hotel
    if(isset($_POST["modifier_hotel"])){
        $hotel_id = $_POST["hotel_id"];
        $nouveau_nom_hotel = $_POST["nouveau_nom_hotel"];
        $nouvelle_adresse_hotel = $_POST["nouvelle_adresse_hotel"];

        // Vérifiez que les champs ne sont pas vides
        if(!empty($nouveau_nom_hotel) && !empty($nouvelle_adresse_hotel)){
            $sql_modification_hotel = "UPDATE hotel SET LIBELLE='$nouveau_nom_hotel', ADRESSE='$nouvelle_adresse_hotel' WHERE ID='$hotel_id'";
            if(mysqli_query($conn, $sql_modification_hotel)){
                header("Location: " . $_SERVER['PHP_SELF']);
                echo "L'hôtel a été modifié avec succès.";
            } else {
                echo "Erreur lors de la modification de l'hôtel : " . mysqli_error($conn);
            }
        } else {
            echo "Veuillez remplir tous les champs.";
        }
    }

    //Fonction supprimer Hotel
    if(isset($_POST["supprimer_hotel"])){
        $hotel_id = $_POST["hotel_id"];
            //$sql_suppression_manage = "DELETE FROM manage WHERE ID = '$hotel_id'";
            //mysqli_query($conn, $sql_suppression_manage);

            $sql_suppression_hotel = "DELETE FROM hotel WHERE ID='$hotel_id'";
            if(mysqli_query($conn, $sql_suppression_hotel)){
                // Supprimez également les chambres associées à cet hôtel
                $sql_suppression_chambres = "DELETE FROM room WHERE ID_HOTEL='$hotel_id'";
                mysqli_query($conn, $sql_suppression_chambres);
                header("Location: " . $_SERVER['PHP_SELF']);
                echo "L'hôtel a été supprimé avec succès.";
            } else {
            echo "Erreur lors de la suppression de l'hôtel : " . mysqli_error($conn);
        }
    }
    if(isset($_POST["logout"])){
        session_destroy();
        header("Location: index.php");
    }
    mysqli_close($conn);
?>