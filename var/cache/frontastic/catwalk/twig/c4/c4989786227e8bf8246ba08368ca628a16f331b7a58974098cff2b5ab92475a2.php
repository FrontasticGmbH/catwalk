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

/* TwigBundle:Exception:traces.html.twig */
class __TwigTemplate_f513f7a04a6640f4f6138685f8e0aeab41b152f3d3e5cbb9626283e4c18f6a6c extends \Twig\Template
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
        echo "<div class=\"trace trace-as-html\" id=\"trace-box-";
        echo twig_escape_filter($this->env, ($context["index"] ?? null), "html", null, true);
        echo "\">
    <div class=\"trace-details\">
        <div class=\"trace-head\">
            <span class=\"sf-toggle\" data-toggle-selector=\"#trace-html-";
        // line 4
        echo twig_escape_filter($this->env, ($context["index"] ?? null), "html", null, true);
        echo "\" data-toggle-initial=\"";
        echo ((($context["expand"] ?? null)) ? ("display") : (""));
        echo "\">
                <h3 class=\"trace-class\">
                    <span class=\"icon icon-close\">";
        // line 6
        echo twig_include($this->env, $context, "@Twig/images/icon-minus-square-o.svg");
        echo "</span>
                    <span class=\"icon icon-open\">";
        // line 7
        echo twig_include($this->env, $context, "@Twig/images/icon-plus-square-o.svg");
        echo "</span>

                    <span class=\"trace-namespace\">
                        ";
        // line 10
        echo twig_escape_filter($this->env, twig_join_filter(twig_slice($this->env, twig_split_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["exception"] ?? null), "class", [], "any", false, false, false, 10), "\\"), 0,  -1), "\\"), "html", null, true);
        // line 11
        echo (((twig_length_filter($this->env, twig_split_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["exception"] ?? null), "class", [], "any", false, false, false, 11), "\\")) > 1)) ? ("\\") : (""));
        echo "
                    </span>
                    ";
        // line 13
        echo twig_escape_filter($this->env, twig_last($this->env, twig_split_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["exception"] ?? null), "class", [], "any", false, false, false, 13), "\\")), "html", null, true);
        echo "
                </h3>

                ";
        // line 16
        if (( !twig_test_empty(twig_get_attribute($this->env, $this->source, ($context["exception"] ?? null), "message", [], "any", false, false, false, 16)) && (($context["index"] ?? null) > 1))) {
            // line 17
            echo "                    <p class=\"break-long-words trace-message\">";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["exception"] ?? null), "message", [], "any", false, false, false, 17), "html", null, true);
            echo "</p>
                ";
        }
        // line 19
        echo "            </span>
        </div>

        <div id=\"trace-html-";
        // line 22
        echo twig_escape_filter($this->env, ($context["index"] ?? null), "html", null, true);
        echo "\" class=\"sf-toggle-content\">
        ";
        // line 23
        $context["_is_first_user_code"] = true;
        // line 24
        echo "        ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, ($context["exception"] ?? null), "trace", [], "any", false, false, false, 24));
        foreach ($context['_seq'] as $context["i"] => $context["trace"]) {
            // line 25
            echo "            ";
            $context["_is_vendor_trace"] = ( !twig_test_empty(twig_get_attribute($this->env, $this->source, $context["trace"], "file", [], "any", false, false, false, 25)) && (twig_in_filter("/vendor/", twig_get_attribute($this->env, $this->source, $context["trace"], "file", [], "any", false, false, false, 25)) || twig_in_filter("/var/cache/", twig_get_attribute($this->env, $this->source, $context["trace"], "file", [], "any", false, false, false, 25))));
            // line 26
            echo "            ";
            $context["_display_code_snippet"] = (($context["_is_first_user_code"] ?? null) &&  !($context["_is_vendor_trace"] ?? null));
            // line 27
            echo "            ";
            if (($context["_display_code_snippet"] ?? null)) {
                $context["_is_first_user_code"] = false;
            }
            // line 28
            echo "            <div class=\"trace-line ";
            echo ((($context["_is_vendor_trace"] ?? null)) ? ("trace-from-vendor") : (""));
            echo "\">
                ";
            // line 29
            echo twig_include($this->env, $context, "@Twig/Exception/trace.html.twig", ["prefix" => ($context["index"] ?? null), "i" => $context["i"], "trace" => $context["trace"], "style" => ((($context["_is_vendor_trace"] ?? null)) ? ("compact") : (((($context["_display_code_snippet"] ?? null)) ? ("expanded") : (""))))], false);
            echo "
            </div>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['i'], $context['trace'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 32
        echo "        </div>
    </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "TwigBundle:Exception:traces.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  123 => 32,  114 => 29,  109 => 28,  104 => 27,  101 => 26,  98 => 25,  93 => 24,  91 => 23,  87 => 22,  82 => 19,  76 => 17,  74 => 16,  68 => 13,  63 => 11,  61 => 10,  55 => 7,  51 => 6,  44 => 4,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "TwigBundle:Exception:traces.html.twig", "/var/www/frontastic/paas/catwalk/vendor/symfony/symfony/src/Symfony/Bundle/TwigBundle/Resources/views/Exception/traces.html.twig");
    }
}
