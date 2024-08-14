<?php

    class Tarification implements JsonSerializable{
        private string $nomPeageSource;
        private string $nomPeageDestination;
        private int $fkGarePeageSource;
        private int $fkGarePeageDestination;
        private float $tarif;
        private float $distance;

        function __construct() {}

        public function setFkGarePeageSource(int $_fkGarePeageSource){
            $this->fkGarePeageSource = $_fkGarePeageSource;
        }

        public function setFkGarePeageDestination(int $_fkGarePeageDestination){
            $this->fkGarePeageDestination = $_fkGarePeageDestination;
        }

        public function setTarif(float $_tarif){
            $this->tarif = $_tarif;
        }

        public function setDistance(float $_distance){
            $this->distace = $_distance;
        }

        public function getFkGarePeageSource() : int{
            return $this->fkGarePeageSource;
        }

        public function getFkGarePeageDestination() : int{
            return $this->fkGarePeageDestination;
        }

        public function getTarif() : float{
            return $this->tarif;
        }

        public function getDistance() : float{
            return $this->distance;
        }

        public static function createFromArray($row) : Tarification {
            $tarification = new Tarification();
            $tarification->fkGarePeageSource = intval($row->fkGarePeageSource);
            $tarification->fkGarePeageDestination = intval($row->fkGarePeageDestination);
            $tarification->tarif = $row->tarif;
            $tarification->distance = $row->distance;
            return $tarification;
        }

        public static function createFromName($row) : Tarification {
            $tarification = new Tarification();
            $tarification->nomPeageSource = $row->nomPeageSource;
            $tarification->nomPeageDestination = $row->nomPeageDestination;
            $tarification->tarif = $row->tarif;
            $tarification->distance = $row->distance;
            return $tarification;
        }

        public static function create(int $fkGarePeageSource, int $fkGarePeageDestination, float $tarif, float $distance) : Tarification {
            $newTarification = new Tarification();
            $newTarification->fkGarePeageSource = $fkGarePeageSource;
            $newTarification->fkGarePeageDestination = $fkGarePeageDestination;
            $newTarification->tarif = $tarif;
            $newTarification->distance = $distance;
            return $newTarification; 
        }

        public static function createFkArray(int $fkGarePeageSource, int $fkGarePeageDestination) : Tarification {
            $newTarification = new Tarification();
            $newTarification->fkGarePeageSource = $fkGarePeageSource;
            $newTarification->fkGarePeageDestination = $fkGarePeageDestination;
            return $newTarification; 
        }

        public function jsonSerialize(){
            $vars = get_object_vars($this);
            return $vars;
        }
    }

?>