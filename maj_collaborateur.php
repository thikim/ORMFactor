<?php
include_once './app/Database.php';

// $id = isset($_GET['id']) ? ($_GET['id']) : die('Erreur avec l\'ID RH Collaborateur');
$id = isset($_GET['id']) ? $_GET['id'] : die('Problème Collaborateur ID');

$database = new Database();
$db = $database->getConnection();

include_once './app/Collaborateur.php';
$collaborateur = new Collaborateur($db);

$collaborateur->id = $id;
$collaborateur->readCollaborateurID();
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mettre à jour collaborateur</title>
    <link href="./public/css/bootstrap.min.css" rel="stylesheet">
    <link href="./public/css/views.css" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">

      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

  </head>
  <body>
  <p><br/></p>
    <div class="container">
      <p>
	<a class="btn btn-primary" href="./pages/collaborateurs.php" role="button">Retour</a>
      </p><br/>

      <?php
      if($_SERVER["REQUEST_METHOD"]== "POST" && !empty($_POST)){

          $errors = [];

          $collaborateur->matriculerh = $_POST['matriculerh'];
          $collaborateur->nom = $_POST['nom'];
          $collaborateur->prenom = $_POST['prenom'];
          $collaborateur->adresse = $_POST['adresse'];
          $collaborateur->cp = $_POST['cp'];
          $collaborateur->ville = $_POST['ville'];
          $collaborateur->tel = $_POST['tel'];
          $collaborateur->categorie_pro = (string)$_POST['pro'];
          $collaborateur->avantage = $_POST['avantage_nature'];
          $collaborateur->date_entree = $_POST['date_entree'];
          $collaborateur->date_sortie = $_POST['date_sortie'];
          $collaborateur->commentaire = $_POST['commentaire'];

          //Gestion erreur formulaire
          $valid = true;

          if(empty($_POST['matriculerh']) || $_POST['matriculerh'] == ''){
            $errors['matriculerh'] = " Vous n'avez pas renseigné votre matricule";
            $valid = false;
          }else if (!preg_match("/^\d{0,4}$/", $collaborateur->matriculerh)) {
          	$errors['matriculerh'] = 'Entrez un matricule à 4 chiffres';
          }

          if(empty($_POST['nom']) || $_POST['nom'] == ''){
            $errors['nom'] = " Vous n'avez pas renseigné de nom";
            $valid = false;
          }else if (!preg_match("/^[a-zA-Z ]*$/",$collaborateur->nom)) {
              $errors['nom'] = "Nom du collaborateur incorrect";
          }

          if(empty($_POST['prenom']) || $_POST['prenom'] == ''){
            $errors['prenom'] = " Vous n'avez pas renseigné de prénom";
            $valid = false;
          }else if (!preg_match("/^[a-zA-Z ]*$/",$collaborateur->prenom)) {
            $errors['prenom'] = "Prénom du collaborateur incorrect";
          }

          if(empty($_POST['adresse']) || $_POST['adresse'] == ''){
            $errors['adresse'] = " Vous n'avez pas renseigné une adresse valide ";
            $valid = false;
          }else if (!preg_match('/^[a-z0-9 .\-]+$/i', $collaborateur->adresse)) {
          	$errors['adresse'] = "Adresse de résidence incorrect";
          }

          if(empty($_POST['cp']) || $_POST['cp'] == ''){
            $errors['cp'] = " Vous n'avez pas renseigné de code postal valide ";
            $valid = false;
          }else if (!preg_match("/^\d{0,5}$/", $collaborateur->cp)) {
          	$errors['cp'] = "Code postal incorrect";
          }

          if(empty($_POST['ville']) || $_POST['ville'] == ''){
            $errors['ville'] = " Vous n'avez pas renseigné de ville valide ";
            $valid = false;
          }

          if(empty($_POST['tel']) || $_POST['tel'] == ''){
            $errors['tel'] = " Vous n'avez pas renseigné de numéro valide ";
            $valid = false;
          }else if (!preg_match("#^0[1-68]([-. ]?[0-9]{2}){4}$#", $collaborateur->tel)){
          	$errors['tel'] = "Ce numéro de téléphone est invalide";
          }

          if(!preg_match('/^[0-9]*(\.[0-9]+)?$/', $collaborateur->avantage)) {
            $errors['avantage_nature'] = " Vous n'avez pas renseigné de montant valide ";
          }

          if(empty($_POST['date_entree']) || $_POST['date_entree'] == '') {
            $errors['date_entree'] = " Vous n'avez pas renseigné de date ";
            $valid = false;
          }else if(!preg_match("/^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}$/", $collaborateur->date_entree)){
            $errors['date_entree'] = " Vous avez renseigné une date invalide ";
          }

          if(empty($_POST['date_sortie']) || $_POST['date_sortie'] == '') {
            $errors['date_sortie'] = " Vous n'avez pas renseigné de date ";
            $valid = false;
          }else if(!preg_match("/^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}$/", $collaborateur->date_sortie)){
            $errors['date_sortie'] = " Vous avez renseigné une date invalide ";
          }

          if(!preg_match("/^[a-zA-Z ]*$/", $collaborateur->commentaire)) {
            $errors['commentaire'] = "Votre commentaire comporte des caractères interdits";
          }

          if($valid) {
          $collaborateur->updateCollaborateur();
              ?>
              <div class="alert alert-success alert-dismissible" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                          aria-hidden="true">&times;</span></button>
                  <strong>Mise à jour réussie !</strong>  <a href="./pages/collaborateurs.php">Voir les collaborateurs</a>.
              </div>
              <?php
          }
            else {
              ?>
                <div class="alert alert-danger alert-dismissible" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                         aria-hidden="true">&times;</span></button>
                 <strong>Erreur lors de la mise à jour!</strong>
              </div>
              <?php
            }
          }
      // ?>

