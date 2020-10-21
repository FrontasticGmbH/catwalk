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

/* TwigBundle::layout.html.twig */
class __TwigTemplate_3f829b469a162219ef9f3e13f69d4d2a7341b2517a5ca3b028bf5517f9a85f0c extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
            'before_html' => [$this, 'block_before_html'],
            'title' => [$this, 'block_title'],
            'head' => [$this, 'block_head'],
            'body' => [$this, 'block_body'],
            'after_html' => [$this, 'block_after_html'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        $__internal_f607aeef2c31a95a7bf963452dff024ffaeb6aafbe4603f9ca3bec57be8633f4 = (("The template \"" . $this->getTemplateName()) . "\" is deprecated since Symfony 4.4, will be removed in 5.0.");
        @trigger_error($__internal_f607aeef2c31a95a7bf963452dff024ffaeb6aafbe4603f9ca3bec57be8633f4." (\"TwigBundle::layout.html.twig\" at line 1).", E_USER_DEPRECATED);
        // line 2
        $this->displayBlock('before_html', $context, $blocks);
        // line 3
        echo "<!DOCTYPE html>
<html>
    <head>
        <meta charset=\"";
        // line 6
        echo twig_escape_filter($this->env, $this->env->getCharset(), "html", null, true);
        echo "\" />
        <meta name=\"robots\" content=\"noindex,nofollow\" />
        <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\" />
        <title>";
        // line 9
        $this->displayBlock('title', $context, $blocks);
        echo "</title>
        <link rel=\"icon\" type=\"image/png\" href=\"";
        // line 10
        echo twig_include($this->env, $context, "@Twig/images/favicon.png.base64");
        echo "\">
        <style>";
        // line 11
        echo twig_include($this->env, $context, "@Twig/exception.css.twig");
        echo "</style>
        ";
        // line 12
        $this->displayBlock('head', $context, $blocks);
        // line 13
        echo "    </head>
    <body>
        <header>
            <div class=\"container\">
                <h1 class=\"logo\">";
        // line 17
        echo twig_include($this->env, $context, "@Twig/images/symfony-logo.svg");
        echo " Symfony Exception</h1>

                <div class=\"help-link\">
                    <a href=\"https://symfony.com/doc/";
        // line 20
        echo twig_escape_filter($this->env, twig_constant("Symfony\\Component\\HttpKernel\\Kernel::VERSION"), "html", null, true);
        echo "/index.html\">
                        <span class=\"icon\">";
        // line 21
        echo twig_include($this->env, $context, "@Twig/images/icon-book.svg");
        echo "</span>
                        <span class=\"hidden-xs-down\">Symfony</span> Docs
                    </a>
                </div>

                <div class=\"help-link\">
                    <a href=\"https://symfony.com/support\">
                        <span class=\"icon\">";
        // line 28
        echo twig_include($this->env, $context, "@Twig/images/icon-support.svg");
        echo "</span>
                        <span class=\"hidden-xs-down\">Symfony</span> Support
                    </a>
                </div>
            </div>
        </header>

        ";
        // line 35
        $this->displayBlock('body', $context, $blocks);
        // line 36
        echo "        ";
        echo twig_include($this->env, $context, "@Twig/base_js.html.twig");
        echo "
    </body>
</html>
";
        // line 39
        $this->displayBlock('after_html', $context, $blocks);
    }

    // line 2
    public function block_before_html($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    // line 9
    public function block_title($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    // line 12
    public function block_head($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    // line 35
    public function block_body($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    // line 39
    public function block_after_html($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    public function getTemplateName()
    {
        return "TwigBundle::layout.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  145 => 39,  139 => 35,  133 => 12,  127 => 9,  121 => 2,  117 => 39,  110 => 36,  108 => 35,  98 => 28,  88 => 21,  84 => 20,  78 => 17,  72 => 13,  70 => 12,  66 => 11,  62 => 10,  58 => 9,  52 => 6,  47 => 3,  45 => 2,  42 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "TwigBundle::layout.html.twig", "/var/www/frontastic/paas/catwalk/vendor/symfony/symfony/src/Symfony/Bundle/TwigBundle/Resources/views/layout.html.twig");
    }
}
