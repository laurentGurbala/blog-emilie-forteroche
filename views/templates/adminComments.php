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
    <form method="POST" action="index.php?action=deleteComment&articleId=<?= $article->getId() ?>">
        <ul>
            <?php foreach ($comments as $comment): ?>
                <li>
                    <input type="checkbox" name="comment_ids[]" id="comment_<?= $comment->getId() ?>" value="<?= $comment->getId() ?>">
                    <p class="commentContent"><?= htmlspecialchars($comment->getContent()) ?></p>
                </li>
            <?php endforeach ?>
        </ul>
        <button class="submit" type="submit">Supprimer</button>
    </form>
</div>