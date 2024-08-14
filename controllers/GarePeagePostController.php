<?php
    require_once(ROOT . "/utils/IController.php");
    require_once(ROOT . "/utils/AbstractController.php");
    require_once(ROOT . "/utils/functions.php");

    require_once(ROOT . "/services/GarePeageService.php");

    class GarePeagePostController extends AbstractController implements IController {

        private GarePeageService $service;

        public function __construct($form, $controllerName) {
            parent::__construct($form, $controllerName);
            $this->service = new GarePeageService();
        }
        
        function checkForm() {
            if ( ! (isset($this->form['garepeage']) && isset($this->form['nompeage']))){
                _400_Bad_Request(" Bad syntaxe ");
            }
            $this->garepeage = $this->form['garepeage'];
            $this->nompeage = $this->form['nompeage'];
        }

        function checkCybersec() {
            if(strlen( $this->garepeage) == 4){
                if( ! ctype_digit($this->garepeage)){
                    _400_Bad_Request(" Bad syntaxe type error " . $this->garepeage);
                }
            }else{
                _400_Bad_Request(" Bad syntaxe " . $this->garepeage);
            }

            if( ! preg_match("/^[A-Za-z0-9 -]{2,30}$/", $this->nompeage)){
                _400_Bad_Request("Bad syntaxe invalide name " . $this->nompeage);
            }
        }

        function checkRights() {
            error_log(__FUNCTION__);
        }
        function processRequest() {
            $this->newGarePeage = GarePeage::create($this->garepeage, $this->nompeage);
            try{
                $this->newGarePeage = $this->service->insertData($this->newGarePeage);
            }catch(PDOException $e){
                if ($e->errorInfo[1] == 1062) {
                    headerAndDie("HTTP/1.1 499 Entity duplicated Badge " . $this->garepeage);
                }
                throw $e;
            }
        }
        function processResponse() {            
            echo json_encode($this->newGarePeage);
        }
    }
?>