<form method="post">

  <div class="form-group">
    <label for="idrh">Matricule RH</label>
    <input type="text" class="form-control" id="matriculerh" name="matriculerh" value='<?php echo $collaborateur->matriculerh; ?>'>
  </div>

  <div class="form-group">
    <label for="nom">Nom</label>
    <input type="text" class="form-control" id="nom" name="nom" value='<?php echo $collaborateur->nom; ?>'>
  </div>

  <div class="form-group">
    <label for="prenom">Prénom</label>
    <input type="text" class="form-control" id="prenom" name="prenom" value='<?php echo $collaborateur->prenom; ?>'>
  </div>

  <div class="form-group">
    <label for="adresse">Adresse</label>
    <input type="text" class="form-control" id="adresse" name="adresse" value='<?php echo $collaborateur->adresse; ?>'>
  </div>

  <div class="form-group">
    <label for="cp">Code postal</label>
    <input type="text" class="form-control" id="cp" name="cp" value='<?php echo $collaborateur->cp; ?>'>
  </div>

  <div class="form-group">
    <label for="ville">Ville</label>
    <input type="text" class="form-control" id="ville" name="ville" value='<?php echo $collaborateur->ville; ?>'>
  </div>

  <div class="form-group">
    <label for="tel">Téléphone</label>
    <input type="text" class="form-control" id="tel" name="tel" value='<?php echo $collaborateur->tel; ?>'>
  </div>

  <!-- <div class="form-group">
    <label for="prof">Profession Phardex</label>
    <select name="prof" id="prof" class="form-control">
      <option>AR</option>
      <option>ARS</option>
      <option>APM</option>
    </select>
  </div>

  <div class="form-group" style="position:relative;">
    <label for="justificatif">Justificatif conducteur</label>
    <a class='btn btn-primary' href='javascript:;'>
      Sélectionnez un fichier
      <input type="file" name="justif" style='position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;' name="file_source" size="50"  onchange='$("#upload-file-info").html($(this).val());'>
    </a>
    &nbsp;
    <span class='label label-info' id="upload-file-info"></span>
  </div> -->

  <?php
  $collaborateur->setCategoriePro();
  ?>

  <div class="form-group">
    <label for="avantage">Avantages naturels</label>
    <input type="text" class="form-control" id="avantage_nature" name="avantage_nature" value='<?php echo $collaborateur->avantage; ?>'>
  </div>

  <div class="form-group">
    <label for="entree">Date entrée</label>
    <input type="date" class="form-control" id="date_entree" name="date_entree" value='<?php echo $collaborateur->date_entree; ?>'>
  </div>

  <div class="form-group">
    <label for="sortie">Date sortie</label>
    <input type="date" class="form-control" id="date_sortie" name="date_sortie" value='<?php echo $collaborateur->date_sortie; ?>'>
  </div>

  <div class="form-group">
    <label for="commentaire">Commentaire</label>
    <textarea class="form-control" rows="3" id="commentaire" name="commentaire" value=''><?php echo $collaborateur->commentaire; ?></textarea>
  </div>
  <button type="submit" class="btn btn-success">Mettre à jour</button>
</form>
    </div>

    <script src="./public/js/jquery.min.js"></script>
    <script src="./public/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.js"></script>

    <script>
      if (!Modernizr.inputtypes.date) {
      $('input[type=date]').datepicker({
          // Consistent format with the HTML5 picker
          dateFormat: 'yy-mm-dd'
      });
  }
    </script>

  </body>
</html>
