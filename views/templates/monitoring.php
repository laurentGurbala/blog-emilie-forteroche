<?php

$sort = Utils::request("sort");
$orderTri = Utils::request("order");

?>

<div class="titreAdmin">
    <h2 class="titreAdmin-link"><a href="index.php?action=admin">Edition des articles</a></h2>
    <h2 class="titreAdmin-link"><a href="index.php?action=monitoring">Monitoring des articles</a></h2>
</div>

<div class="adminArticle">
    <div class="articleLine">
        <div class="title">
            <a href="index.php?action=monitoring&sort=title&order=<?= $orderTri === "asc" ? "desc" : "asc"; ?>">
                Titre <?= $sort === 'title' ? ($orderTri === 'asc' ? '↑' : '↓') : '' ?>
            </a>
        </div>
        <div class="views">
            <a href="index.php?action=monitoring&sort=views&order=<?= $orderTri === "asc" ? "desc" : "asc" ?>">
                Vues <?= $sort === 'views' ? ($orderTri === 'asc' ? '↑' : '↓') : '' ?>
            </a>
        </div>
        <div class="comments">
            <a href="index.php?action=monitoring&sort=comments&order=<?= $orderTri === "asc" ? "desc" : "asc" ?>">
                Commentaires <?= $sort === 'comments' ? ($orderTri === 'asc' ? '↑' : '↓') : '' ?>
            </a>
        </div>
        <div class="date">
            <a href="index.php?action=monitoring&sort=date&order=<?= $orderTri === "asc" ? "desc" : "asc" ?>">
                date <?= $sort === 'date' ? ($orderTri === 'asc' ? '↑' : '↓') : '' ?>
            </a>
        </div>
    </div>
    <?php foreach ($articles as $article) { ?>
        <div class="articleLine">
            <div class="title"><?= $article->getTitle() ?></div>
            <div class="views"><?= $article->getNbView() ?></div>
            <div class="comments"><?= $article->getNbComments() ?></div>
            <div class="date"><?= Utils::convertDateToFrenchFormat($article->getDateCreation()) ?></div>
        </div>
    <?php } ?>
</div>