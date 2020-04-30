<?php

namespace WS\Core\Controller\CMS;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class SecurityController extends AbstractController
{
    protected $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @Route("/login", name="ws_cms_login")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Security\Http\Authentication\AuthenticationUtils $authenticationUtils
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils, AuthorizationCheckerInterface $authChecker)
    {
        if ($authChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('ws_dashboard');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        if ($error instanceof \Exception) {
            // overwrite message to show invalid credentials simple message
            $error = new BadCredentialsException($this->translator->trans('login.bad_credentials', [], 'ws_cms'));
        }

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('@WSCore/cms/security/login.html.twig', [
          'last_username' => $lastUsername,
          'error' => $error,
        ]);
    }
}
