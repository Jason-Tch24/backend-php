<?php    

    class GarePeage implements JsonSerializable {

        private int $id;
        private string $GarePeage;
        private string $nomPeage;
        
        function __construct() {}

        public function setId(int $_id){
            $this->id = $_id;
        }
        
        public function setGarePeage(string $_GarePeage){
            $this->badge = $_GarePeage;
        }
        
        public function setnomPeage(string $_nomPeage){
            $this->nom = $_nomPeage;
        }

        public function getId() : int{
            return $this->id;
        }

        public function getGarePeage() : string{
            return $this->GarePeage;
        }

        public function getnomPeage() : string{
            return $this->nomPeage;
        }

        public static function createFromArray($row) : GarePeage {
            $GarePeage = new GarePeage();
            $GarePeage->id = intval($row->id);
            $GarePeage->GarePeage = $row->GarePeage;
            $GarePeage->nom = $row->nomPeage;
            return $GarePeage;
        }

        public static function create(string $GarePeage, string $nomPeage) : GarePeage {
            $newGarePeage = new GarePeage();
            $newGarePeage->GarePeage = $GarePeage;
            $newGarePeage->nomPeage = $nomPeage;
            return $newGarePeage; 
        }

        public function jsonSerialize(){
            $vars = get_object_vars($this);
            return $vars;
        }
    }

?>