<?php
    require_once(ROOT . "/utils/IController.php");
    require_once(ROOT . "/utils/AbstractController.php");
    require_once(ROOT . "/utils/functions.php");

    require_once(ROOT . "/services/PortiqueService.php");

    class PortiquePutController extends AbstractController implements IController {

        private PortiqueService $service;

        public function __construct($form, $controllerName) {
            parent::__construct($form, $controllerName);
            $this->service = new PortiqueService();
        }
        
        function checkForm() {
            if ( !(isset($this->form['id']) && isset($this->form['entrer']) && isset($this->form['noportique']) && isset($this->form['fkgarepeage'])) ){
                _400_Bad_Request("Bad syntaxe");
            }
            $this->id = $this->form['id'];
            $this->isEntrer = $this->form['entrer'];
            $this->noPortique = $this->form['noportique'];
            $this->fkGarePeage = $this->form['fkgarepeage'];
        }

        function checkCybersec() {
            if (!ctype_digit($this->id)){
                _400_Bad_Request(" Bad syntaxe entrer =" . $this->id);
            }
            if (!ctype_digit($this->isEntrer)){
                _400_Bad_Request(" Bad syntaxe entrer =" . $this->isEntrer);
            }
            if (!ctype_digit($this->noPortique)){
                _400_Bad_Request(" Bad syntaxe portique =" . $this->noPortique);
            }
            if (!ctype_digit($this->fkGarePeage)){
                _400_Bad_Request(" Bad syntaxe gare =" . $this->fkGarePeage);
            }
            $this->id = intval($this->id);
            $this->isEntrer = intval($this->isEntrer);
            $this->noPortique = intval($this->noPortique);
            $this->fkGarePeage =intval($this->fkGarePeage);

            if ( !($this->isEntrer === 1 || $this->isEntrer === 0)){
                _400_Bad_Request(" Bad syntaxe entre soit 0 ou 1 ");
            }
        }

        function checkRights() {
            error_log(__FUNCTION__);
        }

        function processRequest() {
            $this->modifieportique = Portique::createWithId($this->id, $this->isEntrer, $this->noPortique, $this->fkGarePeage);
            
            $this->modifieportique = $this->service->modifie($this->modifieportique);
        }

        function processResponse() {
            if(!$this->modifieportique){
                echo "Imposible de modifier ou la donnée est introuvable";
                _405_Metho_Not_Allowed(" impossible");
            }
            echo json_encode($this->modifieportique);
        }
    }
?>