<?php

/**
 * Classe qui gère les articles.
 */
class ArticleManager extends AbstractEntityManager
{
    /**
     * Récupère tous les articles.
     * @return array : un tableau d'objets Article.
     */
    public function getAllArticles(): array
    {
        $sql = "SELECT * FROM article";
        $result = $this->db->query($sql);
        $articles = [];

        while ($article = $result->fetch()) {
            $articles[] = new Article($article);
        }
        return $articles;
    }

    /**
     * Trie une liste d'articles en fonction de la colonne et de l'ordre spécifiés.
     * 
     * @param array $articles Liste des articles à trier.
     * @param string $sort Colonne à utiliser pour le tri (ex. 'title', 'views').
     * @param string $order Ordre de tri ('asc' ou 'desc').
     * 
     * @return array Liste triée des articles.
     */
    public function sortArticles(array $articles, string $sort, string $order): array
    {
        $sortableColumns = [
            'title' => 'getTitle',
            'views' => 'getNbView',
            'comments' => 'getNbComments',
            'date' => 'getDateCreation'
        ];

        if (!isset($sortableColumns[$sort])) {
            throw new Exception("Colonne de tri invalide.");
        }

        usort($articles, function ($a, $b) use ($sortableColumns, $sort, $order) {
            $valueA = $a->{$sortableColumns[$sort]}();
            $valueB = $b->{$sortableColumns[$sort]}();

            if ($order === 'asc') {
                return $valueA <=> $valueB; // Comparaison ascendante
            } else {
                return $valueB <=> $valueA; // Comparaison descendante
            }
        });

        return $articles;
    }


    /**
     * Récupère un article par son id.
     * @param int $id : l'id de l'article.
     * @return Article|null : un objet Article ou null si l'article n'existe pas.
     */
    public function getArticleById(int $id): ?Article
    {
        $sql = "SELECT * FROM article WHERE id = :id";
        $result = $this->db->query($sql, ['id' => $id]);
        $article = $result->fetch();
        if ($article) {
            return new Article($article);
        }
        return null;
    }

    /**
     * Ajoute ou modifie un article.
     * On sait si l'article est un nouvel article car son id sera -1.
     * @param Article $article : l'article à ajouter ou modifier.
     * @return void
     */
    public function addOrUpdateArticle(Article $article): void
    {
        if ($article->getId() == -1) {
            $this->addArticle($article);
        } else {
            $this->updateArticle($article);
        }
    }

    /**
     * Ajoute un article.
     * @param Article $article : l'article à ajouter.
     * @return void
     */
    public function addArticle(Article $article): void
    {
        $sql = "INSERT INTO article (id_user, title, content, date_creation) VALUES (:id_user, :title, :content, NOW())";
        $this->db->query($sql, [
            'id_user' => $article->getIdUser(),
            'title' => $article->getTitle(),
            'content' => $article->getContent()
        ]);
    }

    /**
     * Modifie un article.
     * @param Article $article : l'article à modifier.
     * @return void
     */
    public function updateArticle(Article $article): void
    {
        $sql = "UPDATE article SET title = :title, content = :content, nb_view = :nbView, date_update = NOW() WHERE id = :id";
        $this->db->query($sql, [
            'title' => $article->getTitle(),
            'content' => $article->getContent(),
            "nbView" => $article->getNbView(),
            'id' => $article->getId()
        ]);
    }

    /**
     * Supprime un article.
     * @param int $id : l'id de l'article à supprimer.
     * @return void
     */
    public function deleteArticle(int $id): void
    {
        $sql = "DELETE FROM article WHERE id = :id";
        $this->db->query($sql, ['id' => $id]);
    }
}
