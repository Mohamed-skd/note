<?php
$links = [
  "homepage" => $currentUser ?
    [PUBLIC_LINK . "notes", "Mes notes"] :
    [PUBLIC_LINK . "login", "Connexion"],
  "login" => [PUBLIC_LINK . "homepage", "Accueil"],
  "notes" => [PUBLIC_LINK . "homepage", "Accueil"],
];
?>

<nav id="nav-site">
  <ul class="flex">
    <?php if ($currentUser) : ?>
    <li>
      <a target="_self" href="<?= $links[$page][0] ?>" class="link"><?= $links[$page][1] ?>
      </a>
    </li>
    <li><a target="_self" href="?logout" class="link">Déconnexion</a></li>
    <?php else : ?>
    <li>
      <a target="_self" href="<?= $links[$page][0] ?>" class="link"><?= $links[$page][1] ?>
      </a>
    </li>
    <?php endif ?>
  </ul>
</nav>