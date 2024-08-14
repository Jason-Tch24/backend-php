<?php

    require_once(ROOT . "/utils/IDao.php");
    require_once(ROOT . "/utils/AbstractDao.php");
    require_once(ROOT . "/utils/BddSingleton.php");

    require_once(ROOT . "/modele/Trajet.php");
    class TrajetDao extends AbstractDao implements IDao {
        function findAll() {
            $pdo = BddSingleton::getinstance()->getPdo();
            $sql = "SELECT 
                        t.id,
                        t.dateEntree,
                        t.dateSortie,
                        pe.noPortique,
                        pe.isEntrer,
                        g.nomPeage,
                        b.nom
                    FROM 
                        autoroute.trajet t
                    INNER JOIN 
                        autoroute.portique pe ON t.fkPortiqueEntree = pe.id
                    INNER JOIN 
                        autoroute.garepeage g ON pe.fkGarePeage = g.id
                    INNER JOIN 
                        autoroute.badge b ON t.fkBadge = b.id";
            $query = $pdo->query($sql);
            $resultSet = $query->fetchAll(PDO::FETCH_OBJ);
            $trajetsEntrer = array();
            foreach($resultSet as $row){
                $trajet = Trajet::createFromFkEntrer($row);
                array_push($trajetsEntrer, $trajet);
            }
            $sql = "SELECT 
                        t.id,
                        t.dateSortie,
                        ps.noPortique,
                        ps.isEntrer,
                        g.nomPeage,
                        b.nom
                    FROM 
                        autoroute.trajet t
                    INNER JOIN 
                        autoroute.portique ps ON t.fkPortiqueSortie = ps.id
                    INNER JOIN 
                        autoroute.garepeage g ON ps.fkGarePeage = g.id
                    INNER JOIN 
                        autoroute.badge b ON t.fkBadge = b.id";
            $query = $pdo->query($sql);
            $resultSet = $query->fetchAll(PDO::FETCH_OBJ);
            $trajetsSortie = array();
            foreach($resultSet as $row){
                $trajet = Trajet::createFromFkSortie($row);
                array_push($trajetsSortie, $trajet);
            }
            return [$trajetsEntrer,$trajetsSortie];
        }

        function findOne($id) {
            $pdo = BddSingleton::getinstance()->getPdo();
            $stmt = $pdo->prepare( "SELECT 
                        t.id,
                        t.dateEntree,
                        t.dateSortie,
                        pe.noPortique AS noPortiqueEntrer,
                        pe.isEntrer AS isEntrer_1,
                        g.nomPeage AS nomPeageEntrer,
                        pe.noPortique AS noPortiqueSortie,
                        pe.isEntrer AS isEntrer_2,
                        gs .nomPeage AS nomPeageSortie,
                        b.nom
                    FROM autoroute.trajet t
                    INNER JOIN autoroute.badge b ON t.fkBadge = b.id
                    INNER JOIN autoroute.portique pe ON t.fkPortiqueEntree = pe.id
                    INNER JOIN autoroute.portique ps ON t.fkPortiqueSortie = ps.id
                    INNER JOIN autoroute.garepeage g ON pe.fkGarePeage = g.id
                    INNER JOIN autoroute.garepeage gs ON ps.fkGarePeage = gs.id
                    WHERE  t.id = ? ");
            $stmt->bindParam(1,$id);
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Trajet");
            $stmt->execute();
            $trajet = $stmt->fetch();
            if($trajet == null){
                $stmt = $pdo->prepare( "SELECT 
                        t.id,
                        t.dateEntree,
                        pe.noPortique AS noPortiqueEntrer,
                        pe.isEntrer AS isEntrer_1,
                        g.nomPeage,
                        b.nom
                    FROM autoroute.trajet t
                    INNER JOIN autoroute.badge b ON t.fkBadge = b.id
                    INNER JOIN autoroute.portique pe ON t.fkPortiqueEntree = pe.id
                    INNER JOIN autoroute.garepeage g ON pe.fkGarePeage = g.id
                    WHERE  t.id = ? ");
                $stmt->bindParam(1,$id);
                $stmt->setFetchMode(PDO::FETCH_CLASS, "Trajet");
                $stmt->execute();
                $trajet = $stmt->fetch();
                return $trajet;
            }
            return $trajet ? $trajet : null;
        }

        function insertData ($entity) {
            $pdo = BddSingleton::getinstance()->getPdo();
			$stmt = $pdo->prepare("INSERT INTO autoroute.trajet (dateentree, fkportiqueentree, fkbadge) VALUES (CURRENT_TIMESTAMP, ?, ?)");
			$portique = $entity->getfkPortiqueEntrer();
			$badge = $entity->getfkBadge();
			$stmt->bindParam(1, $portique);
			$stmt->bindParam(2, $badge);
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
            $stmt = $pdo->prepare("
                            UPDATE autoroute.trajet 
                            SET 
                                dateSortie = CURRENT_TIMESTAMP, 
                                fkPortiqueSortie = :fkPortiqueSortie 
                            WHERE 
                                fkBadge = :fkBadge 
                                AND id = :id 
                                AND fkPortiqueEntree != :sortie
                        ");
            $id = $entity->getId();
			$noPortique = $entity->getfkPortiqueSortie();
            $badge = $entity->getfkBadge();
            $stmt->bindParam(':fkPortiqueSortie', $noPortique);
            $stmt->bindParam(':fkBadge', $badge);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':sortie', $noPortique);

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