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

/* form_table_layout.html.twig */
class __TwigTemplate_e2cab8aaeff3a34c32de7514fc53674ac46b41a9a77cde9c8870d85e11f203ff extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        // line 1
        $_trait_0 = $this->loadTemplate("form_div_layout.html.twig", "form_table_layout.html.twig", 1);
        if (!$_trait_0->isTraitable()) {
            throw new RuntimeError('Template "'."form_div_layout.html.twig".'" cannot be used as a trait.', 1, $this->source);
        }
        $_trait_0_blocks = $_trait_0->getBlocks();

        $this->traits = $_trait_0_blocks;

        $this->blocks = array_merge(
            $this->traits,
            [
                'form_row' => [$this, 'block_form_row'],
                'button_row' => [$this, 'block_button_row'],
                'hidden_row' => [$this, 'block_hidden_row'],
                'form_widget_compound' => [$this, 'block_form_widget_compound'],
            ]
        );
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 3
        $this->displayBlock('form_row', $context, $blocks);
        // line 20
        $this->displayBlock('button_row', $context, $blocks);
        // line 29
        $this->displayBlock('hidden_row', $context, $blocks);
        // line 38
        $this->displayBlock('form_widget_compound', $context, $blocks);
    }

    // line 3
    public function block_form_row($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 4
        $context["widget_attr"] = [];
        // line 5
        if ( !twig_test_empty(($context["help"] ?? null))) {
            // line 6
            $context["widget_attr"] = ["attr" => ["aria-describedby" => (($context["id"] ?? null) . "_help")]];
        }
        // line 8
        echo "<tr";
        $__internal_f607aeef2c31a95a7bf963452dff024ffaeb6aafbe4603f9ca3bec57be8633f4 = ["attr" => ($context["row_attr"] ?? null)];
        if (!twig_test_iterable($__internal_f607aeef2c31a95a7bf963452dff024ffaeb6aafbe4603f9ca3bec57be8633f4)) {
            throw new RuntimeError('Variables passed to the "with" tag must be a hash.', 8, $this->getSourceContext());
        }
        $__internal_f607aeef2c31a95a7bf963452dff024ffaeb6aafbe4603f9ca3bec57be8633f4 = twig_to_array($__internal_f607aeef2c31a95a7bf963452dff024ffaeb6aafbe4603f9ca3bec57be8633f4);
        $context['_parent'] = $context;
        $context = $this->env->mergeGlobals(array_merge($context, $__internal_f607aeef2c31a95a7bf963452dff024ffaeb6aafbe4603f9ca3bec57be8633f4));
        $this->displayBlock("attributes", $context, $blocks);
        $context = $context['_parent'];
        echo ">
        <td>";
        // line 10
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(($context["form"] ?? null), 'label');
        // line 11
        echo "</td>
        <td>";
        // line 13
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(($context["form"] ?? null), 'errors');
        // line 14
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(($context["form"] ?? null), 'widget', ($context["widget_attr"] ?? null));
        // line 15
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(($context["form"] ?? null), 'help');
        // line 16
        echo "</td>
    </tr>";
    }

    // line 20
    public function block_button_row($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 21
        echo "<tr";
        $__internal_62824350bc4502ee19dbc2e99fc6bdd3bd90e7d8dd6e72f42c35efd048542144 = ["attr" => ($context["row_attr"] ?? null)];
        if (!twig_test_iterable($__internal_62824350bc4502ee19dbc2e99fc6bdd3bd90e7d8dd6e72f42c35efd048542144)) {
            throw new RuntimeError('Variables passed to the "with" tag must be a hash.', 21, $this->getSourceContext());
        }
        $__internal_62824350bc4502ee19dbc2e99fc6bdd3bd90e7d8dd6e72f42c35efd048542144 = twig_to_array($__internal_62824350bc4502ee19dbc2e99fc6bdd3bd90e7d8dd6e72f42c35efd048542144);
        $context['_parent'] = $context;
        $context = $this->env->mergeGlobals(array_merge($context, $__internal_62824350bc4502ee19dbc2e99fc6bdd3bd90e7d8dd6e72f42c35efd048542144));
        $this->displayBlock("attributes", $context, $blocks);
        $context = $context['_parent'];
        echo ">
        <td></td>
        <td>";
        // line 24
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(($context["form"] ?? null), 'widget');
        // line 25
        echo "</td>
    </tr>";
    }

    // line 29
    public function block_hidden_row($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 30
        $context["style"] = ((twig_get_attribute($this->env, $this->source, ($context["row_attr"] ?? null), "style", [], "any", true, true, false, 30)) ? ((twig_get_attribute($this->env, $this->source, ($context["row_attr"] ?? null), "style", [], "any", false, false, false, 30) . (((twig_last($this->env, twig_trim_filter(twig_get_attribute($this->env, $this->source, ($context["row_attr"] ?? null), "style", [], "any", false, false, false, 30))) != ";")) ? ("; ") : ("")))) : (""));
        // line 31
        echo "<tr";
        $__internal_1cfccaec8dd2e8578ccb026fbe7f2e7e29ac2ed5deb976639c5fc99a6ea8583b = ["attr" => twig_array_merge(($context["row_attr"] ?? null), ["style" => twig_trim_filter((($context["style"] ?? null) . " display: none"))])];
        if (!twig_test_iterable($__internal_1cfccaec8dd2e8578ccb026fbe7f2e7e29ac2ed5deb976639c5fc99a6ea8583b)) {
            throw new RuntimeError('Variables passed to the "with" tag must be a hash.', 31, $this->getSourceContext());
        }
        $__internal_1cfccaec8dd2e8578ccb026fbe7f2e7e29ac2ed5deb976639c5fc99a6ea8583b = twig_to_array($__internal_1cfccaec8dd2e8578ccb026fbe7f2e7e29ac2ed5deb976639c5fc99a6ea8583b);
        $context['_parent'] = $context;
        $context = $this->env->mergeGlobals(array_merge($context, $__internal_1cfccaec8dd2e8578ccb026fbe7f2e7e29ac2ed5deb976639c5fc99a6ea8583b));
        $this->displayBlock("attributes", $context, $blocks);
        $context = $context['_parent'];
        echo ">
        <td colspan=\"2\">";
        // line 33
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(($context["form"] ?? null), 'widget');
        // line 34
        echo "</td>
    </tr>";
    }

    // line 38
    public function block_form_widget_compound($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 39
        echo "<table ";
        $this->displayBlock("widget_container_attributes", $context, $blocks);
        echo ">";
        // line 40
        if ((Symfony\Bridge\Twig\Extension\twig_is_root_form(($context["form"] ?? null)) && (twig_length_filter($this->env, ($context["errors"] ?? null)) > 0))) {
            // line 41
            echo "<tr>
            <td colspan=\"2\">";
            // line 43
            echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(($context["form"] ?? null), 'errors');
            // line 44
            echo "</td>
        </tr>";
        }
        // line 47
        $this->displayBlock("form_rows", $context, $blocks);
        // line 48
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(($context["form"] ?? null), 'rest');
        // line 49
        echo "</table>";
    }

    public function getTemplateName()
    {
        return "form_table_layout.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  177 => 49,  175 => 48,  173 => 47,  169 => 44,  167 => 43,  164 => 41,  162 => 40,  158 => 39,  154 => 38,  149 => 34,  147 => 33,  134 => 31,  132 => 30,  128 => 29,  123 => 25,  121 => 24,  107 => 21,  103 => 20,  98 => 16,  96 => 15,  94 => 14,  92 => 13,  89 => 11,  87 => 10,  74 => 8,  71 => 6,  69 => 5,  67 => 4,  63 => 3,  59 => 38,  57 => 29,  55 => 20,  53 => 3,  30 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "form_table_layout.html.twig", "/var/www/frontastic/paas/catwalk/vendor/symfony/symfony/src/Symfony/Bridge/Twig/Resources/views/Form/form_table_layout.html.twig");
    }
}
