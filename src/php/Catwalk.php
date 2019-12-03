<?php

namespace Frontastic\Catwalk;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;
use Doctrine\Common\Annotations\AnnotationRegistry;

class Catwalk
{
    public static function runWeb(string $projectDirectory, $autoloader): void
    {
        // Resolve all relative path elements
        $projectDirectory = realpath($projectDirectory);

        AppKernel::$catwalkBaseDir = $projectDirectory;

        (new \Frontastic\Common\EnvironmentResolver())->loadEnvironmentVariables(
            [$projectDirectory . '/..', $projectDirectory . '/../paas/catwalk', $projectDirectory],
            AppKernel::getBaseConfiguration()
        );

        if (class_exists('Tideways\Profiler')) {
            \Tideways\Profiler::start(array('api_key' => getenv('tideways_key')));
        }

        $env = getenv('env') ?? 'prod';
        $debug = (bool) ($_SERVER['APP_DEBUG'] ?? (AppKernel::isDebugEnvironment($env)));

        if ($debug) {
            umask(0000);
            Debug::enable();
        }

        $trustedProxies = getenv('trusted_proxies') ?? false;
        if ($trustedProxies) {
            if (trim($trustedProxies) === '*') {
            // Trust all proxies. In our cloud setup servers are only reachable through the LB but the LB IPs change.
                $trustedProxies = $_SERVER['REMOTE_ADDR'];
            }
        }

        if ($trustedHosts = $_SERVER['TRUSTED_HOSTS'] ?? false) {
            Request::setTrustedHosts(explode(',', $trustedHosts));
        }

        try {
            AnnotationRegistry::registerLoader(array($autoloader, 'loadClass'));

            $request = Request::createFromGlobals();
            $kernel = new AppKernel($env, $debug);
            $response = $kernel->handle($request);
            $response->send();
            $kernel->terminate($request, $response);
        } catch (\Throwable $e) {
            syslog(LOG_CRIT, $e->getMessage() . PHP_EOL . $e->getTraceAsString());

            if (!headers_sent()) {
                header("HTTP/1.0 500 Internal Server Error");
            }

            echo "<html><body><h1>Internal Server Error</h1>";
            if (AppKernel::getDebug()) {
                echo '<pre style="white-space: pre-wrap;">', $e, '</pre>';
            }
            echo "</body></html>";
        }
    }
}
