<?php
    session_start();
    include("connexion.php");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de connexion du site</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
<div class="container">
        <h1>Page de connexion de l'hôtel</h1>
        <form action="index.php" method="post">
            <h2>Enregistrement du compte</h2>
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" name="nom" id="nom">
            </div>
            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" name="name" id="name">
            </div>
            <div class="form-group">
                <label for="mail">Email</label>
                <input type="text" name="mail" id="mail">
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password">
            </div>
            <div class="form-group">
                <input type="submit" name="register" value="S'enregistrer">
            </div>
        </form>

        <form action="index.php" method="post">
        <h2>Connexion au site</h2>
            <div class="form-group">
                <label for="login_email">Email</label>
                <input type="text" name="login_email" id="login_email">
            </div>
            <div class="form-group">
                <label for="login_password">Mot de passe</label>
                <input type="password" name="login_password" id="login_password">
            </div>
            <div class="form-group">
                <input type="submit" name="login" value="Se connecter">
            </div>
        </form>
    </div>
    <!--<a href="liste.php">Connexion vers la page de liste d'hôtel <br></a>-->
    <h3>Client exemple : george@gmail.com et george</h3>
    <h3>User admin exemple : david@gmail.com et davidd</h3>
    <h3>User gestion exemple : mama@gmail.com et ecole</h3>
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

    //Section enregistrement de compte
    if(isset($_POST["register"])){
        $nom = $_POST["nom"];
        $name = $_POST["name"];
        $mail = $_POST["mail"];
        $password = $_POST["password"];
        
        $_SESSION["nom"] = $nom;
        $_SESSION["name"] = $name;
        $_SESSION["mail"] = $mail;
        $_SESSION["password"] = $password;
        
        echo $_SESSION["nom"] ."<br>";
        echo $_SESSION["name"] ."<br>";
        echo $_SESSION["mail"] ."<br>";
        echo $_SESSION["password"] ."<br><br>";

        $sql = "INSERT INTO client (nom, prenom, mail, mot_de_passe) 
                            VALUES ('$nom','$name', '$mail', '$password')";
        if (mysqli_query($conn, $sql)) {
            echo "Enregistrement réussi";
        } else {
            echo "Erreur lors de l'enregistrement : " . mysqli_error($conn);
        }
    }

    //section de connexion
    if(isset($_POST["login"])){
        $login_email = $_POST["login_email"];
        $login_password = $_POST["login_password"];
        //echo "affichage de vardump email,  password après clique connecter: <br> : ",var_dump($login_email, $login_password);
        //echo "Traitement de connexion pendant 5 secondes... <br>";
        
        // Effectuer la vérification du compte dans la base de données
        $sql_client = "SELECT * FROM client WHERE mail='$login_email' AND mot_de_passe='$login_password'";
        $result = mysqli_query($conn, $sql_client);
        //Vérification client
        if(mysqli_num_rows($result) == 1){
            // L'utilisateur est connecté avec succès, on créer la session
            $row = mysqli_fetch_assoc($result);
            //$_SESSION["nom"] = $row["nom"];
            //$_SESSION   ["name"] = $row["prenom"];
            $_SESSION["mail"] = $login_email;   
            $_SESSION["password"] = $login_password;
            $_SESSION["client_id"] = $row["ID"];
            
            echo "affichage de vardump2 : <br>",var_dump($_SESSION);
            header("Refresh: 10; URL=liste.php");
            echo "Vous êtes connecté. Vous serez redirigé dans 10 secondes...";
            exit();
        } 
            //echo "Bloc de code pour client exécuté";
            //Vérification admin
            $sql_user = "SELECT * FROM user WHERE MAIL ='$login_email' AND PASSWORD ='$login_password'";
            $result_user = mysqli_query($conn, $sql_user);
            if(mysqli_num_rows($result_user) == 1){
                if (!$result) {
                    die('Erreur dans la requête : ' . mysqli_error($conn));
                }
                $row = mysqli_fetch_assoc($result_user);
                echo "User trouvé <br>"; //var_dump($_SESSION);

                $_SESSION["mail"] = $login_email;   
                $_SESSION["password"] = $login_password;
                $_SESSION["user_id"] = $row["ID"];
                $_SESSION["user_role"] = $row["ROLE"];
                echo "ROLE : ",var_dump($row["ROLE"]), "<br>";
                echo "Role dans sessions : ", $_SESSION["user_role"], "<br>";
                
                header("Refresh: 5; URL=gestionnaire.php");
                echo "Vous êtes connecté en tant que gestionnaire. Vous serez redirigé dans 5 secondes...";
                exit();
                //if ($_SESSION["user_role"] == "ADMIN") {
                //    header("Refresh: 10; Location: admin.php");
                //    echo "session admin : ",var_dump($_SESSION);
                //    exit();
//
                //} elseif ($_SESSION["user_role"] == "GESTION") {
                //    header("Refresh: 10; Location: gestionnaire.php");
                //    echo "session gestion : ",var_dump($_SESSION);
                //    exit();
                //}             
            } else {
                echo "Erreur id session : ",var_dump($_SESSION);
                echo "Identifiants incorrects. Veuillez réessayer.";
            }
        }

    mysqli_close($conn);
?>