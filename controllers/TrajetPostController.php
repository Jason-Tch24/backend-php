<?php
    require_once(ROOT . "/utils/IController.php");
    require_once(ROOT . "/utils/AbstractController.php");
    require_once(ROOT . "/utils/functions.php");

    require_once(ROOT . "/services/TrajetService.php");

    class TrajetPostController extends AbstractController implements IController {

        private TrajetService $service;

        public function __construct($form, $controllerName) {
            parent::__construct($form, $controllerName);
            $this->service = new TrajetService();
        }
        
        function checkForm() {
            if ( !(isset($this->form['portique']) && isset($this->form['badge'])) ){
                _400_Bad_Request("Bad syntaxe");
            }
            $this->noPortique = $this->form['portique'];
            $this->fkBadge = $this->form['badge'];
        }

        function checkCybersec() {
            if (!ctype_digit($this->noPortique)){
                _400_Bad_Request(" Bad syntaxe portique =" . $this->noPortique);
            }
            if (!ctype_digit($this->fkBadge)){
                _400_Bad_Request(" Bad syntaxe badge =" . $this->fkBadge);
            }
            
            $this->noPortique = intval($this->noPortique);
            $this->fkBadge =intval($this->fkBadge);
        }

        function checkRights() {
            error_log(__FUNCTION__);
        }

        function processRequest() {
            $this->newEntrer = Trajet::create($this->noPortique, $this->fkBadge);
            try{
                $this->newEntrer = $this->service->insertData($this->newEntrer);
            }catch(PDOException $e){
                if ($e->errorInfo[1] == 1452) {
                    headerAndDie("HTTP/1.1 499 Entity gare dosn't existe " . $this->fkGarePeage);
                }
                echo "oh une erreur";
                throw $e;
            }
        }

        function processResponse() {
            echo json_encode($this->newEntrer);
        }
    }
?>