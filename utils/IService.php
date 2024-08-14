<?php

    require_once(ROOT . "/utils/IDao.php");

    interface IService {
        function findAll();
        function findOne($id);
        function getDao() : IDao;
        function deleteById($id);
        function insertData($entity);
        function modifie($entity);
    }
?>