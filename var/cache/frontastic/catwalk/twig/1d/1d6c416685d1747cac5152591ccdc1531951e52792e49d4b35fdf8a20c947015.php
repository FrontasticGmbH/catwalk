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

/* @Tastic/components/image.html.twig */
class __TwigTemplate_5401986de2c88097d84dbf8d05d35ec33261548bd8a3d5be8de45dbc3d5c90b1 extends \Twig\Template
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
        // line 2
        echo "<img
    class=\"c-image\"
    loading='lazy'
    alt=";
        // line 5
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["image"] ?? null), "name", [], "any", false, false, false, 5), "html", null, true);
        echo "
    src=\"";
        // line 6
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["image"] ?? null), "file", [], "any", false, false, false, 6), "html", null, true);
        echo "\"
/>
";
    }

    public function getTemplateName()
    {
        return "@Tastic/components/image.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  46 => 6,  42 => 5,  37 => 2,);
    }

    public function getSourceContext()
    {
        return new Source("", "@Tastic/components/image.html.twig", "/var/www/frontastic/paas/catwalk/src/php/TwigTasticBundle/Resources/views/components/image.html.twig");
    }
}
