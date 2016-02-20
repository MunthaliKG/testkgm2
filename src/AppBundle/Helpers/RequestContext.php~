<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Helpers;

use Symfony\Component\HttpFoundation\RequestStack;

class RequestContext extends \Symfony\Component\Routing\RequestContext
{

    private $request;

    /**
     * Constructor.
     *
     * @param string $baseUrl     The base URL
     * @param string $method      The HTTP method
     * @param string $host        The HTTP host name
     * @param string $scheme      The HTTP scheme
     * @param int    $httpPort    The HTTP port
     * @param int    $httpsPort   The HTTPS port
     * @param string $path        The path
     * @param string $queryString The query string
     *
     * @api
     */
    public function __construct($baseUrl = '', $method = 'GET', $host = 'localhost', $scheme = 'http', $httpPort = 80, $httpsPort = 443, $path = '/', $queryString = ''
        , RequestStack $rs)
    {
        parent::__construct($baseUrl, $method, $host, $scheme, $httpPort, $httpsPort, $path, $queryString);
        $this->request = $rs->getCurrentRequest();
    }

    
    /** Gets the current request object
     */
    public function getEmisCode()
    {
        $emisCode = explode('/', $this->getPathInfo())[2];
        return $emisCode;
    }
}
