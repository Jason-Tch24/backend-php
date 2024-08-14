<?php

        require_once(ROOT . "/utils/IService.php");
        require_once(ROOT . "/utils/AbstractService.php");
        require_once(ROOT . "/utils/IDao.php");
        require_once(ROOT . "/Dao/BadgeDao.php");
        
        class BadgeService extends AbstractService implements IService {
                private BadgeDao $dao;

                function __construct() {
                        $this->dao = new BadgeDao();
                }

                function getDao() : IDao {
                        return $this->dao;
                }
        }
?>