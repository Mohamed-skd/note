<?php
require_once "./ctrl.php";
?>

<!DOCTYPE html>
<html lang="fr">
<?php require_once CMPS . "head.php" ?>

<body data-page="<?= $page ?>">
  <?php require_once CMPS . "header.php" ?>

  <main>
    <section>
      <?php if ($usersCtrl->areUsers()) : ?>
      <article>
        <h3>Se connecter</h3>

        <form target="_self" method="post" class="form">
          <input maxlength="100" type="text" name="login" placeholder="Nom">
          <input maxlength="100" type="password" name="pwd" placeholder="Mot de passe">
          <button class="bt">Connexion</button>
        </form>
      </article>
      <?php endif ?>

      <article>
        <h3>S'inscrire</h3>

        <form target="_self" method="post" class="form">
          <input maxlength="100" type="text" name="signup" placeholder="Nom">
          <input maxlength="100" type="password" name="pwd" placeholder="Mot de passe">
          <button class="bt">Inscription</button>
        </form>
      </article>
    </section>
  </main>

  <?php require_once CMPS . "footer.php" ?>
</body>

</html>