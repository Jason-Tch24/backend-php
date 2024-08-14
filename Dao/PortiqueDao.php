<?php

    require_once(ROOT . "/utils/IDao.php");
    require_once(ROOT . "/utils/AbstractDao.php");
    require_once(ROOT . "/utils/BddSingleton.php");
    
    require_once(ROOT . "/modele/Portique.php");

    class PortiqueDao extends AbstractDao implements IDao {

        function findAll() {
            $pdo = BddSingleton::getinstance()->getPdo();
            $sql = "SELECT autoroute.portique.id, autoroute.portique.isEntrer, autoroute.portique.noPortique, autoroute.garepeage.id AS fkGarePeage, autoroute.garepeage.nomPeage
            FROM autoroute.portique INNER JOIN autoroute.garepeage ON autoroute.portique.fkGarePeage = autoroute.garepeage.id";
            $query = $pdo->query($sql);
            $resultSet = $query->fetchAll(PDO::FETCH_OBJ);
            $portiques = array();
            foreach($resultSet as $row){
                $portique = Portique::createFromFk($row);
                array_push($portiques, $portique);
            }
            return $portiques;
        }

        function findOne($id) {
            $pdo = BddSingleton::getinstance()->getPdo();
            $stmt = $pdo->prepare( "SELECT autoroute.portique.id, autoroute.portique.isEntrer, autoroute.portique.noPortique, autoroute.garepeage.id AS fkGarePeage, autoroute.garepeage.nomPeage
            FROM autoroute.portique INNER JOIN autoroute.garepeage ON autoroute.portique.fkGarePeage = autoroute.garepeage.id WHERE autoroute.portique.id = ? ");
            $stmt->bindParam(1,$id);
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Portique");
            $stmt->execute();
            $portique = $stmt->fetch();
            return $portique ? $portique : null;
        }

        function insertData ($entity) {
            $pdo = BddSingleton::getinstance()->getPdo();
			$stmt = $pdo->prepare("INSERT INTO portique (isEntrer, noPortique, fkGarePeage) VALUES (?, ?, ?)");
			$entrer = $entity->getisEntrer();
			$portique = $entity->getnoPortique();
			$gare = $entity->getfkGarePeage();
			$stmt->bindParam(1, $entrer);
			$stmt->bindParam(2, $portique);
			$stmt->bindParam(3, $gare);
			$stmt->execute();
			$entity->setId($pdo->lastInsertId());
			return $entity;
        }
        function deleteById ($id) {
            $pdo = BddSingleton::getInstance()->getPdo();
            $stmt = $pdo->prepare("DELETE FROM portique WHERE id = ?");
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
        function modifie ($entity) {
            $pdo = BddSingleton::getinstance()->getPdo();
            $stmt = $pdo->prepare("UPDATE portique SET isentrer = ?,  noportique = ?, fkgarepeage = ? WHERE id = ?");
            $id = $entity->getId();
            $isEntrer = $entity->getisEntrer();
			$noPortique = $entity->getnoPortique();
            $fkgarepeage = $entity->getfkGarePeage();
            $stmt->bindParam(1, $isEntrer);
			$stmt->bindParam(2, $noPortique);
			$stmt->bindParam(3, $fkgarepeage);
			$stmt->bindParam(4, $id);
            
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