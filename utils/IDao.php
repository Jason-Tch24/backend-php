<?php

    interface IDao {
        function findAll();
		function findOne($id);
		function insertData($entity);
		function deleteById($id);
		function modifie($entity);
    }
?>