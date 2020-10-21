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

/* @FrontasticCatwalkFrontendBundle/layout.html.twig */
class __TwigTemplate_91dc3c515418f2531d065b24a178c0511ebe1a596b72ec0aa6c45edd841981ef extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
            'meta' => [$this, 'block_meta'],
            'title' => [$this, 'block_title'],
            'main' => [$this, 'block_main'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<!doctype html>
<html lang=\"en\">
    <head>
        <meta charset=\"utf-8\">
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, shrink-to-fit=no\">

        <link rel=\"apple-touch-icon\" sizes=\"180x180\" href=\"/assets/apple-touch-icon.png\">
        <link rel=\"icon\" type=\"image/png\" sizes=\"32x32\" href=\"/assets/favicon-32x32.png\">
        <link rel=\"icon\" type=\"image/png\" sizes=\"16x16\" href=\"/assets/favicon-16x16.png\">
        <link rel=\"manifest\" href=\"/assets/manifest.json\">
        <link rel=\"mask-icon\" href=\"/assets/safari-pinned-tab.svg\" color=\"#5c3878\">
        <link rel=\"shortcut icon\" href=\"/assets/favicon.ico\">
        <meta name=\"msapplication-config\" content=\"/assets/browserconfig.xml\">
        <meta name=\"theme-color\" content=\"#5c3878\">
        ";
        // line 15
        $this->displayBlock('meta', $context, $blocks);
        // line 16
        echo "
    ";
        // line 17
        if ((twig_get_attribute($this->env, $this->source, ($context["app"] ?? null), "environment", [], "any", false, false, false, 17) == "prod")) {
            // line 18
            echo "        <link rel=\"stylesheet\" href=\"";
            echo twig_escape_filter($this->env, $this->extensions['Frontastic\Catwalk\FrontendBundle\Twig\AssetExtension']->updateHash("/assets/css/main.d3729d6c.css"), "html", null, true);
            echo "\"></link>
    ";
        }
        // line 20
        echo "
        ";
        // line 21
        $this->displayBlock('title', $context, $blocks);
        // line 22
        echo "    </head>
    <body>
    ";
        // line 24
        $this->displayBlock('main', $context, $blocks);
        // line 30
        echo "    ";
        if ((twig_get_attribute($this->env, $this->source, ($context["app"] ?? null), "environment", [], "any", false, false, false, 30) == "dev")) {
            // line 31
            echo "        <script type=\"text/javascript\" src=\"/webpack/js/bundle.js\"></script>
    ";
        } else {
            // line 33
            echo "        <script type=\"text/javascript\" src=\"";
            echo twig_escape_filter($this->env, $this->extensions['Frontastic\Catwalk\FrontendBundle\Twig\AssetExtension']->updateHash("/assets/js/main.d3729d6c.js"), "html", null, true);
            echo "\"></script>
    ";
        }
        // line 35
        echo "    </body>
</html>
";
    }

    // line 15
    public function block_meta($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    // line 21
    public function block_title($context, array $blocks = [])
    {
        $macros = $this->macros;
        echo "<title>Frontastic Online Shop</title>";
    }

    // line 24
    public function block_main($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 25
        echo "        <noscript>
            You need to enable JavaScript to run this application.
        </noscript>
        <div id=\"root\"></div>
    ";
    }

    public function getTemplateName()
    {
        return "@FrontasticCatwalkFrontendBundle/layout.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  116 => 25,  112 => 24,  105 => 21,  99 => 15,  93 => 35,  87 => 33,  83 => 31,  80 => 30,  78 => 24,  74 => 22,  72 => 21,  69 => 20,  63 => 18,  61 => 17,  58 => 16,  56 => 15,  40 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "@FrontasticCatwalkFrontendBundle/layout.html.twig", "/var/www/frontastic/paas/catwalk/templates/layout.html.twig");
    }
}
