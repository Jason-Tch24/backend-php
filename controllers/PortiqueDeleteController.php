<?php
        require_once(ROOT . "/utils/IController.php");
        require_once(ROOT . "/utils/AbstractController.php");
        require_once(ROOT . "/utils/functions.php");

        require_once(ROOT . "/services/PortiqueService.php");

        class PortiqueDeleteController extends AbstractController implements IController {

            private PortiqueService $service;

            public function __construct($form, $controllerName) {
                parent::__construct($form, $controllerName);
                $this->service = new PortiqueService();
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
                    if(isset($this->id)){
                        $this->portique = $this->service->deleteById($this->id);
                    }else{
                        _400_Bad_Request("Bad value id not set");
                    }
                }catch(PDOException $e){
                    if ($e->errorInfo[1] == 1451) {
                        headerAndDie("HTTP/1.1 480 Can't delete dependencie error");
                    }
                    throw $e; 
                }
                
            }

            function processResponse() {
                if(!$this->portique){
                    echo "id = ". $this->id ." est deja supprimer ou l'operation est impossible";
                    _405_Metho_Not_Allowed("Portique id = " . $this->id);
                }else{
                    echo "Operation de suppresion effectuer avec succes ";
                }
                echo json_encode($this->portique);
            }
        }

?>