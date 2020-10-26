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

/* FrontasticCatwalkFrontendBundle::render.html.twig */
class __TwigTemplate_177307753950436157b2586d586244aac5376888f091dc33dc086cb381ebb0f4 extends \Twig\Template
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
        // line 4
        $context["response"] = call_user_func_array($this->env->getFunction('frontastic_render')->getCallable(), [twig_get_attribute($this->env, $this->source, ($context["app"] ?? null), "request", [], "any", false, false, false, 4), ($context["pageData"] ?? null)]);
        // line 1
        $this->parent = $this->loadTemplate("layout.html.twig", "FrontasticCatwalkFrontendBundle::render.html.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 6
    public function block_meta($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 7
        echo twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["response"] ?? null), "body", [], "any", false, false, false, 7), "helmet", [], "any", false, false, false, 7), "meta", [], "any", false, false, false, 7);
        echo "
";
        // line 8
        echo twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["response"] ?? null), "body", [], "any", false, false, false, 8), "helmet", [], "any", false, false, false, 8), "link", [], "any", false, false, false, 8);
        echo "
";
        // line 9
        echo twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["response"] ?? null), "body", [], "any", false, false, false, 9), "helmet", [], "any", false, false, false, 9), "script", [], "any", false, false, false, 9);
        echo "
";
        // line 10
        echo twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["response"] ?? null), "body", [], "any", false, false, false, 10), "helmet", [], "any", false, false, false, 10), "styles", [], "any", false, false, false, 10);
        echo "
";
    }

    // line 13
    public function block_title($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 14
        echo twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["response"] ?? null), "body", [], "any", false, false, false, 14), "helmet", [], "any", false, false, false, 14), "title", [], "any", false, false, false, 14);
        echo "
";
    }

    // line 17
    public function block_main($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 18
        $this->loadTemplate("FrontasticCatwalkFrontendBundle::frontastic_render.html.twig", "FrontasticCatwalkFrontendBundle::render.html.twig", 18)->display(twig_array_merge($context, ["response" => ($context["response"] ?? null), "pageData" => ($context["pageData"] ?? null)]));
    }

    public function getTemplateName()
    {
        return "FrontasticCatwalkFrontendBundle::render.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  89 => 18,  85 => 17,  79 => 14,  75 => 13,  69 => 10,  65 => 9,  61 => 8,  57 => 7,  53 => 6,  48 => 1,  46 => 4,  44 => 3,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "FrontasticCatwalkFrontendBundle::render.html.twig", "/var/www/frontastic/paas/catwalk/src/php/FrontendBundle/Resources/views/render.html.twig");
    }
}
