<?php

    require './modele/modele.class.php';

// Affiche la page d'accueil
    function accueil() {
        require './vue/vueAccueil.php';
    }
    function login(){
        require './vue/vueLogin.php';
    }

    function erreur() {
        require './vue/vueErreur.php';
    }
    /*
    function connec(){

        header('Location:./admin/administration.php');
    }
    */
    function inscription(){
        require './vue/vueInscription.php';
    }
    function demandeInscription(){
        require './vue/vueDemandeInscription.php';
    }
    function spectacle(){
        require './vue/vueSpectacle.php';
    }
// page de contact
    function contact(){
        require './vue/vueContact.php';
    }
    function noter(){
        require './vue/vueNoter.php';
    }

    function deco(){
        require 'vue/vueDeconnection.php';
    }
    function connexion()
    {
        require './vue/vueIdentification.php';
    }

    function recherche(){
        require 'vue/vueRecherche.php';
    }



?>