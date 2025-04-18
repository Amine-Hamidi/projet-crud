<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
    .main{
      width: 60%;
      margin: 20px auto;
    }
    table {
      border-collapse: collapse;
      font-family: Arial, sans-serif;
    }

    th, td {
      border: 1px solid #ccc;
      padding: 10px;
      text-align: center;
    }

    th {
      background-color: #f2f2f2;
    }

    tr:hover {
      background-color: #f9f9f9;
    }

    .btn-edit {
      background-color: #4CAF50; /* vert */
    }

    .btn-delete {
      background-color: #f44336; /* rouge */
    }
  </style>

</head>
<body class="main">
  <h1>Liste des Livres</h1>


  <table>
    <thead>
      <tr>
        <th>id</th>
        <th>titre</th>
        <th>auteur</th>
        <th>categorie</th>
        <th>stock</th>
        <th>action</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($livres as $livre): ?>
          <tr>
            <td> <?php echo htmlspecialchars($livre->id); ?></td>
            <td> <?php echo htmlspecialchars($livre->titre); ?> </td>
            <td> <?php echo htmlspecialchars($livre->auteur); ?> </td>
            <td> <?php echo htmlspecialchars($livre->categorie); ?> </td>
            <td> <?php echo htmlspecialchars($livre->stock); ?> </td>
            <td>
              <a href="../service/gestionLivre.php?id=<?php echo $livre->id ?>&type=edit" class="btn btn-edit">Modifier</a>
              <a href="../service/gestionLivre.php?id=<?php echo $livre->id ?>&type=delete" class="btn btn-delete">Supprimer</a>
            </td>

      </tr>
      <?php endforeach; ?>
    </tbody>

  </table>



  </body>
</html>