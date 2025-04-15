<?php
    $host='localhost';
    $dbname='bibliotheque';
    $user='root';
    $pass='';

    $livres = [];
    try{
        $pdo= new PDO("mysql:host=$host;dbname=$dbname;charset=utf8",$user,$pass);       
        $livres = findAll($pdo);
        
        // foreach($livres as $livre){
        //     echo "Titre : " . $livre->titre . "<br>";
        //     echo "Auteur : " . $livre->auteur . "<br>";
        //     echo "Catégorie : " . $livre->categorie . "<br>";
        //     echo "Stock : " . $livre->stock . "<br><br>";
        // }
        

        // findByTitle($pdo,"Le Petit Prince");
       
       
        // $deleted = deleteById($pdo, 2);



        // if ($deleted) {
        //     echo "$deleted ligne(s) supprimée(s).";
        //     findAll($pdo);
        // } else {
        //     echo "Erreur lors de la suppression.";
        //     findAll($pdo);
        // }

        // $livreToCreate= new Livre("PHP" ,"Rasmus", "informatique", 5);
        // createLivre($pdo, $livreToCreate); 
        // findAll($pdo);

        // $livreUpdated= new Livre("PHP" ,"Rasmus Lerdorf", "informatique", 5);
        // updateLivre($pdo, 6, $livreUpdated );
        


       
    }catch (PDOException $e){
        echo "Erreur de connexion : ".$e->getMessage();
    }


    function findAll($pdo){
        
        $livres = [];

        $sql="SELECT * FROM livres";
        $stmt=$pdo->query($sql);
        while($ligne = $stmt->fetch()){
            $livres []= new Livre($ligne['id'], $ligne['titre'], $ligne['auteur'], $ligne['categorie'], $ligne['stock']);
            
        }
        return $livres;
    }
    function findByTitle($pdo, $title){
        $livres = [];
        $sql="SELECT * FROM livres WHERE titre='$title'";
        $stmt=$pdo->query($sql);
        
        while($ligne = $stmt->fetch()){
            $livres []= new Livre($ligne['id'], $ligne['titre'], $ligne['auteur'], $ligne['categorie'], $ligne['stock']);
        }
    }

    function deleteById($pdo, $id){
        $sql="DELETE FROM livres WHERE id= :id";
        $stmt = $pdo->prepare($sql);

        if ($stmt->execute(['id' => $id])) {
            return $stmt->rowCount(); // retourne le nombre de lignes supprimées
        } else {
            return false; // en cas d'erreur
        }
    }
    function createLivre(PDO $pdo, Livre $livre) {
        $sql = "INSERT INTO livres (titre, auteur, categorie, stock) VALUES (:titre, :auteur, :categorie, :stock)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            'titre' => $livre->titre,
            'auteur' => $livre->auteur,
            'categorie' => $livre->categorie,
            'stock' => $livre->stock,
        ]);
    }

    function updateLivre(PDO $pdo,int $id, Livre $livre){
        $sql="UPDATE livres SET titre = :titre, auteur = :auteur , categorie = :categorie, stock = :stock WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            'titre' => $livre->titre,
            'auteur' => $livre->auteur,
            'categorie' => $livre->categorie,
            'stock' => $livre->stock,
            'id' => $id
        ]);
    }

    class Livre {
        public $id;
        public $titre;
        public $auteur;
        public $categorie;
        public $stock;
        public function __construct($id, $titre, $auteur, $categorie, $stock) {
            $this->id = $id;
            $this->titre = $titre;
            $this->auteur = $auteur;
            $this->categorie=$categorie;
            $this->stock=$stock;
        }
    }


    include '../view/index.php';
?>