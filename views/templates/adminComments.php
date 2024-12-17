<?php

/** 
 * Affichage de la partie de modération des commentaires. 
 */
?>

<div class="titreAdmin">
    <h2 class="titreAdmin-link"><a href="index.php?action=admin">Edition des articles</a></h2>
    <h2 class="titreAdmin-link"><a href="index.php?action=monitoring">Monitoring des articles</a></h2>
</div>

<h2>Commentaire de l'article:</h2>

<div class="commentList">
    <p class="articleTitle"><?= $article->getTitle() ?></p>
    <ul>
        <?php foreach ($comments as $comment): ?>
            <li>
                <p class="commentContent"><?= $comment->getContent() ?></p>
                <div class="commentAction">
                    <a class="submit" href="index.php?action=deleteComment&commentId=<?= $comment->getId() ?>&articleId=<?= $article->getId() ?>"
                        <?= Utils::askConfirmation("Êtes-vous sûr de vouloir supprimer ce commentaire ?") ?>>
                        Supprimer
                    </a>
                </div>
            </li>
        <?php endforeach ?>
    </ul>
</div>