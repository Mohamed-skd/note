<nav id="nav-site">
  <ul>
    <li>
      <a target="_self" href="<?= $currentUser ? PUBLIC_LINK . "notes" : PUBLIC_LINK . "login" ?>" class="link">
        <?= $currentUser ? "Mes notes" : "Connexion" ?>
      </a>
    </li>

    <?php if ($currentUser) : ?>
    <li><a target="_self" href="?logout" class="link">Déconnexion</a></li>
    <?php endif ?>
  </ul>
</nav>