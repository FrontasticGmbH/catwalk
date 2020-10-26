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

/* @Tastic/render.html.twig */
class __TwigTemplate_a84833ac8fa02282b86f6219c043a937bbbc85d085efac67787314c2b151c07d extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'meta' => [$this, 'block_meta'],
            'title' => [$this, 'block_title'],
            'main' => [$this, 'block_main'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 1
        return "layout.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 3
        $context["pageData"] = $this->extensions['Frontastic\Catwalk\FrontendBundle\Twig\NodeExtension']->completeInformation(["node" => ($context["node"] ?? null), "page" => ($context["page"] ?? null), "data" => ($context["data"] ?? null)]);
        // line 1
        $this->parent = $this->loadTemplate("layout.html.twig", "@Tastic/render.html.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 5
    public function block_meta($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 6
        echo "<!-- @TODO: How? -->
";
    }

    // line 9
    public function block_title($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 10
        echo "<!-- @TODO: How? -->
";
    }

    // line 13
    public function block_main($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 14
        $this->loadTemplate("@Tastic/node.html.twig", "@Tastic/render.html.twig", 14)->display(twig_array_merge($context, ($context["pageData"] ?? null)));
    }

    public function getTemplateName()
    {
        return "@Tastic/render.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  73 => 14,  69 => 13,  64 => 10,  60 => 9,  55 => 6,  51 => 5,  46 => 1,  44 => 3,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "@Tastic/render.html.twig", "/var/www/frontastic/paas/catwalk/src/php/TwigTasticBundle/Resources/views/render.html.twig");
    }
}
