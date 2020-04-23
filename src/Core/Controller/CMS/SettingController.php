<?php

namespace WS\Core\Controller\CMS;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use WS\Core\Service\SettingService;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/settings", name="ws_setting_")
 */
class SettingController extends AbstractController
{
    protected $translator;
    protected $service;

    public function __construct(TranslatorInterface $translator, SettingService $service)
    {
        $this->translator = $translator;
        $this->service = $service;
    }

    /**
     * @Route("/{section}", name="index")
     *
     * @param string $section
     *
     * @return Response
     */
    public function index(string $section)
    {
        $section = $this->service->getSection($section);
        if ($section === null) {
            throw new NotFoundHttpException();
        }

        if (!$this->isGranted($section->getRole())) {
            $exception = $this->createAccessDeniedException($this->translator->trans('not_allowed', [], 'ws_cms'));
            throw $exception;
        }

        return $this->render('@WSCore/cms/setting/index.html.twig', [
            'section' => $section
        ]);
    }

    /**
     * @Route("/{section}/save", name="save", methods={"POST"})
     *
     * @param Request $request
     * @param string $section
     *
     * @return Response
     */
    public function save(Request $request, string $section)
    {
        if (!$request->isXmlHttpRequest()) {
            return $this->json(
                ['msg' => $this->translator->trans('bad_request', [], 'ws_cms')],
                Response::HTTP_BAD_REQUEST
            );
        }

        $section = $this->service->getSection($section);
        if ($section === null) {
            return $this->json(
                ['msg' => $this->translator->trans('bad_request', [], 'ws_cms')],
                Response::HTTP_BAD_REQUEST
            );
        }

        if (!$this->isGranted($section->getRole())) {
            $exception = $this->createAccessDeniedException($this->translator->trans('not_allowed', [], 'ws_cms'));
            throw $exception;
        }

        $options = json_decode((string) $request->getContent(), true);
        foreach ($options as $settingCode => $settingValue) {
            $this->service->save($section, $settingCode, $settingValue);
        }

        return $this->json(
            ['msg'=> $this->translator->trans('save_success', [], 'ws_cms_setting')],
            Response::HTTP_OK
        );
    }

    public function form(string $section, string $group)
    {
        return $this->render('@WSCore/cms/setting/form.html.twig', [
            'settings' => $this->service->getSettingsByGroup($section, $group),
        ]);
    }
}
