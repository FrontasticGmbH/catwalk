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

/* FrontasticCatwalkFrontendBundle:Preview:view.html.twig */
class __TwigTemplate_c55eeb874d645699441980d3ff68184ea257131848e770bffd5293fb45b67c20 extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
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
        $this->parent = $this->loadTemplate("layout.html.twig", "FrontasticCatwalkFrontendBundle:Preview:view.html.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_title($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 4
        $this->displayParentBlock("title", $context, $blocks);
        echo "
";
    }

    // line 7
    public function block_main($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 8
        ob_start(function () { return ''; });
        // line 9
        $context["response"] = call_user_func_array($this->env->getFunction('frontastic_render')->getCallable(), [twig_get_attribute($this->env, $this->source, ($context["app"] ?? null), "request", [], "any", false, false, false, 9), $this->extensions['Frontastic\Catwalk\FrontendBundle\Twig\NodeExtension']->completeInformation(["previewId" => ($context["previewId"] ?? null), "node" => ($context["node"] ?? null), "page" => ($context["page"] ?? null), "data" => ($context["data"] ?? null)])]);
        // line 10
        echo "    <div id=\"app\">
        ";
        // line 11
        if ((twig_get_attribute($this->env, $this->source, ($context["response"] ?? null), "status", [], "any", false, false, false, 11) < 400)) {
            echo twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["response"] ?? null), "body", [], "any", false, false, false, 11), "app", [], "any", false, false, false, 11);
        }
        // line 12
        echo "    </div>
    <div id=\"appData\"
         data-props=\"";
        // line 14
        echo twig_escape_filter($this->env, json_encode(call_user_func_array($this->env->getFilter('frontastic_json_serialize')->getCallable(), [$this->extensions['Frontastic\Catwalk\FrontendBundle\Twig\NodeExtension']->completeInformation(["previewId" => ($context["previewId"] ?? null), "node" => ($context["node"] ?? null), "page" => ($context["page"] ?? null), "data" => ($context["data"] ?? null)])])), "html", null, true);
        echo "\"
         data-app=\"";
        // line 15
        echo twig_escape_filter($this->env, json_encode($this->extensions['Frontastic\Catwalk\FrontendBundle\Twig\ContextExtension']->getContext()), "html", null, true);
        echo "\"
     />
";
        echo trim(preg_replace('/>\s+</', '><', ob_get_clean()));
    }

    public function getTemplateName()
    {
        return "FrontasticCatwalkFrontendBundle:Preview:view.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  80 => 15,  76 => 14,  72 => 12,  68 => 11,  65 => 10,  63 => 9,  61 => 8,  57 => 7,  51 => 4,  47 => 3,  36 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "FrontasticCatwalkFrontendBundle:Preview:view.html.twig", "/var/www/frontastic/paas/catwalk/src/php/FrontendBundle/Resources/views/Preview/view.html.twig");
    }
}
