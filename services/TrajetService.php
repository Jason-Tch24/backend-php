<?php

    require_once(ROOT . "/utils/IService.php");
    require_once(ROOT . "/utils/AbstractService.php");
    require_once(ROOT . "/utils/IDao.php");
    require_once(ROOT . "/Dao/TrajetDao.php");
    
    class TrajetService extends AbstractService implements IService {
        private TrajetDao $dao;

        function __construct() {
            $this->dao = new TrajetDao();
        }

        function getDao() : IDao {
            return $this->dao;
        }
    }
?>