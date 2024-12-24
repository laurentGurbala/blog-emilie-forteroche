<?php

$sort = Utils::request("sort");
$orderTri = Utils::request("order");

/**
 * Génère un lien de tri pour une colonne donnée.
 *
 * @param string $column Nom de la colonne pour le tri.
 * @param ?string $currentSort Colonne actuellement triée (ou null si aucun tri).
 * @param ?string $currentOrder Ordre actuel (asc, desc, ou null si aucun tri).
 * @param string $label Label à afficher pour le lien.
 * @return string Lien HTML pour le tri.
 */
function generateSortLink(string $column, ?string $currentSort, ?string $currentOrder, string $label): string
{
    $newOrder = $currentSort === $column && $currentOrder === "asc" ? "desc" : "asc";
    $arrow = $currentSort === $column ? ($currentOrder === "asc" ? "↑" : "↓") : "";
    return "<a href='index.php?action=monitoring&sort={$column}&order={$newOrder}'>{$label} {$arrow}</a>";
}

?>

<div class="titreAdmin">
    <h2 class="titreAdmin-link"><a href="index.php?action=admin">Edition des articles</a></h2>
    <h2 class="titreAdmin-link"><a href="index.php?action=monitoring">Monitoring des articles</a></h2>
</div>

<div class="adminArticle">
    <div class="articleLine">
        <div class="title">
            <?= generateSortLink("title", $sort, $orderTri, "Titre") ?>
        </div>
        <div class="views">
            <?= generateSortLink("views", $sort, $orderTri, "Vues") ?>
        </div>
        <div class="comments">
            <?= generateSortLink("comments", $sort, $orderTri, "Commentaires") ?>
        </div>
        <div class="date">
            <?= generateSortLink("date", $sort, $orderTri, "Date") ?>
        </div>
    </div>

    <?php foreach ($articles as $article) : ?>
        <div class="articleLine">
            <div class="title">
                <a href="index.php?action=adminComments&articleId=<?= $article->getId() ?>">
                    <?= $article->getTitle() ?>
                </a>
            </div>
            <div class="views"><?= $article->getNbView() ?></div>
            <div class="comments">
                <a href="index.php?action=adminComments&articleId=<?= $article->getId() ?>">
                    <?= $article->getNbComments() ?>
                </a>
            </div>
            <div class="date"><?= Utils::convertDateToFrenchFormat($article->getDateCreation()) ?></div>
        </div>
    <?php endforeach; ?>
</div>