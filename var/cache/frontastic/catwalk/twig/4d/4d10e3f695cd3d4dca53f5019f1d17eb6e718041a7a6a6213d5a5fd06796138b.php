<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* FrontasticCatwalkFrontendBundle:Error:error.html.twig */
class __TwigTemplate_c811bb826a4175649a5ad8ba047e827a409a82c942a1d4473797b79de80e3180 extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        $this->loadTemplate("@FrontasticCatwalkFrontendBundle/render.html.twig", "FrontasticCatwalkFrontendBundle:Error:error.html.twig", 1)->display(twig_array_merge($context, ["node" => ($context["node"] ?? null), "page" => ($context["page"] ?? null), "data" => ($context["data"] ?? null)]));
    }

    public function getTemplateName()
    {
        return "FrontasticCatwalkFrontendBundle:Error:error.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "FrontasticCatwalkFrontendBundle:Error:error.html.twig", "/var/www/frontastic/paas/catwalk/src/php/FrontendBundle/Resources/views/Error/error.html.twig");
    }
}
