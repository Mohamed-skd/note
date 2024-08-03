<?php
require_once "./ctrl.php";
?>

<!DOCTYPE html>
<html lang="fr">
<?php require_once CMPS . "head.php" ?>

<body data-page="<?= $page ?>">
  <?php require_once CMPS . "header.php" ?>
  <?php require_once CMPS . "nav.php" ?>

  <main>
    <section>
      <form method="post" target="_self" class="form">
        <textarea name="note" cols="18" rows="6" maxlength="1000" placeholder="Nouvelle note ..."></textarea>

        <input list="cat" placeholder="Catégorie">
        <datalist id="cat">
          <?php foreach ($cats as &$cat) : ?>
          <option value="<?= $cat ?>"><?= $cat ?></option>
          <?php endforeach ?>
        </datalist>

        <button class="bt">Ajouter</button>
      </form>

      <div id="notes">
        <nav>
          <input type="search" name="search" placeholder="Chercher ...">

          <button id="tog-cats" title="Catégories"></button>
          <div>
            <ul>
              <?php foreach ($cats as &$cat) : ?>
              <li>
                <a href="?cat=<?= $cat ?>" class="link"><?= $cat ?></a>
              </li>
              <?php endforeach ?>
            </ul>
          </div>
        </nav>

        <div class="grid">
          <?php foreach ($notes as &$note) : ?>
          <article>
            <input type="checkbox" name="select">
            <p class="cat"><?= $note->cat ?></p>
            <p class="content"><?= $note->content ?></p>

            <aside class="flex">
              <button class="bt edit">Modifier</button>
              <button class="bt delete">Supprimer</button>
            </aside>
          </article>
          <?php endforeach ?>
        </div>
      </div>
    </section>
  </main>

  <?php require_once CMPS . "footer.php" ?>
</body>

</html>