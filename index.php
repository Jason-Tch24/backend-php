<?php 
    define("ROOT" , dirname(__FILE__));
    require_once(ROOT . '/utils/functions.php');
    $FORM = extractForm();
    //var_dump($FORM);
    $ROUTE = extractRoute($FORM);
    // on va construire le controlleur de maniere dynamique
    // il devra implementer l'interface IController
    $controller = createController( $FORM, $ROUTE);
    $controller->execute();

// var_dump($FORM);
// echo "ok\n";
?>