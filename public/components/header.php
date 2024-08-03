<header>
<h1><?= $title ?></h1>

<aside id="notifications">
<?php if ($templFn->notification): ?>
    <p class="<?= $templFn->notification["type"] ?>"><?= $templFn->notification["content"] ?></p>
<?php endif ?>
</aside>
</header>