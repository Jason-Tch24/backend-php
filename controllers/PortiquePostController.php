<?php
    require_once(ROOT . "/utils/IController.php");
    require_once(ROOT . "/utils/AbstractController.php");
    require_once(ROOT . "/utils/functions.php");

    require_once(ROOT . "/services/PortiqueService.php");

    class PortiquePostController extends AbstractController implements IController {

        private PortiqueService $service;

        public function __construct($form, $controllerName) {
            parent::__construct($form, $controllerName);
            $this->service = new PortiqueService();
        }
        
        function checkForm() {
            if ( !(isset($this->form['entrer']) && isset($this->form['noportique']) && isset($this->form['fkgarepeage'])) ){
                _400_Bad_Request("Bad syntaxe");
            }
            $this->isEntrer = $this->form['entrer'];
            $this->noPortique = $this->form['noportique'];
            $this->fkGarePeage = $this->form['fkgarepeage'];
        }

        function checkCybersec() {
            if (!ctype_digit($this->isEntrer)){
                _400_Bad_Request(" Bad syntaxe entrer =" . $this->isEntrer);
            }
            if (!ctype_digit($this->noPortique)){
                _400_Bad_Request(" Bad syntaxe portique =" . $this->noPortique);
            }
            if (!ctype_digit($this->fkGarePeage)){
                _400_Bad_Request(" Bad syntaxe gare =" . $this->fkGarePeage);
            }
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
            $this->newportique = Portique::create($this->isEntrer, $this->noPortique, $this->fkGarePeage);
            try{
                $this->newportique = $this->service->insertData($this->newportique);
            }catch(PDOException $e){
                if ($e->errorInfo[1] == 1452) {
                    headerAndDie("HTTP/1.1 481 Entity gare dosn't existe " . $this->fkGarePeage);
                }
                throw $e;
            }
        }

        function processResponse() {
            echo json_encode($this->newportique);
        }
    }
?>