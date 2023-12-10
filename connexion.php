<?php
    $serveur = "localhost";
    $user = "root";
    $mdp = "root";
    $base_de_donnees = "2hotel";
    $conn = "";
    
    $conn = mysqli_connect( $serveur, 
                            $user, 
                            $mdp, 
                            $base_de_donnees);
    
    //if($conn){
    //    echo"<footer> Connexion au base de donnée établie ! <br></footer>";
    //}
    //else{
    //    echo "Connexion au base de données ne s'est pas effectué !";}
?>