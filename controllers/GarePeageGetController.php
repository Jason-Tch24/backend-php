<?php
    require_once(ROOT . "/utils/IController.php");
    require_once(ROOT . "/utils/AbstractController.php");
    require_once(ROOT . "/utils/functions.php");

    require_once(ROOT . "/services/GarePeageService.php");

    class GarePeageGetController extends AbstractController implements IController {

        private GarePeageService $service;

        public function __construct($form, $controllerName) {
            parent::__construct($form, $controllerName);
            $this->service = new GarePeageService();
        }
        
        function checkForm() {
            if( isset($this->form['id'])){
                $this->id = $this->form['id'];
            }
        }

        function checkCybersec() {
            if ( isset( $this->id) ) {
                if( ctype_digit ( $this->form['id'])){
                    $this->id = intval($this->form['id']);
                }else{
                    _400_Bad_Request("Bad value for id " .$this->form['id']);
                }
            }
        }

        function checkRights() {
            error_log(__FUNCTION__);
        }
        function processRequest() {
            if( isset($this->id)){
                $this->garepeage = $this->service->findOne($this->id);
            }else{
                $this->garepeage = $this->service->findAll();
            }
        }
        function processResponse() {
            echo json_encode($this->garepeage);
        }
    }
?>