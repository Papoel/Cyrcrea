<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException("Votre rôle ne vous permet pas d'accéder a l'administration");
        }

        return $this->render('admin/dashboard.html.twig');
    }

        // Option 1. Vous pouvez faire en sorte que votre tableau de bord redirige vers une page commune de votre backend.

        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class) ;
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl()) ;

        // Option 2. Vous pouvez faire en sorte que votre tableau de bord redirige vers différentes pages selon l'utilisateur

        // if ('jane' === $this->getUser()->getUsername()) {
        // return $this->redirect('....') ;
        // }

        // Option 3. Vous pouvez rendre un modèle personnalisé pour afficher un véritable tableau de bord avec des widgets, etc.
        // (conseil : c'est plus facile si votre modèle s'étend à partir de @EasyAdmin/page/content.html.twig)

        // return $this->render('some/path/my-dashboard.html.twig') ;


    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Cyrcrea');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
