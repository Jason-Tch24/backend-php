<?php

        require_once(ROOT . "/utils/IService.php");
        require_once(ROOT . "/utils/AbstractService.php");
        require_once(ROOT . "/utils/IDao.php");
        require_once(ROOT . "/Dao/GarePeageDao.php");
        
        class GarePeageService extends AbstractService implements IService {
                private GarePeageDao $dao;

                function __construct() {
                        $this->dao = new GarePeageDao();
                }

                function getDao() : IDao {
                        return $this->dao;
                }
        }


?>