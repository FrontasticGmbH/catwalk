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

/* @Tastic/page.html.twig */
class __TwigTemplate_6cadac0a88ae99cbf8379102aa89e4e2b1983783a41402fb06b1b46d2a84c707 extends \Twig\Template
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
        $this->loadTemplate("@Tastic/layout.html.twig", "@Tastic/page.html.twig", 1)->display(twig_array_merge($context, ["node" => ($context["node"] ?? null), "page" => ($context["page"] ?? null), "data" => ($context["data"] ?? null), "tastics" => ($context["tastics"] ?? null), "facets" => ($context["facets"] ?? null), "categories" => ($context["categories"] ?? null), "route" => ($context["route"] ?? null)]));
    }

    public function getTemplateName()
    {
        return "@Tastic/page.html.twig";
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
        return new Source("", "@Tastic/page.html.twig", "/var/www/frontastic/paas/catwalk/src/php/TwigTasticBundle/Resources/views/page.html.twig");
    }
}
