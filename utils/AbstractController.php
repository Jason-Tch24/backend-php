<?php

    require_once(ROOT . "/utils/IController.php");

    abstract class AbstractController implements IController {

        protected $form;
        protected $controllerName;

        public function __construct($form, $controllerName) {
            $this->form = $form;
            $this->controllerName = $controllerName;
        }

        function execute() {
            $this->checkForm();
            $this->checkCybersec();
            $this->checkRights();
            $this->processRequest();
            $this->processResponse();
        }


        abstract function checkForm();
        abstract function checkCybersec();
        abstract function checkRights();
        abstract function processRequest();
        abstract function processResponse();

    }


?>