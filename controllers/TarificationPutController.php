<?php
    require_once(ROOT . "/utils/IController.php");
    require_once(ROOT . "/utils/AbstractController.php");
    require_once(ROOT . "/utils/functions.php");

    require_once(ROOT . "/services/TarificationService.php");

    class TarificationPutController extends AbstractController implements IController {

        private TarificationService $service;

        public function __construct($form, $controllerName) {
            parent::__construct($form, $controllerName);
            $this->service = new TarificationService();
        }
        
        function checkForm() {
            if( !(isset($this->form['source']) && isset($this->form['destination']) && isset($this->form['tarif']) && isset($this->form['distance'])) ){
                _400_Bad_Request("Bad parameters");
            }
            $this->source = $this->form['source'];
            $this->destination = $this->form['destination'];
            $this->tarif = $this->form['tarif'];
            $this->distance = $this->form['distance'];
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
            if ( isDecimal( $this->tarif) ) {
                $this->tarif = floatval($this->form['tarif']);
            }else{
                _400_Bad_Request("Bad value for tarif " .$this->form['tarif']);
            }

            if ( isDecimal( $this->distance) ) {
                $this->distance = floatval($this->form['distance']);
            }else{
                _400_Bad_Request("Bad value for distance " .$this->form['distance']);
            }
        }

        function checkRights() {
            error_log(__FUNCTION__);
        }

        function processRequest() {
            $this->modiftarif = Tarification::create($this->source, $this->destination, $this->tarif, $this->distance);

            try{
                $this->modiftarif = $this->service->modifie($this->modiftarif);
            }catch(PDOException $e){
                if ($e->errorInfo[1] == 1452) {
                    headerAndDie("HTTP/1.1 480 GarePeage  dosn't existe ");
                }
                if ($e->errorInfo[1] == 1062) {
                    headerAndDie("HTTP/1.1 499 Tarif duplicated already existe ");
                }
                throw $e;
            }
        }

        function processResponse() {
            if(!$this->modiftarif){
                echo "Imposible de modifier ou la donnée est introuvable";
                _405_Metho_Not_Allowed(" impossible");
            }
            echo json_encode($this->modiftarif);
        }
    }
?>