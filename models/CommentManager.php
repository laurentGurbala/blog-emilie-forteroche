<?php

/**
 * Cette classe sert à gérer les commentaires. 
 */
class CommentManager extends AbstractEntityManager
{
    /**
     * Récupère tous les commentaires d'un article.
     * @param int $idArticle : l'id de l'article.
     * @return array : un tableau d'objets Comment.
     */
    public function getAllCommentsByArticleId(int $idArticle): array
    {
        $sql = "SELECT * FROM comment WHERE id_article = :idArticle";
        $result = $this->db->query($sql, ['idArticle' => $idArticle]);
        $comments = [];

        while ($comment = $result->fetch()) {
            $comments[] = new Comment($comment);
        }
        return $comments;
    }

    /**
     * Récupère un commentaire par son id.
     * @param int $id : l'id du commentaire.
     * @return Comment|null : un objet Comment ou null si le commentaire n'existe pas.
     */
    public function getCommentById(int $id): ?Comment
    {
        $sql = "SELECT * FROM comment WHERE id = :id";
        $result = $this->db->query($sql, ['id' => $id]);
        $comment = $result->fetch();
        if ($comment) {
            return new Comment($comment);
        }
        return null;
    }

    /**
     * Ajoute un commentaire.
     * @param Comment $comment : l'objet Comment à ajouter.
     * @return bool : true si l'ajout a réussi, false sinon.
     */
    public function addComment(Comment $comment): bool
    {
        $sql = "INSERT INTO comment (pseudo, content, id_article, date_creation) VALUES (:pseudo, :content, :idArticle, NOW())";
        $result = $this->db->query($sql, [
            'pseudo' => $comment->getPseudo(),
            'content' => $comment->getContent(),
            'idArticle' => $comment->getIdArticle()
        ]);
        return $result->rowCount() > 0;
    }

    /**
     * Compte le nombre de commentaires associés à un article spécifique.
     *
     * @param int $idArticle L'identifiant de l'article dont on souhaite connaître le nombre de commentaires.
     * @return int Le nombre total de commentaires pour l'article donné. Retourne 0 si aucun commentaire n'est trouvé.
     */
    public function countCommentsByArticleId(int $idArticle): int
    {
        // Requête SQL pour compter le nombre de commentaires liés à un article spécifique
        $sql = "SELECT COUNT(*) AS total FROM comment WHERE id_article = :id_article";

        // Exécute la requête avec le paramètre fourni
        $stmt = $this->db->query($sql, ["id_article" => $idArticle]);

        // Récupère le résultat sous forme de tableau associatif
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Retourne le nombre total de commentaires ou 0 si aucun résultat n'est trouvé
        return $result["total"] ?? 0;
    }


    /**
     * Supprime un commentaire.
     * @param Comment $comment : l'objet Comment à supprimer.
     * @return bool : true si la suppression a réussi, false sinon.
     */
    public function deleteComment(Comment $comment): bool
    {
        $sql = "DELETE FROM comment WHERE id = :id";
        $result = $this->db->query($sql, ['id' => $comment->getId()]);
        return $result->rowCount() > 0;
    }

    public function deleteMultiple(array $commentIds): bool
    {
        if (empty($commentIds)) {
            return false; // Pas d'IDs à supprimer
        }

        // Créer les placeholders nécessaires
        $placeholders = implode(',', array_fill(0, count($commentIds), '?'));
        $sql = "DELETE FROM comment WHERE id IN ($placeholders)";

        // Utiliser la méthode query pour exécuter la requête
        $stmt = $this->db->query($sql, $commentIds);

        // Vérifier si la requête a été exécutée avec succès
        return $stmt !== false;
    }
}
