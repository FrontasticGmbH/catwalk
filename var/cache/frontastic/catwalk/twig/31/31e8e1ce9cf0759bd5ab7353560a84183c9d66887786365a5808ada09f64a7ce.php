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

/* FrontasticCatwalkFrontendBundle::frontastic_render.html.twig */
class __TwigTemplate_a3a3eecab50f740cf2c0a4ad70c0b729ca40896ade2507319e92c282415789ea extends \Twig\Template
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
        ob_start(function () { return ''; });
        // line 3
        echo "    ";
        $context["context"] = $this->extensions['Frontastic\Catwalk\FrontendBundle\Twig\ContextExtension']->getContext();
        // line 4
        echo "    ";
        if ((((twig_get_attribute($this->env, $this->source, ($context["response"] ?? null), "status", [], "any", false, false, false, 4) >= 400) && (twig_get_attribute($this->env, $this->source, ($context["context"] ?? null), "environment", [], "any", false, false, false, 4) != "prod")) && (twig_get_attribute($this->env, $this->source, ($context["context"] ?? null), "environment", [], "any", false, false, false, 4) != "production"))) {
            // line 5
            echo "    <script type=\"text/javascript\">
        console.warn(\"Server Side Rendering failed\", ";
            // line 6
            echo json_encode(($context["response"] ?? null));
            echo ")
    </script>
    <div id=\"frssrw\" style=\"position: absolute; top: 10px; left: calc(50% - 250px); width: 500px; border: 3px solid #654b8b; background-color: #fff; color: #000; z-index: 2147483647; padding: 10px; box-shadow: 10px 10px 5px 0px rgba(0,0,0,0.75);\">
        <button
            style=\"float: right; margin: 5px; border: 1px solid #ccc; padding: 5px; background-color: #eee; color: #000;\"
            onClick=\"document.getElementById('frssrw').setAttribute('style', 'display: none')\">
            ❌
        </button>
        <h1 style=\"font-weight: bold; font-size: 24px; margin: 0px 0px 10px 0px;\">⚠ Server Side Rendering Failed</h1>
        <p style=\"font-size: 14px; margin: 0px 0px 10px 0px;\">Code ";
            // line 15
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["response"] ?? null), "status", [], "any", false, false, false, 15), "html", null, true);
            echo ", reason:</p>
        <p style=\"font-size: 12px; font-family: monospace; margin: 0px 0px 10px 0px;\">";
            // line 16
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["response"] ?? null), "body", [], "any", false, false, false, 16), "app", [], "any", false, false, false, 16), "html", null, true);
            echo "</p>
        <p style=\"font-size: 12px; margin: 0px 0px 10px 0px;\">
            Ensure the service s for this project are started (<code style=\"font-family: monospace;\">sudo supervisorctl restart ";
            // line 18
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["context"] ?? null), "customer", [], "any", false, false, false, 18), "name", [], "any", false, false, false, 18), "html", null, true);
            echo "-";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["context"] ?? null), "project", [], "any", false, false, false, 18), "projectId", [], "any", false, false, false, 18), "html", null, true);
            echo ":</code>) and check the log at <code style=\"font-family: monospace;\">/var/log/frontastic/";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["context"] ?? null), "customer", [], "any", false, false, false, 18), "name", [], "any", false, false, false, 18), "html", null, true);
            echo "_";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["context"] ?? null), "project", [], "any", false, false, false, 18), "projectId", [], "any", false, false, false, 18), "html", null, true);
            echo "/ssr.log</code> for more details.
        </p>
    </div>
    ";
        }
        // line 22
        echo "    <div id=\"app\">
        ";
        // line 23
        if ((twig_get_attribute($this->env, $this->source, ($context["response"] ?? null), "status", [], "any", false, false, false, 23) < 400)) {
            echo twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["response"] ?? null), "body", [], "any", false, false, false, 23), "app", [], "any", false, false, false, 23);
        }
        // line 24
        echo "    </div>
    <div id=\"appData\"
         data-props=\"";
        // line 26
        echo twig_escape_filter($this->env, json_encode(call_user_func_array($this->env->getFilter('frontastic_json_serialize')->getCallable(), [($context["pageData"] ?? null)])), "html", null, true);
        echo "\"
         data-user-agent=\"";
        // line 27
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["app"] ?? null), "request", [], "any", false, false, false, 27), "headers", [], "any", false, false, false, 27), "get", [0 => "User-Agent"], "method", false, false, false, 27), "html", null, true);
        echo "\"
     />
";
        echo trim(preg_replace('/>\s+</', '><', ob_get_clean()));
    }

    public function getTemplateName()
    {
        return "FrontasticCatwalkFrontendBundle::frontastic_render.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  97 => 27,  93 => 26,  89 => 24,  85 => 23,  82 => 22,  69 => 18,  64 => 16,  60 => 15,  48 => 6,  45 => 5,  42 => 4,  39 => 3,  37 => 2,);
    }

    public function getSourceContext()
    {
        return new Source("", "FrontasticCatwalkFrontendBundle::frontastic_render.html.twig", "/var/www/frontastic/paas/catwalk/src/php/FrontendBundle/Resources/views/frontastic_render.html.twig");
    }
}
