<?php
    require_once(ROOT . "/utils/IController.php");
    require_once(ROOT . "/utils/AbstractController.php");
    require_once(ROOT . "/utils/functions.php");

    require_once(ROOT . "/services/TrajetService.php");

    class TrajetPutController extends AbstractController implements IController {

        private TrajetService $service;
        private int $id;
        private int $noPortique;
        private int $fkBadge;


        public function __construct($form, $controllerName) {
            parent::__construct($form, $controllerName);
            $this->service = new TrajetService();
        }
        
        function checkForm() {
            if(isset($this->form['id'])){
                $this->id = $this->form['id'];
            }else{
                _400_Bad_Request(" Bad syntaxe id");
            }

            if(isset($this->form['portique'])){
                $this->noPortique = $this->form['portique'];
            }else{
                _400_Bad_Request(" Bad syntaxe portique");
            }
            
            if(isset($this->form['badge'])){
                $this->fkBadge = $this->form['badge'];
            }else{
                _400_Bad_Request(" Bad syntaxe badge");
            }
        }

        function checkCybersec() {
            if (!is_int($this->id)){
                _400_Bad_Request(" Bad syntaxe id =" . $this->id);
            }else{
                $this->fkBadge = intval($this->fkBadge);
            }

            if (!is_int($this->noPortique)){
                _400_Bad_Request(" Bad syntaxe portique =" . $this->noPortique);
            }else{
                $this->noPortique = intval($this->noPortique);
            }

            if (!is_int($this->fkBadge)){
                _400_Bad_Request(" Bad syntaxe badge =" . $this->fkBadge);
            }else{
                $this->fkBadge = intval($this->fkBadge);
            }
        }

        function checkRights() {
            error_log(__FUNCTION__);
        }

        function processRequest() {
            $this->newSortie = Trajet::createSortie($this->id, $this->noPortique, $this->fkBadge);
            
            $this->newSortie = $this->service->modifie($this->newSortie);
        }

        function processResponse() {
            if(!$this->newSortie){
                echo "Imposible de Sortir pas cette portique ou avec ce badge";
                _404_Not_Found_(" Operation de sortie frauduleuse");
            }
            echo json_encode($this->newSortie);
        }
    }
?>