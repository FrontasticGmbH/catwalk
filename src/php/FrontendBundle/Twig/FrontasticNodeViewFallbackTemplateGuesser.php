<?php

namespace Frontastic\Catwalk\FrontendBundle\Twig;

use Psr\Log\LoggerInterface;
use QafooLabs\Bundle\NoFrameworkBundle\View\TemplateGuesser;
use Twig\Environment;

/**
 * An improved templateguesser which falls back to our standard node view, if you did not create one explicitly.
 *
 * This means, whenever a controller returns an array but has no twig template associated, we assume you want to render a node.
 */
class FrontasticNodeViewFallbackTemplateGuesser implements TemplateGuesser
{
    /**
     * @var TemplateGuesser
     */
    private $innerTemplateGuesser;

    /**
     * @var LoggerInterface
     */
    private $twigLogger;

    private $twigLoader;

    public function __construct(LoggerInterface $twigLogger, TemplateGuesser $innerTemplateGuesser, Environment $twig)
    {
        $this->innerTemplateGuesser = $innerTemplateGuesser;
        $this->twigLogger = $twigLogger;
        $this->twigLoader = $twig->getLoader();
    }

    public function guessControllerTemplateName($controller, $actionName, $format, $engine)
    {
        $templateName = $this->innerTemplateGuesser->guessControllerTemplateName($controller, $actionName, $format, $engine);

        if ($this->twigLoader->exists($templateName)) {
            return $templateName;
        }

        $this->twigLogger->debug(
            'Did not find a template for the current controller named {templateName}. Falling back to node template.',
            ['templateName' => $templateName]
        );

        return '@FrontasticCatwalkFrontendBundle/render.html.twig';
    }
}
