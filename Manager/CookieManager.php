<?php

namespace C2is\Bundle\CookieBundle\Manager;

use C2is\Bundle\CookieBundle\Exception\InvalidParameterException;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class CookieManager
 *
 * @package C2is\Bundle\CookieBundle\Controller
 */
class CookieManager
{
    /** @var \Symfony\Component\HttpFoundation\Request */
    protected $request;

    /** @var array */
    protected $config;

    /**
     * @param RequestStack $requestStack
     * @param array   $config
     */
    public function __construct(RequestStack $requestStack, array $config)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->config  = $config;
    }

    /**
     * @return bool
     */
    public function displayCookieMessage()
    {
        $request     = $this->request;
        $cookieName  = $this->config['cookie_name'];
        $occurrences = (int)$this->config['occurrences'];

        return null === ($cookie = $request->cookies->get($cookieName, null)) || (int)$cookie < $occurrences;
    }

    /**
     * @param string $action
     *
     * @return \Symfony\Component\HttpFoundation\Cookie
     * @throws \Exception
     */
    public function generateEmptyCookie($action = 'close')
    {
        $cookieConfig = $this->config;
        $this->validateAction($action);

        return new Cookie($cookieConfig['cookie_name'], 0, $cookieConfig['cookie_expire']);
    }

    /**
     * @param string $action
     *
     * @return \Symfony\Component\HttpFoundation\Cookie
     * @throws \Exception
     */
    public function generateCookie($action = 'close')
    {
        $cookieConfig = $this->config;
        $this->validateAction($action);

        $request     = $this->request;
        $cookieName  = $cookieConfig['cookie_name'];
        $occurrences = $step = $cookieConfig['actions'][$action];
        $cookie      = $request->cookies->get($cookieName, null);

        if ($cookie) {
            $occurrences = ((int)$cookie) + $step;
        }

        return new Cookie($cookieConfig['cookie_name'], $occurrences, $cookieConfig['cookie_expire']);
    }

    /**
     * @param $action
     *
     * @throws \C2is\Bundle\CookieBundle\Exception\InvalidParameterException
     */
    protected function validateAction($action)
    {
        $cookieConfig = $this->config;

        if (!isset($cookieConfig['actions'][$action])) {
            throw new InvalidParameterException(
                sprintf(
                    'Invalid action used to generate a cookie. Passed "%s", available actions are "%s"',
                    $action,
                    array_values($cookieConfig['actions'])
                )
            );
        }
    }
}
