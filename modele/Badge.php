<?php    

    class Badge implements JsonSerializable {

        private int $id;
        private string $badge;
        private string $nom;
        
        function __construct() {}

        public function setId(int $_id){
            $this->id = $_id;
        }
        
        public function setBadge(string $_badge){
            $this->badge = $_badge;
        }
        
        public function setNom(string $_nom){
            $this->nom = $_nom;
        }

        public function getId() : int{
            return $this->id;
        }

        public function getBadge() : string{
            return $this->badge;
        }

        public function getNom() : string{
            return $this->nom;
        }

        public static function createFromArray($row) : Badge {
            $badge = new Badge();
            $badge->id = intval($row->id);
            $badge->badge = $row->badge;
            $badge->nom = $row->nom;
            return $badge;
        }

        public static function create(string $badge, string $nom) : Badge {
            $newBadge = new Badge();
            $newBadge->badge = $badge;
            $newBadge->nom = $nom;
            return $newBadge; 
        }

        public function jsonSerialize(){
            $vars = get_object_vars($this);
            return $vars;
        }
    }

?>