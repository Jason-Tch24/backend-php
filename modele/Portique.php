<?php    

    class Portique implements JsonSerializable {

        private int $id;
        private int $isEntrer;
        private int $noPortique;
        private int $fkGarePeage;
        private string $nomPeage;
        
        function __construct() {}

        public function setId(int $_id){
            $this->id = $_id;
        }
        
        public function setisEntrer(int $_isEntrer){
            $this->isEntrer = $_isEntrer;
        }
        
        public function setnoPortique(int $_noPortique){
            $this->noPortique = $_noPortique;
        }

        public function setfkGarePeage(int $_fkGarePeage){
            $this->fkGarePeage = $_fkGarePeage ;
        }

        public function getId() : int{
            return $this->id;
        }

        public function getisEntrer() : int{
            return $this->isEntrer;
        }

        public function getnoPortique() : int{
            return $this->noPortique;
        }

        public function getfkGarePeage() : int{
            return $this->fkGarePeage;
        }

        public static function createFromArray($row) : Portique {
            $portique = new Portique();
            $portique->id = intval($row->id);
            $portique->isEntrer = intval($row->isEntrer);
            $portique->noPortique = intval($row->noPortique);
            $portique->fkGarePeage = intval($row->fkGarePeage);
            return $portique;
        }

        public static function createFromFk($row) : Portique {
            $portique = new Portique();
            $portique->id = intval($row->id);
            $portique->isEntrer = intval($row->isEntrer);
            $portique->noPortique = intval($row->noPortique);
            $portique->nomPeage = $row->nomPeage;
            return $portique;
        }

        public static function create(int $isEntrer, int $noPortique, int $fkGarePeage) : Portique {
            $newPortique = new Portique();
            $newPortique->isEntrer = $isEntrer;
            $newPortique->noPortique = $noPortique;
            $newPortique->fkGarePeage = $fkGarePeage;
            return $newPortique; 
        }

        public static function createWithId(int $id,int $isEntrer, int $noPortique, int $fkGarePeage) : Portique {
            $newPortique = new Portique();
            $newPortique->id = $id < 1 ? 0 : $id;
            $newPortique->isEntrer = $isEntrer;
            $newPortique->noPortique = $noPortique;
            $newPortique->fkGarePeage = $fkGarePeage;
            return $newPortique; 
        }

        public function jsonSerialize(){
            $vars = get_object_vars($this);
            return $vars;
        }
    }

?>