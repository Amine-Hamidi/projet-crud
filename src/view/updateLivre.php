<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>update book</title>
</head>
<body>
<form action="../service/gestionLivre.php" method="post">
    <label for="id">ID :</label><br>
    <input type="text" id="id" name="id" 
           value="<?= isset($livreToUpdate) ? htmlspecialchars($livreToUpdate->id) : '' ?>" required readonly><br><br>

    <label for="titre">Titre :</label><br>
    <input type="text" id="titre" name="titre" 
           value="<?= isset($livreToUpdate) ? htmlspecialchars($livreToUpdate->titre) : '' ?>" required><br><br>

    <label for="auteur">Auteur :</label><br>      
    <select name="auteur" id="auteur">
    <option value="" disabled selected><?= isset($livreToUpdate) ? htmlspecialchars($livreToUpdate->auteur) : '' ?></option>
        <?php foreach ($auteurs as $auteur): ?>
            <option value=""><?= isset($auteur) ? htmlspecialchars($auteur->nom) : '' ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <!-- <label for="auteur">Auteur :</label><br>
    <input type="text" id="auteur" name="auteur" 
           value="" required><br><br> -->
            <!-- isset($livreToUpdate) ? htmlspecialchars($livreToUpdate->auteur) : ''   -->



    <label for="categorie">Catégorie :</label><br>
    <input type="text" id="categorie" name="categorie" 
           value="<?= isset($livreToUpdate) ? htmlspecialchars($livreToUpdate->categorie) : '' ?>" required><br><br>

    <label for="stock">Stock :</label><br>
    <input type="number" id="stock" name="stock" 
           value="<?= isset($livreToUpdate) ? htmlspecialchars($livreToUpdate->stock) : '' ?>" required><br><br>

    <input type="submit" name="edit" value="Mettre à jour">     <input type="submit" name="add" value="ajouter">

    
</form>
</body>
</html>