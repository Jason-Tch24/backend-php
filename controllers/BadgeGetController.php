<?php
        require_once(ROOT . "/utils/IController.php");
        require_once(ROOT . "/utils/AbstractController.php");
        require_once(ROOT . "/utils/functions.php");

        require_once(ROOT . "/services/BadgeService.php");

        class BadgeGetController extends AbstractController implements IController {

            private BadgeService $service;

            public function __construct($form, $controllerName) {
                parent::__construct($form, $controllerName);
                $this->service = new BadgeService();
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
                    $this->badge = $this->service->findOne($this->id);
                }else{
                    $this->badge = $this->service->findAll();
                }
            }
            function processResponse() {
                if( isset($this->id) ){
                    if($this->badge == null){
                        _404_Not_Found_("Badge id = " . $this->id);
                    }else{
                        echo json_encode($this->badge);
                    }
                }else{
                echo json_encode($this->badge);
                }
            }

        }

?>