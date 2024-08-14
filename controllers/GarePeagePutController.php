<?php
    require_once(ROOT . "/utils/IController.php");
    require_once(ROOT . "/utils/AbstractController.php");
    require_once(ROOT . "/utils/functions.php");

    require_once(ROOT . "/services/GarePeageService.php");

    class GarePeagePutController extends AbstractController implements IController {

        private GarePeageService $service;

        public function __construct($form, $controllerName) {
            parent::__construct($form, $controllerName);
            $this->service = new GarePeageService();
        }
        
        function checkForm() {
            if ( isset($this->form['garepeage']) ) {
				$this->garepeage = $this->form['garepeage'];
			}
            if ( isset($this->form['nompeage']) ) {
				$this->nompeage = $this->form['nompeage'];
			}
        }

        function checkCybersec() {
            if(strlen( $this->garepeage) == 4){
                if( ! ctype_digit($this->garepeage)){
                    _400_Bad_Request(" Bad syntaxe type error " . $this->garepeage);
                }
            }else{
                _400_Bad_Request("Bad syntaxe " . $this->garepeage);
            }
            if( ! preg_match("/^[A-Za-z0-9 -]{2,30}$/", $this->nompeage)){
                _400_Bad_Request("Bad syntaxe invalide name " . $this->nompeage);
            }
        }

        function checkRights() {
            error_log(__FUNCTION__);
        }
        function processRequest() {
            
            $this->modifieGarePeage = GarePeage::create($this->garepeage, $this->nompeage);

            $this->resultgarepeage = $this->service->modifie($this->modifieGarePeage);
        }
        function processResponse() { 
            if($this->resultgarepeage == null){
                echo "le numero de garepeage ". $this->garepeage ." est introuvable ou aucune modification est apporter au nom!";
                _404_Not_Found_("garepeage = " . $this->garepeage);
            }
            echo json_encode($this->resultgarepeage);
        }
    }
?>