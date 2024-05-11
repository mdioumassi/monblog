<?php

namespace App\Twig;

use App\Entity\Menu;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class AppExtension extends \Twig\Extension\AbstractExtension
{
    const ADMIN_NAMESPACE = 'App\Controller\Admin';

    public function __construct(
        private RouterInterface $router,
        private AdminUrlGenerator $adminUrlGenerator
    ) {      
    }

    public function getFilters(): array
    {
        return [
            new \Twig\TwigFilter('menuLink', [$this, 'menuLink']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new \Twig\TwigFunction('ea_index', [$this, 'getAdminUrl']),
        ];
    }

    public function getAdminUrl(string $controller) : string
    {
        return $this->adminUrlGenerator
            ->setController(self::ADMIN_NAMESPACE . DIRECTORY_SEPARATOR . $controller)
            ->generateUrl();
    }

    public function menuLink(Menu $menu)
    {
        $article = $menu->getArticle();
        $category = $menu->getCategory();
        $page = $menu->getPage();

        $url = $menu->getLink() ?: '#';
        if ($url != '#') {
            return $url;
        }

        if ($article) {
            return $this->router->generate('article_show', ['slug' => $article->getSlug()]);
        }

        if ($category) {
            return $this->router->generate('category_show', ['slug' => $category->getSlug()]);
        }

        if ($page) {
            return $this->router->generate('page_show', ['slug' => $page->getSlug()]);
        }
    }
}