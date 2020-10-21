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

/* FrontasticCatwalkFrontendBundle:Account:profile.html.twig */
class __TwigTemplate_761c055e421363c663e24de22409e3d9e862569349360f93120c7de21491a847 extends \Twig\Template
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
        $this->loadTemplate("@FrontasticCatwalkFrontendBundle/render.html.twig", "FrontasticCatwalkFrontendBundle:Account:profile.html.twig", 1)->display(twig_array_merge($context, ["node" => ($context["node"] ?? null), "page" => ($context["page"] ?? null), "data" => ($context["data"] ?? null)]));
    }

    public function getTemplateName()
    {
        return "FrontasticCatwalkFrontendBundle:Account:profile.html.twig";
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
        return new Source("", "FrontasticCatwalkFrontendBundle:Account:profile.html.twig", "/var/www/frontastic/paas/catwalk/src/php/FrontendBundle/Resources/views/Account/profile.html.twig");
    }
}
