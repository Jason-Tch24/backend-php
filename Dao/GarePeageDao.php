<?php

    require_once(ROOT . "/utils/IDao.php");
    require_once(ROOT . "/utils/AbstractDao.php");
    require_once(ROOT . "/utils/BddSingleton.php");
    require_once(ROOT . "/modele/GarePeage.php");

    class GarePeageDao extends AbstractDao implements IDao {
        function findAll() {
            $pdo = BddSingleton::getinstance()->getPdo();
            $sql = "SELECT * FROM garepeage";
            $query = $pdo->query($sql);
            $resultSet = $query->fetchAll(PDO::FETCH_OBJ);
            $garepeages = array();
            foreach($resultSet as $row){
                $garepeage = GarePeage::createFromArray($row);
                array_push($garepeages, $garepeage);
            }
            return $garepeages;
        }

        function findOne($id){
            $pdo = BddSingleton::getinstance()->getPdo();
            $sql = "SELECT * FROM garepeage WHERE id =". $id ."";
            $stmt = $pdo->prepare( "SELECT * FROM garepeage WHERE id = ?");
            $stmt->bindParam(1,$id);
            $stmt->setFetchMode(PDO::FETCH_CLASS, "GarePeage");
            $stmt->execute();
            $garepeage = $stmt->fetch();
            return $garepeage ? $garepeage : null;
        }
        function insertData($entity) {
			$pdo = BddSingleton::getinstance()->getPdo();
			$stmt = $pdo->prepare("INSERT INTO garepeage (garepeage, nompeage) VALUES (?, ?)");
			$garePeage = $entity->getGarePeage();
			$nomPeage = $entity->getnomPeage();
			$stmt->bindParam(1, $garePeage);
			$stmt->bindParam(2, $nomPeage);
			$stmt->execute();
			$entity->setId($pdo->lastInsertId());
			return $entity;
		}

        function deleteById($id) {
            $pdo = BddSingleton::getInstance()->getPdo();
            $stmt = $pdo->prepare("DELETE FROM garepeage WHERE id = ?");
            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    return true; 
                } else {
                    return false; // Aucune ligne affectée
                }
            } else {
                return false; // Échec de la suppression
            }
        }
        

        function modifie($entity) {
            $pdo = BddSingleton::getinstance()->getPdo();
            $stmt = $pdo->prepare("UPDATE garepeage SET nompeage = ? WHERE garepeage = ?");
            $garePeage = $entity->getGarePeage();
			$nomPeage = $entity->getnomPeage();
            $stmt->bindParam(1, $nomPeage);
			$stmt->bindParam(2, $garePeage);
            
            if ($stmt->execute()) {
                // Vérifie si la mise à jour a affecté une ligne
                if ($stmt->rowCount() > 0) {
                    return true; // Mise à jour réussie
                } else {
                    return false; // Aucune ligne affectée, peut-être que le badge n'existe pas
                }
            } else {
                return false; // Échec de la mise à jour
            }
        }
    }
?>