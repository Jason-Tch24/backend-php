<?php

        function headerAndDie ($header) {
            header($header); // built in : ecrit le header http
            die(); // built in : arrete le traitement de la requete
        }


        function _404_Not_Found_($msg = "") { 
            headerAndDie("HTTP/1.1 404 Not Found " . $msg);
        }

        function _400_Bad_Request( $msg = "") {
            headerAndDie("HTTP/1.1 400 Bad request" . $msg);
        }
    

        function _405_Metho_Not_Allowed() {
            headerAndDie("HTTP/1.1 405 Method Not Allowed");
        }


        // selon la methode de la requete, renvoie le formulaire
        function extractForm() {
            switch ( $_SERVER['REQUEST_METHOD']){
                case 'GET' : return $_GET;
                case 'POST' : return $_POST;
                case 'DELETE' : return $_GET; // DELETE est une sorte de GET
                case 'PUT' : // Cas particulier pour le put
                        $raw = file_get_contents('php://input'); // php built in function
                        $form = [];
                        parse_str($raw, $form); // built in : String requete HTTP -> tableau associatif
                        return $form;
                default : _405_Metho_Not_Allowed();
            }
        }

    function extractRoute($form) {
        if ( ! isset($form['route']) ) {
            return "Accueil";
        }
        $ROUTE = $form['route'];
        if (preg_match('/^[A-Za-z]{1,64}$/' , $ROUTE)) {
            return $ROUTE;
        }
        _400_Bad_Request("route ' " . $ROUTE . "'");
    }

    function createController($form, $route) {
        $METHOD = strtolower( $_SERVER['REQUEST_METHOD']); // tout en minuscule ex: get
        $METHOD = ucfirst($METHOD); // Puis la premiere lettre en majuscule : Get
        $FILE = ROOT . "/controllers/" . $route .$METHOD . "Controller.php";
        if ( ! file_exists($FILE)) { // Mon controller n'existe pas, donc 404
            _404_Not_Found_($route . $METHOD);
        }
        require($FILE); // Je sais que mon fichier existe donc je peux le require
        $className = $route . $METHOD . 'Controller'; // je construis le nom de la classe
        $controller = new $className($form, $route . $METHOD); // Que je peux utliser pour creer une nouvelle instance
        return $controller; // Si tout se passe bien, il existe une instance que je retourne
    }

    function isDecimal($str) {
        return filter_var($str, FILTER_VALIDATE_FLOAT) !== false && (float)$str > 0;
    }
?>