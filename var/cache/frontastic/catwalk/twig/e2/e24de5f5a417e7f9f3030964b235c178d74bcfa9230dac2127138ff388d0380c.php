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

/* @Tastic/missingTastic.html.twig */
class __TwigTemplate_0353fca979a68bebe2ca88a5c7f5dd896e81890f4f7cd6fb4b45b102e30382e6 extends \Twig\Template
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
        echo "<div class='alert alert-warning'>
    <p>Tastic <code>";
        // line 2
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["tastic"] ?? null), "tasticType", [], "any", false, false, false, 2), "html", null, true);
        echo "</code> not yet implemented.</p>
    <p>Please add a twig template to <code>templates/Tastic/tastic/";
        // line 3
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["tastic"] ?? null), "tasticType", [], "any", false, false, false, 3), "html", null, true);
        echo ".html.twig</code>.</p>
</div>
";
    }

    public function getTemplateName()
    {
        return "@Tastic/missingTastic.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  44 => 3,  40 => 2,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "@Tastic/missingTastic.html.twig", "/var/www/frontastic/paas/catwalk/src/php/TwigTasticBundle/Resources/views/missingTastic.html.twig");
    }
}
