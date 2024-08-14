<?php

    require_once(ROOT . "/utils/IController.php");
    require_once(ROOT . "/utils/AbstractController.php");
    require_once(ROOT . "/utils/functions.php");

    require_once(ROOT . "/services/BadgeService.php");

    class BadgePutController extends AbstractController implements IController {
        private BadgeService $service;

        public function __construct($form, $controllerName) {
            parent::__construct($form, $controllerName);
            $this->service = new BadgeService();
        }
        
        function checkForm(){
			if ( isset($this->form['badge']) ) {
				$this->badge = $this->form['badge'];
			}
            if ( isset($this->form['nom']) ) {
				$this->nom = $this->form['nom'];
			}
        }
        function checkCybersec(){
			if(strlen( $this->badge) == 4){
                if( ! ctype_digit($this->badge)){
                    _400_Bad_Request(" Bad syntaxe type error " . $this->badge);
                }
            }else{
                _400_Bad_Request("Bad syntaxe " . $this->badge);
            }
            if( ! preg_match("/^[A-Za-z0-9 -]{2,30}$/", $this->nom)){
                _400_Bad_Request("Bad syntaxe invalide name " . $this->nom);
            }
        }
        function checkRights(){
			error_log(__FUNCTION__);
        }
        function processRequest(){
            $this->modifieBadge = Badge::create($this->badge, $this->nom);

            $this->resultbadge = $this->service->modifie($this->modifieBadge);
        }
        function processResponse(){
            if(!$this->resultbadge){
                echo "le numero de badge ". $this->badge ." est introuvable ou aucune modification est apporter au nom!";
                _405_Metho_Not_Allowed(" Badge id = " . $this->badge);
            }
            echo json_encode($this->resultbadge);
        }
    }

?>