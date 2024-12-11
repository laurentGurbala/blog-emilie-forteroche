<div class="titreAdmin">
    <h2 class="titreAdmin-link"><a href="index.php?action=admin">Edition des articles</a></h2>
    <h2 class="titreAdmin-link"><a href="index.php?action=monitoring">Monitoring des articles</a></h2>
</div>

<div class="adminArticle">
    <div class="articleLine">
        <div class="title">Titre</div>
        <div class="views">Vues</div>
        <div class="comments">Commentaires</div>
        <div class="date">Date</div>
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