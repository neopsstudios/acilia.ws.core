<?php

namespace WS\Core\Controller\CMS;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="ws_dashboard")
     *
     * @return Response
     */
    public function index()
    {
        return $this->render('@WSCore/cms/dashboard/index.html.twig');
    }
}
