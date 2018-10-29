<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;
use Doctrine\Common\Annotations\AnnotationRegistry;

use Frontastic\Catwalk\AppKernel;

$autoloader = require_once __DIR__ . "/../vendor/autoload.php";

AppKernel::$catwalkBaseDir = dirname(__DIR__);

(new \Frontastic\Common\EnvironmentResolver())->loadEnvironmentVariables(
    [__DIR__ . '/../../../', __DIR__ . '/../'],
    AppKernel::getBaseConfiguration()
);

if (class_exists('Tideways\Profiler')) {
    \Tideways\Profiler::start(array('api_key' => getenv('tideways.key')));
}

$env = getenv('env') ?? 'prod';
$debug = (bool) ($_SERVER['APP_DEBUG'] ?? (AppKernel::isDebugEnvironment($env)));

if ($debug) {
    umask(0000);
    Debug::enable();
}

if ($trustedProxies = $_SERVER['TRUSTED_PROXIES'] ?? false) {
    Request::setTrustedProxies(explode(',', $trustedProxies), Request::HEADER_X_FORWARDED_ALL ^ Request::HEADER_X_FORWARDED_HOST);
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
    if (!headers_sent()) {
        header("HTTP/1.0 500 Internal Server Error");
    }

    echo "<html><body><h1>Internal Server Error</h1>";
    if (AppKernel::getDebug()) {
        echo '<pre style="white-space: pre-wrap;">', $e, '</pre>';
    }
    echo "</body></html>";
}
