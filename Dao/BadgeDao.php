<?php

    require_once(ROOT . "/utils/IDao.php");
    require_once(ROOT . "/utils/AbstractDao.php");
    require_once(ROOT . "/utils/BddSingleton.php");
    
    require_once(ROOT . "/modele/Badge.php");

    class BadgeDao extends AbstractDao implements IDao {
        function findAll() {
            $pdo = BddSingleton::getinstance()->getPdo();
            $sql = "SELECT * FROM badge";
            $query = $pdo->query($sql);
            $resultSet = $query->fetchAll(PDO::FETCH_OBJ);
            $badges = array();
            foreach($resultSet as $row){
                $badge = Badge::createFromArray($row);
                array_push($badges, $badge);
            }
            return $badges;
        }

        function findOne($id){
            $pdo = BddSingleton::getinstance()->getPdo();
            $stmt = $pdo->prepare( "SELECT * FROM badge WHERE id = ?");
            $stmt->bindParam(1,$id);
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Badge");
            $stmt->execute();
            $badge = $stmt->fetch();
            return $badge ? $badge : null;
        }
        function insertData($entity) {
			$pdo = BddSingleton::getinstance()->getPdo();
			$stmt = $pdo->prepare("INSERT INTO Badge (badge, nom) VALUES (?, ?)");
			$badge = $entity->getBadge();
			$nom = $entity->getNom();
			$stmt->bindParam(1, $badge);
			$stmt->bindParam(2, $nom);
			$stmt->execute();
			$entity->setId($pdo->lastInsertId());  
			return $entity;
		}

        function deleteById($id) {
            $pdo = BddSingleton::getInstance()->getPdo();
            $stmt = $pdo->prepare("DELETE FROM badge WHERE id = ?");
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
            $pdo = BddSingleton::getInstance()->getPdo();
            $stmt = $pdo->prepare("UPDATE badge SET nom = ? WHERE badge = ?");
            $badge = $entity->getBadge();
			$nom = $entity->getNom();
            $stmt->bindParam(1, $nom);
			$stmt->bindParam(2, $badge);
            
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