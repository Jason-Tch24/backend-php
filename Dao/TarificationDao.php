<?php

    require_once(ROOT . "/utils/IDao.php");
    require_once(ROOT . "/utils/AbstractDao.php");
    require_once(ROOT . "/utils/BddSingleton.php");

    require_once(ROOT . "/modele/Tarification.php");
    class TarificationDao extends AbstractDao implements IDao {
        function findAll() {
            $pdo = BddSingleton::getinstance()->getPdo();
            $sql = "SELECT 
                        gs.nomPeage as nomPeageSource,
                        gd.nomPeage as nomPeageDestination,
                        tar.tarif,
                        tar.distance
                    FROM autoroute.tarification tar 
                    INNER JOIN autoroute.garepeage gs ON tar.fkGarePeageSource = gs.id
                    INNER JOIN autoroute.garepeage gd ON tar.fkGarePeageDestination = gd.id";
            $query = $pdo->query($sql);
            $resultSet = $query->fetchAll(PDO::FETCH_OBJ);
            $tarifications = array();
            foreach($resultSet as $row){
                $tarification = Tarification::createFromName($row);
                array_push($tarifications, $tarification);
            }
            return $tarifications;
        }

        function findOne($id) {
            $pdo = BddSingleton::getinstance()->getPdo();
            $stmt = $pdo->prepare( "SELECT 
                                gs.nomPeage as nomPeageSource,
                                gd.nomPeage as nomPeageDestination,
                                tar.tarif,
                                tar.distance
                            FROM autoroute.tarification tar 
                            INNER JOIN autoroute.garepeage gs ON tar.fkGarePeageSource = gs.id
                            INNER JOIN autoroute.garepeage gd ON tar.fkGarePeageDestination = gd.id
                            WHERE 
                                tar.fkGarePeageSource = ? AND tar.fkGarePeageDestination = ? ");
            $source = $id->getFkGarePeageSource();
            $destination = $id->getFkGarePeageDestination();
            $stmt->bindParam(1,$source);
            $stmt->bindParam(2,$destination);
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Tarification");
            $stmt->execute();
            $tarification = $stmt->fetch();
            return $tarification ? $tarification : null;
        }

        function insertData ($entity) {
            $pdo = BddSingleton::getinstance()->getPdo();
            $fkgarepeagesource = $entity->getFkGarePeageSource();
			$fkgarepeagedestination = $entity->getFkGarePeageDestination();
			$tarif = $entity->getTarif();
			$distance = $entity->getDistance();
            if( $fkgarepeagesource == $fkgarepeagedestination ){
                return "impossible";
            }
			$stmt = $pdo->prepare("INSERT INTO tarification (fkgarepeagesource, fkgarepeagedestination, tarif, distance) VALUES (?, ?, ?, ?)");
			$stmt->bindParam(1, $fkgarepeagesource);
			$stmt->bindParam(2, $fkgarepeagedestination);
			$stmt->bindParam(3, $tarif);
			$stmt->bindParam(4, $distance);
			$stmt->execute();
			$entity->setFkGarePeageSource($pdo->lastInsertId());
			return $entity;
        }

        function deleteById ($id) {
            $pdo = BddSingleton::getInstance()->getPdo();
            $stmt = $pdo->prepare("DELETE FROM tarification WHERE fkgarepeagesource = ? AND fkgarepeagedestination = ? ");
            $source = $id->getFkGarePeageSource();
            $destination = $id->getFkGarePeageDestination();
            $stmt->bindParam(1,$source);
            $stmt->bindParam(2,$destination);
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
            $stmt = $pdo->prepare("UPDATE tarification SET tarif = ?,  distance = ? WHERE fkgarepeagesource = ? AND fkgarepeagedestination = ? ");
            $fkgarepeagesource = $entity->getFkGarePeageSource();
			$fkgarepeagedestination = $entity->getFkGarePeageDestination();
			$tarif = $entity->getTarif();
			$distance = $entity->getDistance();
            $stmt->bindParam(1, $tarif);
			$stmt->bindParam(2, $distance);
            $stmt->bindParam(3, $fkgarepeagesource);
			$stmt->bindParam(4, $fkgarepeagedestination);
			
            
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