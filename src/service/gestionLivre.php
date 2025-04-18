<?php
    $host='localhost';
    $dbname='bibliotheque';
    $user='root';
    $pass='';

    $livres = [];
    $auteurs=[];
    $livreToUpdate = null;

    $isDesabled = false;

   
    try{
        $pdo= new PDO("mysql:host=$host;dbname=$dbname;charset=utf8",$user,$pass);       
        $livres = findAll($pdo);
        $auteurs= fundAllAuteur($pdo);


        if (isset($_GET['id']) && isset($_GET['type'])) {
            $id = intval($_GET['id']);
            $type = $_GET['type'];
            if ($type == "delete") {
                deleteById($pdo, $id);
                header("Location: gestionLivre.php"); // évite la suppression multiple
                exit;
            }else if($type=="edit"){
                $livreToUpdate = findById($pdo, $id);


            } else {
                echo "Action inconnue !";
            }
            
        }


//         // Si le formulaire est soumis (via POST)
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['edit'])) {
                    $id        = intval($_POST['id']);
                    $titre     = $_POST['titre'];
                    $auteur    = $_POST['auteur'];
                    $categorie = $_POST['categorie'];
                    $stock     = $_POST['stock'];
    
                    $livreUpdated = new Livre($id, $titre, $auteur, $categorie, $stock);
                    update($pdo, $id, $livreUpdated);
                    header("Location: gestionLivre.php"); // évite le double update
                    exit;
                }

                if (isset($_POST['add'])) {
                    $titre     = $_POST['titre'];
                    $auteur    = $_POST['auteur'];
                    $categorie = $_POST['categorie'];
                    $stock     = $_POST['stock'];
    
                    $livreUpdated = new Livre(0, $titre, $auteur, $categorie, $stock);
                    createLivre($pdo, $livreUpdated);
                    header("Location: gestionLivre.php"); // évite le double update
                    exit;
                }
              
            }
        





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


    function findById($pdo, $id) {
        $sql = "SELECT livres.id, livres.titre, livres.categorie, livres.stock, auteur.nom 
                    FROM livres Join auteur on auteur.id=livres.fk_auteur WHERE livres.id = :id ";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $ligne = $stmt->fetch();
        if ($ligne) {
            return new Livre($ligne['id'], $ligne['titre'], $ligne['nom'], $ligne['categorie'], $ligne['stock']);
        }
        return null;
    }

    function findAll($pdo){
        
        $livres = [];

        $sql="SELECT livres.id, livres.titre, livres.categorie, livres.stock, auteur.nom 
                    FROM livres Join auteur on auteur.id=livres.fk_auteur";
        $stmt=$pdo->query($sql);
        while($ligne = $stmt->fetch()){
            $livres []= new Livre($ligne['id'], $ligne['titre'], $ligne['nom'], $ligne['categorie'], $ligne['stock']);
            
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
        echo "Livre avec ID $id supprimé !";
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
    function update(PDO $pdo,int $id, Livre $livre){
        updateLivre($pdo, $id, $livre);
        updateAuteur($pdo, $id, $livre);
    }
    
    function updateLivre(PDO $pdo,int $id, Livre $livre){
        
        $sql="UPDATE livres SET titre = :titre, auteur = :auteur , categorie = :categorie, stock = :stock, WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        
        return $stmt->execute([
            'titre' => $livre->titre,
            'auteur' => $livre->auteur,
            'categorie' => $livre->categorie,
            'stock' => $livre->stock,
            'id' => $id
        ]);
        echo "Edition du livre avec ID $id";
    }

    function fundAllAuteur(PDO $pdo){
        $auteur=[];
        $sql="SELECT * from auteur ";
        $stmt=$pdo -> prepare($sql);
        $stmt=$pdo->query($sql);
        while($ligne = $stmt->fetch()){
            $auteur []= new Auteur($ligne['id'], $ligne['nom']);
            
        }
        return $auteur;
    }
    class Auteur{
       public $id;
       public $nom;

       public function __construct($id, $nom){
        $this->id=$id;
        $this->nom=$nom;
       }
    }
    function updateAuteur(PDO $pdo,int $id, Livre $livre){
        
        $sql="UPDATE auteur SET nom = :nom WHERE id = (SELECT livres.fk_auteur from livres where livres.id = :id)";
        $stmt = $pdo->prepare($sql);
        
        return $stmt->execute([
            'nom' => $livre->auteur,
            'id' => $id
        ]);
        echo "Edition du livre avec ID $id";
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
    include '../view/updateLivre.php';
?>  