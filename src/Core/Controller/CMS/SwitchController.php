<?php

namespace WS\Core\Controller\CMS;

use WS\Core\Entity\Domain;
use WS\Core\Service\ContextService;
use WS\Core\Service\DomainService;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class SwitchController extends AbstractController
{
    protected $translator;
    protected $service;

    public function __construct(TranslatorInterface $translator, DomainService $service)
    {
        $this->translator = $translator;
        $this->service = $service;
    }

    /**
     * @Route("/switch-domain/{id}", name="ws_switch_domain", methods={"GET"})
     * @Security("is_granted('ROLE_CMS')", message="not_allowed")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function switch(Request $request, $id)
    {
        $domain = $this->service->get($id);
        if ($domain instanceof Domain) {
            $session = $request->getSession();
            if ($session !== null) {
                $session->set(ContextService::SESSION_DOMAIN, $domain->getId());

                $this->addFlash('cms_success', $this->translator->trans(
                    'domain_switched',
                    [
                        '%domain%' => $domain->getHost(),
                        '%locale%' => $domain->getLocale(),
                    ],
                    'ws_cms'
                ));

                return $this->redirectToRoute('ws_dashboard');
            }
        }

        throw $this->createNotFoundException();
    }
}
