<?php
include "user-pdo.php";
$obj=new User("Tom13", "azerty",
"thomas@gmail.com", "Thomas", "DUPONT"); // on crée une instance d'un user
var_dump($obj); 
echo "<br>";
echo "<br>";
print_r($obj->register("Tom13", "azerty",
"thomas@gmail.com", "Thomas", "azerty")); // on l'enregistre en BDD
echo "<br>";
$obj->connect("Tom13", "azerty"); //on fait une connexion
echo "<br>";
print_r($obj->getAllinfos()); //on recupere tous ses infos
echo "<br>";
echo "<br>";
echo $obj->getLogin();//on recupère le login
echo "<br>";
echo $obj->getEmail();//on recupère l'email
echo "<br>";
echo $obj->getFirstname();//on recupère le prenom
echo "<br>";
echo $obj->getLastname();//on recupère le prenom
echo "<br>";
echo $obj->isConnected();// on verifie s'il est connecté (retourne 1 si connecté)
echo "<br>";
echo "<br>";
$obj->update("Ben2", "azerty",
"tomcruz@gmail.com", "Thomas", "DUPONT");
var_dump($obj);
echo "<br>";
echo "<br>";
echo $obj->delete(); //on supprime l'user
echo "<br>";
echo "<br>";
var_dump($obj); 