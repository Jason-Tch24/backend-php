<?php
        require_once(ROOT . "/utils/IController.php");
        require_once(ROOT . "/utils/AbstractController.php");
        require_once(ROOT . "/utils/functions.php");

        require_once(ROOT . "/services/BadgeService.php");

        class BadgePostController extends AbstractController implements IController {

            private BadgeService $service;

            public function __construct($form, $controllerName) {
                parent::__construct($form, $controllerName);
                $this->service = new BadgeService();
            }
            
            function checkForm() {
                if ( ! (isset($this->form['badge']) && isset($this->form['nom']))){
                    _400_Bad_Request(" Bad syntaxe ");
                }
                $this->badge = $this->form['badge'];
                $this->nom = $this->form['nom'];
            }
            function checkCybersec() {
                if(strlen( $this->badge) == 4){
                    if( ! ctype_digit($this->badge)){
                        _400_Bad_Request(" Bad syntaxe type error " . $this->badge);
                    }
                }else{
                    _400_Bad_Request(" Bad syntaxe " . $this->badge);
                }

                if( ! preg_match("/^[A-Za-z0-9 -]{2,30}$/", $this->nom)){
                    _400_Bad_Request("Bad syntaxe invalide name " . $this->nom);
                }
            }
            function checkRights() {
                error_log(__FUNCTION__);
            }
            function processRequest() {
                $this->newBadge = Badge::create($this->badge, $this->nom);
                try{
                    $this->newBadge = $this->service->insertData($this->newBadge);
                }catch(PDOException $e){
                    if ($e->errorInfo[1] == 1062) {
                        headerAndDie("HTTP/1.1 499 Entity duplicated Badge " . $this->badge);
                    }
                    throw $e;
                }
            }
            function processResponse() {
                echo json_encode($this->newBadge);
            }
    }
?>