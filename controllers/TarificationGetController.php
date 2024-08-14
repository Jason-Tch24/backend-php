<?php
    require_once(ROOT . "/utils/IController.php");
    require_once(ROOT . "/utils/AbstractController.php");
    require_once(ROOT . "/utils/functions.php");

    require_once(ROOT . "/services/TarificationService.php");

    class TarificationGetController extends AbstractController implements IController {

        private TarificationService $service;

        public function __construct($form, $controllerName) {
            parent::__construct($form, $controllerName);
            $this->service = new TarificationService();
        }
        
        function checkForm() {
            if(isset($this->form['source']) && isset($this->form['destination']) ){
                $this->source = $this->form['source'];
                $this->destination = $this->form['destination'];
            }
        }

        function checkCybersec() {
            if ( isset( $this->source) ) {
                if( ctype_digit ( $this->form['source'])){
                    $this->source = intval($this->form['source']);
                }else{
                    _400_Bad_Request("Bad value for source " .$this->form['source']);
                }
            }
            if ( isset( $this->destination) ) {
                if( ctype_digit ( $this->form['destination'])){
                    $this->destination = intval($this->form['destination']);
                }else{
                    _400_Bad_Request("Bad value for destination " .$this->form['destination']);
                }
            }
        }

        function checkRights() {
            error_log(__FUNCTION__);
        }

        function processRequest() {
            if( isset($this->source) && isset($this->destination)){
                $this->fkArray = Tarification::createFkArray($this->source, $this->destination);
            }
            
            if( isset($this->fkArray)){
                $this->tarification = $this->service->findOne($this->fkArray);
            }else{
                $this->tarification = $this->service->findAll();
            }
        }

        function processResponse() {
            if( isset($this->fkArray) ){
                if($this->tarification == null){
                    _404_Not_Found_("Tarification ") ;
                }else{
                    echo json_encode($this->tarification);
                }
            }else{
                echo json_encode($this->tarification);
            }
        }
    }
?>