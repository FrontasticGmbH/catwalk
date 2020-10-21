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

/* FrontasticCatwalkFrontendBundle:Account:vouchers.html.twig */
class __TwigTemplate_ff28655bf4afa418ebe421e1c3d7e3f7dfa32abbabc30e7ed7edd152fbcc0007 extends \Twig\Template
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
        $this->loadTemplate("@FrontasticCatwalkFrontendBundle/render.html.twig", "FrontasticCatwalkFrontendBundle:Account:vouchers.html.twig", 1)->display(twig_array_merge($context, ["node" => ($context["node"] ?? null), "page" => ($context["page"] ?? null), "data" => ($context["data"] ?? null)]));
    }

    public function getTemplateName()
    {
        return "FrontasticCatwalkFrontendBundle:Account:vouchers.html.twig";
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
        return new Source("", "FrontasticCatwalkFrontendBundle:Account:vouchers.html.twig", "/var/www/frontastic/paas/catwalk/src/php/FrontendBundle/Resources/views/Account/vouchers.html.twig");
    }
}
