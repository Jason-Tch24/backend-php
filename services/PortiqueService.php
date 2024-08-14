<?php

        require_once(ROOT . "/utils/IService.php");
        require_once(ROOT . "/utils/AbstractService.php");
        require_once(ROOT . "/utils/IDao.php");
        require_once(ROOT . "/Dao/PortiqueDao.php");
        
        class PortiqueService extends AbstractService implements IService {
            private PortiqueDao $dao;

            function __construct() {
                $this->dao = new PortiqueDao();
            }

            function getDao() : IDao {
                return $this->dao;
            }
        }
?>