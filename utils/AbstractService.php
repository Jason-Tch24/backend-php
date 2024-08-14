<?php

        require_once(ROOT . "/utils/IService.php");

        abstract class AbstractService implements IService {

            abstract function getDao() : IDao;

            function findAll() {
                return $this->getDao()->findAll();
            }

            function findOne($id) {
                return $this->getDao()->findOne($id);
            }
            
            function insertData($entity) {
                return $this->getDao()->insertData($entity);
            }

            function deleteById($id) {
                return $this->getDao()->deleteById($id);
            }

            function modifie($entity) {
                return $this->getDao()->modifie($entity);
            }
    
        }
?>