<?php

    require_once(ROOT . "/utils/IService.php");
    require_once(ROOT . "/utils/AbstractService.php");
    require_once(ROOT . "/utils/IDao.php");
    require_once(ROOT . "/Dao/TarificationDao.php");
    
    class TarificationService extends AbstractService implements IService {
        private TarificationDao $dao;

        function __construct() {
            $this->dao = new TarificationDao();
        }

        function getDao() : IDao {
            return $this->dao;
        }
    }
?>