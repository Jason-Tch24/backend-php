<?php
    require_once(ROOT . "/utils/IController.php");
    require_once(ROOT . "/utils/AbstractController.php");
    require_once(ROOT . "/utils/functions.php");

    require_once(ROOT . "/services/TarificationService.php");

    class TarificationDeleteController extends AbstractController implements IController {

        private TarificationService $service;

        public function __construct($form, $controllerName) {
            parent::__construct($form, $controllerName);
            $this->service = new TarificationService();
        }
        
        function checkForm() {
            if(isset($this->form['source']) && isset($this->form['destination']) ){
                $this->source = $this->form['source'];
                $this->destination = $this->form['destination'];
            }else{
                _400_Bad_Request("Source and destination must be set ");
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
            
            try{
                $this->tarification = $this->service->deleteById($this->fkArray);
            }catch(PDOException $e){
                if ($e->errorInfo[1] == 1451) {
                    headerAndDie("HTTP/1.1 480 Can't delete dependencie error");
                }
                throw $e; 
            }
        }

        function processResponse() {
            if(!$this->tarification){
                echo "Probleme lors de la suppression";
                _405_Metho_Not_Allowed(" Permission probleme ");
            }else{
                echo "Operation de suppresion effectuer avec succes ";
            }
            echo json_encode($this->tarification);
        }
    }
?>