<?php
    require_once(ROOT . "/utils/IController.php");
    require_once(ROOT . "/utils/AbstractController.php");
    require_once(ROOT . "/utils/functions.php");

    require_once(ROOT . "/services/TrajetService.php");

    class TrajetDeleteController extends AbstractController implements IController {

        private TrajetService $service;

        public function __construct($form, $controllerName) {
            parent::__construct($form, $controllerName);
            $this->service = new TrajetService();
        }
        
        function checkForm() {
            if( isset($this->form['id'])){
                $this->id = $this->form['id'];
            }else{
                _400_Bad_Request(" Id most be set ");
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
            try{
                $this->trajet = $this->service->deleteById($this->id);
            }catch(PDOException $e){
                echo $e;
                if ($e->errorInfo[1] == 1451) {
                    headerAndDie("HTTP/1.1 480 Can't delete dependencie error");
                }
                throw $e;
            }
        }

        function processResponse() {
            if(!$this->trajet){
                echo "trajet id = ". $this->id ." est deja supprimer ou l'operation est impossible";
                _405_Metho_Not_Allowed(" Badge id = " . $this->id);
            }else{
                echo "Operation de suppresion effectuer avec succes ";
            }
            echo json_encode($this->trajet);
        }
    }
?>