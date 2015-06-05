<?php

namespace C2is\Bundle\CookieBundle\Controller;

use C2is\Bundle\CookieBundle\Manager\CookieManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\Translator;

/**
 * Class CookieController
 *
 * @package C2is\Bundle\CookieBundle\Controller
 */
class CookieController extends Controller
{
    /**
     * @return Response
     */
    public function initializeAction()
    {
        $cookieManager = $this->getCookieManager();
        $cookieClose   = $cookieManager->generateEmptyCookie('close');
        $cookieAccept  = $cookieManager->generateEmptyCookie('accept');
        $response      = new Response('');
        $response->headers->setCookie($cookieClose);
        $response->headers->setCookie($cookieAccept);

        return $response;
    }

    /**
     * @return Response
     */
    public function messageAction()
    {
        $cookieManager = $this->getCookieManager();
        $cacheDuration = 0;

        if ($cookieManager->displayCookieMessage()) {
            $response = $this->render('C2isCookieBundle::message.html.twig');
        } else {
            $response      = new Response('');
            $cacheDuration = 60 * 60 * 24;
        }

        $response->setPrivate();
        $response->setMaxAge($cacheDuration);
        $response->setSharedMaxAge($cacheDuration);

        return $response;
    }

    /**
     * @return JsonResponse
     */
    public function closeAction()
    {
        $cookieManager = $this->getCookieManager();
        $cookie        = $cookieManager->generateCookie('close');
        /** @var Translator $translator */
        $translator = $this->get('translator');

        $response = new JsonResponse(
            array(
                'success' => true,
                'message' => $translator->trans('c2is.cookie.close.message'),
            )
        );
        $response->headers->setCookie($cookie);
        $response->setPrivate();
        $response->setMaxAge(0);
        $response->setSharedMaxAge(0);

        return $response;
    }

    /**
     * @return JsonResponse
     */
    public function acceptAction()
    {
        $cookieManager = $this->getCookieManager();
        $cookie        = $cookieManager->generateCookie('accept');
        /** @var Translator $translator */
        $translator = $this->get('translator');

        $response = new JsonResponse(
            array(
                'success' => true,
                'message' => $translator->trans('c2is.cookie.accept.message'),
            )
        );
        $response->headers->setCookie($cookie);
        $response->setPrivate();
        $response->setMaxAge(0);
        $response->setSharedMaxAge(0);

        return $response;
    }

    /**
     * @return CookieManager
     */
    protected function getCookieManager()
    {
        return $this->get('c2is_cookie.manager');
    }
}
