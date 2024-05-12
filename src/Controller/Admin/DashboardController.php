<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Menu;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Option\EA;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private AdminUrlGenerator $adminUrlGenerator
    ) {}

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $url = $this->adminUrlGenerator
            ->setController(ArticleCrudController::class)
            ->setAction(Action::INDEX)
            ->set('menuIndex', 0)
            ->generateUrl();
        
        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Symfony CMS')
            ->renderContentMaximized();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Aller sur le site', 'fas fa-undo', 'app_home');

        if ($this->isGranted('ROLE_ADMIN')) {
            yield MenuItem::section('Administration');
            yield MenuItem::subMenu('Menus', 'fas fa-list')->setSubItems([
                MenuItem::linkToCrud('Pages', 'fas fa-file', Menu::class)
                    ->setQueryParameter('submenuIndex', 0)->setAction(Crud::PAGE_NEW),
                MenuItem::linkToCrud('Articles', 'fas fa-newspaper', Menu::class)
                    ->setQueryParameter('submenuIndex', 1)->setAction(Crud::PAGE_INDEX),
                MenuItem::linkToCrud('Liens personnalisés', 'fas fa-link', Menu::class)
                    ->setQueryParameter('submenuIndex', 2)->setAction(Crud::PAGE_NEW),
                MenuItem::linkToCrud('Catégories', 'fab fa-delicious', Menu::class)
                    ->setQueryParameter('submenuIndex', 3)->setAction(Crud::PAGE_NEW),
            ]);
        }

        if ($this->isGranted('ROLE_AUTHOR')) {
            yield MenuItem::subMenu('Articles', 'fas fa-newspaper')->setSubItems([
                MenuItem::linkToCrud('Tous les articles', 'fas fa-file-text', Article::class),
                MenuItem::linkToCrud('Ajouter', 'fas fa-plus', Article::class)->setAction(Crud::PAGE_NEW),
                MenuItem::linkToCrud('Catégories', 'fas fa-list', Category::class),
            ]);
        }
        if ($this->isGranted('ROLE_ADMIN')) {
            yield MenuItem::linkToCrud('Commentaires', 'fas fa-comment', Comment::class);

            yield MenuItem::subMenu('Comptes', 'fas fa-user')->setSubItems([
                MenuItem::linkToCrud('Tous Utilisateurs', 'fas fa-user-firends', User::class),
                MenuItem::linkToCrud('Ajouter', 'fas fa-plus', User::class)->setAction(Crud::PAGE_NEW),
            ]);
        }
    }
}
