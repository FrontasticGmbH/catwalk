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

/* @Tastic/layout.html.twig */
class __TwigTemplate_44cbc296ee7c4f45619cee9047c86741b6e0fdffde536c963f1a210ff9fe181d extends \Twig\Template
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
        echo "<header class='c-page-head'>
    ";
        // line 2
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "regions", [], "any", false, true, false, 2), "head", [], "any", true, true, false, 2)) {
            // line 3
            echo "        ";
            $this->loadTemplate("@Tastic/region.html.twig", "@Tastic/layout.html.twig", 3)->display(twig_array_merge($context, ["node" => ($context["node"] ?? null), "page" => ($context["page"] ?? null), "data" => ($context["data"] ?? null), "region" => twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "regions", [], "any", false, false, false, 3), "head", [], "any", false, false, false, 3)]));
            // line 4
            echo "    ";
        }
        // line 5
        echo "</header>
<main class='c-page-body'>
    <div class='c-page-wrapper o-wrapper'>
        ";
        // line 8
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "regions", [], "any", false, true, false, 8), "main", [], "any", true, true, false, 8)) {
            // line 9
            echo "            ";
            $this->loadTemplate("@Tastic/region.html.twig", "@Tastic/layout.html.twig", 9)->display(twig_array_merge($context, ["node" => ($context["node"] ?? null), "page" => ($context["page"] ?? null), "data" => ($context["data"] ?? null), "region" => twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "regions", [], "any", false, false, false, 9), "main", [], "any", false, false, false, 9)]));
            // line 10
            echo "        ";
        }
        // line 11
        echo "    </div>
</main>
<footer class='c-page-foot'>
    ";
        // line 14
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "regions", [], "any", false, true, false, 14), "footer", [], "any", true, true, false, 14)) {
            // line 15
            echo "        ";
            $this->loadTemplate("@Tastic/region.html.twig", "@Tastic/layout.html.twig", 15)->display(twig_array_merge($context, ["node" => ($context["node"] ?? null), "page" => ($context["page"] ?? null), "data" => ($context["data"] ?? null), "region" => twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "regions", [], "any", false, false, false, 15), "footer", [], "any", false, false, false, 15)]));
            // line 16
            echo "    ";
        }
        // line 17
        echo "</footer>
";
    }

    public function getTemplateName()
    {
        return "@Tastic/layout.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  74 => 17,  71 => 16,  68 => 15,  66 => 14,  61 => 11,  58 => 10,  55 => 9,  53 => 8,  48 => 5,  45 => 4,  42 => 3,  40 => 2,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "@Tastic/layout.html.twig", "/var/www/frontastic/paas/catwalk/src/php/TwigTasticBundle/Resources/views/layout.html.twig");
    }
}
