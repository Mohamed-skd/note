<nav id="nav-site">
  <ul>
    <li>
      <a target="_self"
        href="<?= $currentUser ? ($page === "homepage" ? PUBLIC_LINK . "notes" : PUBLIC_LINK . "homepage") : PUBLIC_LINK . "login" ?>"
        class="link">
        <?= $currentUser ? ($page === "homepage" ? "Mes notes" : "Acceuil") : "Connexion" ?>
      </a>
    </li>

    <?php if ($currentUser) : ?>
    <li><a target="_self" href="?logout" class="link">Déconnexion</a></li>
    <?php endif ?>
  </ul>
</nav>