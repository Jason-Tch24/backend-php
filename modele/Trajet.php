<?php    

    class Trajet implements JsonSerializable {

        private int $id;
        private string $dateEntrer;
        private string $dateSortie;
        private int $fkPortiqueEntrer;
        private int $fkPortiqueSortie;
        private int $fkBadge;
        private string $nomPeage;
        private string $nomPeageEntrer;
        private string $nomPeageSortie;
        private int $isEntrer;
        private int $noPortique;
        private int $noPortiqueEntrer;
        private int $noPortiqueSortie;
        private string $nom;
        
        function __construct() {}

        public function setId(int $_id){
            $this->id = $_id;
        }
        
        public function setdateEntrer(string $_dateEntrer){
            $this->dateEntrer = $_dateEntrer;
        }
        
        public function setdateSortie(string $_dateSortie = null){
            $this->dateSortie = $_dateSortie;
        }

        public function setfkPortiqueEntrer(int $_fkPortiqueEntrer){
            $this->fkPortiqueEntrer = $_fkPortiqueEntrer ;
        }

        public function setfkPortiqueSortie(int $_fkPortiqueSortie){
            $this->fkPortiqueSortie = $_fkPortiqueSortie ;
        }

        public function setfkBadge(int $_fkBadge){
            $this->fkBadge = $_fkBadge ;
        }

        public function getId() : int{
            return $this->id;
        }

        public function getdateEntrer() : string{
            return $this->dateEntrer;
        }

        public function getdateSortie() : string{
            return $this->dateSortie;
        }

        public function getfkPortiqueEntrer() : int{
            return $this->fkPortiqueEntrer;
        }

        public function getfkPortiqueSortie() : int{
            return $this->fkPortiqueSortie;
        }

        public function getfkBadge() : int{
            return $this->fkBadge;
        }

        public static function createFromArray($row) : Trajet {
            $trajet = new Trajet();
            $trajet->id = intval($row->id);
            $trajet->dateEntrer = $row->dateEntree;
            $trajet->dateSortie = $row->dateSortie == null ? 'pas sortie' : $row->dateSortie;
            $trajet->fkPortiqueEntrer = intval($row->fkPortiqueEntree);
            $trajet->fkPortiqueSortie = intval($row->fkPortiqueSortie);
            $trajet->fkBadge = intval($row->fkBadge);
            return $trajet;
        }

        public static function createFromFkEntrer($row) : Trajet {
            $trajet = new Trajet();
            $trajet->id = intval($row->id);
            $trajet->dateEntrer = $row->dateEntree;
            $trajet->dateSortie = $row->dateSortie == null ? 'pas sortie' : $row->dateSortie;
            $trajet->isEntrer = intval($row->isEntrer);
            $trajet->noPortiqueEntrer = intval($row->noPortique);
            $trajet->nomPeage = $row->nomPeage;
            $trajet->nom = $row->nom;
            return $trajet;
        }

        public static function createFromFkSortie($row) : Trajet {
            $trajet = new Trajet();
            $trajet->id = intval($row->id);
            $trajet->dateSortie = $row->dateSortie;
            $trajet->isEntrer = intval($row->isEntrer);
            $trajet->noPortiqueSortie = intval($row->noPortique);
            $trajet->nomPeage = $row->nomPeage;
            $trajet->nom = $row->nom;
            return $trajet;
        }

        public static function create( int $Portique, int $Badge) : Trajet {
            $newPortique = new Trajet();
            $newPortique->fkPortiqueEntrer = $Portique;
            $newPortique->fkBadge = $Badge;
            return $newPortique; 
        }

        public static function createSortie(int $id, int $Portique, int $Badge) : Trajet {
            $newPortique = new Trajet();
            $newPortique->id = $id;
            $newPortique->fkPortiqueSortie = $Portique;
            $newPortique->fkBadge = $Badge;
            return $newPortique; 
        }

        public function jsonSerialize(){
            $vars = get_object_vars($this);
            return $vars;
        }
    }

?>