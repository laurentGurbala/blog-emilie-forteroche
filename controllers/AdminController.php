<?php

/**
 * Contrôleur de la partie admin.
 */

class AdminController
{

    /**
     * Affiche la page d'administration.
     * @return void
     */
    public function showAdmin(): void
    {
        // On vérifie que l'utilisateur est connecté.
        $this->checkIfUserIsConnected();

        // On récupère les articles.
        $articleManager = new ArticleManager();
        $articles = $articleManager->getAllArticles();

        // On affiche la page d'administration.
        $view = new View("Administration");
        $view->render("admin", [
            'articles' => $articles
        ]);
    }

    /**
     * Affiche la page de monitoring.
     * Permet de trier et de voir les statistiques des articles.
     * 
     * @return void
     */
    public function showMonitoring(): void
    {
        // On vérifie que l'utilisateur est connecté
        $this->checkIfUserIsConnected();

        // On récupère les articles
        $articleManager = new ArticleManager();
        $articles = $articleManager->getAllArticles();

        // Récupère le nombre de commentaires pour chaque article
        $commentManager = new CommentManager();
        foreach ($articles as $article) {
            $nbComment = $commentManager->countCommentsByArticleId($article->getId());
            $article->setNbComments($nbComment);
        }

        // Récupère les paramètres de tri
        $sort = Utils::request("sort", "title");
        $order = Utils::request("order", "asc");

        // Récupère les articles triés depuis le modèle
        $articleManager = new ArticleManager();
        $articles = $articleManager->sortArticles($articles, $sort, $order);

        // On affiche la page de monitoring
        $view = new View("Administration");
        $view->render("monitoring", ["articles" => $articles]);
    }

    public function showComments(): void
    {
        // Vérifie que l'utilisateur est connecté
        $this->checkIfUserIsConnected();

        // Récupérer l'ID de l'article
        $articleId = Utils::request("articleId", -1);

        // Récupérer l'article
        $articleManager = new ArticleManager();
        $article = $articleManager->getArticleById($articleId);
        if (!$article) {
            throw new Exception("Article non trouvé");
        }

        // Récupérer les commentaires
        $commentManager = new CommentManager();
        $comments = $commentManager->getAllCommentsByArticleId($articleId);

        // Afficher la vue
        $view = new View("Administration");
        $view->render("adminComments", [
            "article" => $article,
            "comments" => $comments
        ]);
    }

    /**
     * Vérifie que l'utilisateur est connecté.
     * @return void
     */
    private function checkIfUserIsConnected(): void
    {
        // On vérifie que l'utilisateur est connecté.
        if (!isset($_SESSION['user'])) {
            Utils::redirect("connectionForm");
        }
    }

    /**
     * Affichage du formulaire de connexion.
     * @return void
     */
    public function displayConnectionForm(): void
    {
        $view = new View("Connexion");
        $view->render("connectionForm");
    }

    /**
     * Connexion de l'utilisateur.
     * @return void
     */
    public function connectUser(): void
    {
        // On récupère les données du formulaire.
        $login = Utils::request("login");
        $password = Utils::request("password");

        // On vérifie que les données sont valides.
        if (empty($login) || empty($password)) {
            throw new Exception("Tous les champs sont obligatoires. 1");
        }

        // On vérifie que l'utilisateur existe.
        $userManager = new UserManager();
        $user = $userManager->getUserByLogin($login);
        if (!$user) {
            throw new Exception("L'utilisateur demandé n'existe pas.");
        }

        // On vérifie que le mot de passe est correct.
        if (!password_verify($password, $user->getPassword())) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            throw new Exception("Le mot de passe est incorrect : $hash");
        }

        // On connecte l'utilisateur.
        $_SESSION['user'] = $user;
        $_SESSION['idUser'] = $user->getId();

        // On redirige vers la page d'administration.
        Utils::redirect("admin");
    }

    /**
     * Déconnexion de l'utilisateur.
     * @return void
     */
    public function disconnectUser(): void
    {
        // On déconnecte l'utilisateur.
        unset($_SESSION['user']);

        // On redirige vers la page d'accueil.
        Utils::redirect("home");
    }

    /**
     * Affichage du formulaire d'ajout d'un article.
     * @return void
     */
    public function showUpdateArticleForm(): void
    {
        $this->checkIfUserIsConnected();

        // On récupère l'id de l'article s'il existe.
        $id = Utils::request("id", -1);

        // On récupère l'article associé.
        $articleManager = new ArticleManager();
        $article = $articleManager->getArticleById($id);

        // Si l'article n'existe pas, on en crée un vide. 
        if (!$article) {
            $article = new Article();
        }

        // On affiche la page de modification de l'article.
        $view = new View("Edition d'un article");
        $view->render("updateArticleForm", [
            'article' => $article
        ]);
    }

    /**
     * Ajout et modification d'un article. 
     * On sait si un article est ajouté car l'id vaut -1.
     * @return void
     */
    public function updateArticle(): void
    {
        $this->checkIfUserIsConnected();

        // On récupère les données du formulaire.
        $id = Utils::request("id", -1);
        $title = Utils::request("title");
        $content = Utils::request("content");

        // On vérifie que les données sont valides.
        if (empty($title) || empty($content)) {
            throw new Exception("Tous les champs sont obligatoires. 2");
        }

        // On crée l'objet Article.
        $article = new Article([
            'id' => $id, // Si l'id vaut -1, l'article sera ajouté. Sinon, il sera modifié.
            'title' => $title,
            'content' => $content,
            'id_user' => $_SESSION['idUser']
        ]);

        // On ajoute l'article.
        $articleManager = new ArticleManager();
        $articleManager->addOrUpdateArticle($article);

        // On redirige vers la page d'administration.
        Utils::redirect("admin");
    }


    /**
     * Suppression d'un article.
     * @return void
     */
    public function deleteArticle(): void
    {
        $this->checkIfUserIsConnected();

        $id = Utils::request("id", -1);

        // On supprime l'article.
        $articleManager = new ArticleManager();
        $articleManager->deleteArticle($id);

        // On redirige vers la page d'administration.
        Utils::redirect("admin");
    }

    public function deleteComment(): void
    {
        $this->checkIfUserIsConnected();

        // Récupérer les IDs des commentaires à supprimer
        $commentIds = Utils::request("comment_ids", []);
        $articleId = Utils::request("articleId");

        if (empty($commentIds)) {
            Utils::redirect("adminComments", ["articleId" => $articleId]);
            return;
        }

        // Supprimer les commentaires
        $commentManager = new CommentManager();
        $success = $commentManager->deleteMultiple($commentIds);

        if ($success) {
            // Rediriger avec un message de succès
            Utils::redirect("adminComments", ["articleId" => $articleId]);
            return;
        } else {
            throw new Exception("Erreur : Les commentaires n'ont pas pu être supprimés");
        }
    }
}
