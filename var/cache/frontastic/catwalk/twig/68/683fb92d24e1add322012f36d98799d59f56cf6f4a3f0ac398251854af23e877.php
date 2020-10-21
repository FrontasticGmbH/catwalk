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

/* bootstrap_4_layout.html.twig */
class __TwigTemplate_39d415aa7267b23c88e2ffc96c8252d35e9fd190dd845ae23732b6f62582b41f extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        // line 1
        $_trait_0 = $this->loadTemplate("bootstrap_base_layout.html.twig", "bootstrap_4_layout.html.twig", 1);
        if (!$_trait_0->isTraitable()) {
            throw new RuntimeError('Template "'."bootstrap_base_layout.html.twig".'" cannot be used as a trait.', 1, $this->source);
        }
        $_trait_0_blocks = $_trait_0->getBlocks();

        $this->traits = $_trait_0_blocks;

        $this->blocks = array_merge(
            $this->traits,
            [
                'money_widget' => [$this, 'block_money_widget'],
                'datetime_widget' => [$this, 'block_datetime_widget'],
                'date_widget' => [$this, 'block_date_widget'],
                'time_widget' => [$this, 'block_time_widget'],
                'dateinterval_widget' => [$this, 'block_dateinterval_widget'],
                'percent_widget' => [$this, 'block_percent_widget'],
                'file_widget' => [$this, 'block_file_widget'],
                'form_widget_simple' => [$this, 'block_form_widget_simple'],
                'widget_attributes' => [$this, 'block_widget_attributes'],
                'button_widget' => [$this, 'block_button_widget'],
                'submit_widget' => [$this, 'block_submit_widget'],
                'checkbox_widget' => [$this, 'block_checkbox_widget'],
                'radio_widget' => [$this, 'block_radio_widget'],
                'choice_widget_expanded' => [$this, 'block_choice_widget_expanded'],
                'form_label' => [$this, 'block_form_label'],
                'form_label_errors' => [$this, 'block_form_label_errors'],
                'checkbox_radio_label' => [$this, 'block_checkbox_radio_label'],
                'form_row' => [$this, 'block_form_row'],
                'form_errors' => [$this, 'block_form_errors'],
                'form_help' => [$this, 'block_form_help'],
            ]
        );
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 2
        echo "
";
        // line 4
        echo "
";
        // line 5
        $this->displayBlock('money_widget', $context, $blocks);
        // line 26
        echo "
";
        // line 27
        $this->displayBlock('datetime_widget', $context, $blocks);
        // line 34
        echo "
";
        // line 35
        $this->displayBlock('date_widget', $context, $blocks);
        // line 42
        echo "
";
        // line 43
        $this->displayBlock('time_widget', $context, $blocks);
        // line 50
        echo "
";
        // line 51
        $this->displayBlock('dateinterval_widget', $context, $blocks);
        // line 107
        echo "
";
        // line 108
        $this->displayBlock('percent_widget', $context, $blocks);
        // line 120
        echo "
";
        // line 121
        $this->displayBlock('file_widget', $context, $blocks);
        // line 133
        echo "
";
        // line 134
        $this->displayBlock('form_widget_simple', $context, $blocks);
        // line 145
        $this->displayBlock('widget_attributes', $context, $blocks);
        // line 152
        $this->displayBlock('button_widget', $context, $blocks);
        // line 156
        echo "
";
        // line 157
        $this->displayBlock('submit_widget', $context, $blocks);
        // line 161
        echo "
";
        // line 162
        $this->displayBlock('checkbox_widget', $context, $blocks);
        // line 181
        echo "
";
        // line 182
        $this->displayBlock('radio_widget', $context, $blocks);
        // line 196
        echo "
";
        // line 197
        $this->displayBlock('choice_widget_expanded', $context, $blocks);
        // line 208
        echo "
";
        // line 210
        echo "
";
        // line 211
        $this->displayBlock('form_label', $context, $blocks);
        // line 241
        echo "
";
        // line 242
        $this->displayBlock('checkbox_radio_label', $context, $blocks);
        // line 279
        echo "
";
        // line 281
        echo "
";
        // line 282
        $this->displayBlock('form_row', $context, $blocks);
        // line 296
        echo "
";
        // line 298
        echo "
";
        // line 299
        $this->displayBlock('form_errors', $context, $blocks);
        // line 310
        echo "
";
        // line 312
        echo "
";
        // line 313
        $this->displayBlock('form_help', $context, $blocks);
    }

    // line 5
    public function block_money_widget($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 6
        $context["prepend"] =  !(is_string($__internal_f607aeef2c31a95a7bf963452dff024ffaeb6aafbe4603f9ca3bec57be8633f4 = ($context["money_pattern"] ?? null)) && is_string($__internal_62824350bc4502ee19dbc2e99fc6bdd3bd90e7d8dd6e72f42c35efd048542144 = "{{") && ('' === $__internal_62824350bc4502ee19dbc2e99fc6bdd3bd90e7d8dd6e72f42c35efd048542144 || 0 === strpos($__internal_f607aeef2c31a95a7bf963452dff024ffaeb6aafbe4603f9ca3bec57be8633f4, $__internal_62824350bc4502ee19dbc2e99fc6bdd3bd90e7d8dd6e72f42c35efd048542144)));
        // line 7
        $context["append"] =  !(is_string($__internal_1cfccaec8dd2e8578ccb026fbe7f2e7e29ac2ed5deb976639c5fc99a6ea8583b = ($context["money_pattern"] ?? null)) && is_string($__internal_68aa442c1d43d3410ea8f958ba9090f3eaa9a76f8de8fc9be4d6c7389ba28002 = "}}") && ('' === $__internal_68aa442c1d43d3410ea8f958ba9090f3eaa9a76f8de8fc9be4d6c7389ba28002 || $__internal_68aa442c1d43d3410ea8f958ba9090f3eaa9a76f8de8fc9be4d6c7389ba28002 === substr($__internal_1cfccaec8dd2e8578ccb026fbe7f2e7e29ac2ed5deb976639c5fc99a6ea8583b, -strlen($__internal_68aa442c1d43d3410ea8f958ba9090f3eaa9a76f8de8fc9be4d6c7389ba28002))));
        // line 8
        if ((($context["prepend"] ?? null) || ($context["append"] ?? null))) {
            // line 9
            echo "<div class=\"input-group";
            echo twig_escape_filter($this->env, (((isset($context["group_class"]) || array_key_exists("group_class", $context))) ? (_twig_default_filter(($context["group_class"] ?? null), "")) : ("")), "html", null, true);
            echo "\">";
            // line 10
            if (($context["prepend"] ?? null)) {
                // line 11
                echo "<div class=\"input-group-prepend\">
                    <span class=\"input-group-text\">";
                // line 12
                echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->encodeCurrency($this->env, ($context["money_pattern"] ?? null));
                echo "</span>
                </div>";
            }
            // line 15
            $this->displayBlock("form_widget_simple", $context, $blocks);
            // line 16
            if (($context["append"] ?? null)) {
                // line 17
                echo "<div class=\"input-group-append\">
                    <span class=\"input-group-text\">";
                // line 18
                echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->encodeCurrency($this->env, ($context["money_pattern"] ?? null));
                echo "</span>
                </div>";
            }
            // line 21
            echo "</div>";
        } else {
            // line 23
            $this->displayBlock("form_widget_simple", $context, $blocks);
        }
    }

    // line 27
    public function block_datetime_widget($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 28
        if (((($context["widget"] ?? null) != "single_text") &&  !($context["valid"] ?? null))) {
            // line 29
            $context["attr"] = twig_array_merge(($context["attr"] ?? null), ["class" => twig_trim_filter((((twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", true, true, false, 29)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", false, false, false, 29), "")) : ("")) . " form-control is-invalid"))]);
            // line 30
            $context["valid"] = true;
        }
        // line 32
        $this->displayParentBlock("datetime_widget", $context, $blocks);
    }

    // line 35
    public function block_date_widget($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 36
        if (((($context["widget"] ?? null) != "single_text") &&  !($context["valid"] ?? null))) {
            // line 37
            $context["attr"] = twig_array_merge(($context["attr"] ?? null), ["class" => twig_trim_filter((((twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", true, true, false, 37)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", false, false, false, 37), "")) : ("")) . " form-control is-invalid"))]);
            // line 38
            $context["valid"] = true;
        }
        // line 40
        $this->displayParentBlock("date_widget", $context, $blocks);
    }

    // line 43
    public function block_time_widget($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 44
        if (((($context["widget"] ?? null) != "single_text") &&  !($context["valid"] ?? null))) {
            // line 45
            $context["attr"] = twig_array_merge(($context["attr"] ?? null), ["class" => twig_trim_filter((((twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", true, true, false, 45)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", false, false, false, 45), "")) : ("")) . " form-control is-invalid"))]);
            // line 46
            $context["valid"] = true;
        }
        // line 48
        $this->displayParentBlock("time_widget", $context, $blocks);
    }

    // line 51
    public function block_dateinterval_widget($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 52
        if (((($context["widget"] ?? null) != "single_text") &&  !($context["valid"] ?? null))) {
            // line 53
            $context["attr"] = twig_array_merge(($context["attr"] ?? null), ["class" => twig_trim_filter((((twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", true, true, false, 53)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", false, false, false, 53), "")) : ("")) . " form-control is-invalid"))]);
            // line 54
            $context["valid"] = true;
        }
        // line 56
        if ((($context["widget"] ?? null) == "single_text")) {
            // line 57
            $this->displayBlock("form_widget_simple", $context, $blocks);
        } else {
            // line 59
            $context["attr"] = twig_array_merge(($context["attr"] ?? null), ["class" => twig_trim_filter((((twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", true, true, false, 59)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", false, false, false, 59), "")) : ("")) . " form-inline"))]);
            // line 60
            echo "<div ";
            $this->displayBlock("widget_container_attributes", $context, $blocks);
            echo ">";
            // line 61
            if (($context["with_years"] ?? null)) {
                // line 62
                echo "<div class=\"col-auto\">
                ";
                // line 63
                echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, ($context["form"] ?? null), "years", [], "any", false, false, false, 63), 'label');
                echo "
                ";
                // line 64
                echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, ($context["form"] ?? null), "years", [], "any", false, false, false, 64), 'widget');
                echo "
            </div>";
            }
            // line 67
            if (($context["with_months"] ?? null)) {
                // line 68
                echo "<div class=\"col-auto\">
                ";
                // line 69
                echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, ($context["form"] ?? null), "months", [], "any", false, false, false, 69), 'label');
                echo "
                ";
                // line 70
                echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, ($context["form"] ?? null), "months", [], "any", false, false, false, 70), 'widget');
                echo "
            </div>";
            }
            // line 73
            if (($context["with_weeks"] ?? null)) {
                // line 74
                echo "<div class=\"col-auto\">
                ";
                // line 75
                echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, ($context["form"] ?? null), "weeks", [], "any", false, false, false, 75), 'label');
                echo "
                ";
                // line 76
                echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, ($context["form"] ?? null), "weeks", [], "any", false, false, false, 76), 'widget');
                echo "
            </div>";
            }
            // line 79
            if (($context["with_days"] ?? null)) {
                // line 80
                echo "<div class=\"col-auto\">
                ";
                // line 81
                echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, ($context["form"] ?? null), "days", [], "any", false, false, false, 81), 'label');
                echo "
                ";
                // line 82
                echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, ($context["form"] ?? null), "days", [], "any", false, false, false, 82), 'widget');
                echo "
            </div>";
            }
            // line 85
            if (($context["with_hours"] ?? null)) {
                // line 86
                echo "<div class=\"col-auto\">
                ";
                // line 87
                echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, ($context["form"] ?? null), "hours", [], "any", false, false, false, 87), 'label');
                echo "
                ";
                // line 88
                echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, ($context["form"] ?? null), "hours", [], "any", false, false, false, 88), 'widget');
                echo "
            </div>";
            }
            // line 91
            if (($context["with_minutes"] ?? null)) {
                // line 92
                echo "<div class=\"col-auto\">
                ";
                // line 93
                echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, ($context["form"] ?? null), "minutes", [], "any", false, false, false, 93), 'label');
                echo "
                ";
                // line 94
                echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, ($context["form"] ?? null), "minutes", [], "any", false, false, false, 94), 'widget');
                echo "
            </div>";
            }
            // line 97
            if (($context["with_seconds"] ?? null)) {
                // line 98
                echo "<div class=\"col-auto\">
                ";
                // line 99
                echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, ($context["form"] ?? null), "seconds", [], "any", false, false, false, 99), 'label');
                echo "
                ";
                // line 100
                echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, ($context["form"] ?? null), "seconds", [], "any", false, false, false, 100), 'widget');
                echo "
            </div>";
            }
            // line 103
            if (($context["with_invert"] ?? null)) {
                echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, ($context["form"] ?? null), "invert", [], "any", false, false, false, 103), 'widget');
            }
            // line 104
            echo "</div>";
        }
    }

    // line 108
    public function block_percent_widget($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 109
        if (($context["symbol"] ?? null)) {
            // line 110
            echo "<div class=\"input-group\">";
            // line 111
            $this->displayBlock("form_widget_simple", $context, $blocks);
            // line 112
            echo "<div class=\"input-group-append\">
                <span class=\"input-group-text\">";
            // line 113
            echo twig_escape_filter($this->env, (((isset($context["symbol"]) || array_key_exists("symbol", $context))) ? (_twig_default_filter(($context["symbol"] ?? null), "%")) : ("%")), "html", null, true);
            echo "</span>
            </div>
        </div>";
        } else {
            // line 117
            $this->displayBlock("form_widget_simple", $context, $blocks);
        }
    }

    // line 121
    public function block_file_widget($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 122
        echo "<";
        echo twig_escape_filter($this->env, (((isset($context["element"]) || array_key_exists("element", $context))) ? (_twig_default_filter(($context["element"] ?? null), "div")) : ("div")), "html", null, true);
        echo " class=\"custom-file\">";
        // line 123
        $context["type"] = (((isset($context["type"]) || array_key_exists("type", $context))) ? (_twig_default_filter(($context["type"] ?? null), "file")) : ("file"));
        // line 124
        $this->displayBlock("form_widget_simple", $context, $blocks);
        // line 125
        $context["label_attr"] = twig_array_merge(($context["label_attr"] ?? null), ["class" => twig_trim_filter((((twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", true, true, false, 125)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", false, false, false, 125), "")) : ("")) . " custom-file-label"))]);
        // line 126
        echo "<label for=\"";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["form"] ?? null), "vars", [], "any", false, false, false, 126), "id", [], "any", false, false, false, 126), "html", null, true);
        echo "\" ";
        $__internal_d7fc55f1a54b629533d60b43063289db62e68921ee7a5f8de562bd9d4a2b7ad4 = ["attr" => ($context["label_attr"] ?? null)];
        if (!twig_test_iterable($__internal_d7fc55f1a54b629533d60b43063289db62e68921ee7a5f8de562bd9d4a2b7ad4)) {
            throw new RuntimeError('Variables passed to the "with" tag must be a hash.', 126, $this->getSourceContext());
        }
        $__internal_d7fc55f1a54b629533d60b43063289db62e68921ee7a5f8de562bd9d4a2b7ad4 = twig_to_array($__internal_d7fc55f1a54b629533d60b43063289db62e68921ee7a5f8de562bd9d4a2b7ad4);
        $context['_parent'] = $context;
        $context = $this->env->mergeGlobals(array_merge($context, $__internal_d7fc55f1a54b629533d60b43063289db62e68921ee7a5f8de562bd9d4a2b7ad4));
        $this->displayBlock("attributes", $context, $blocks);
        $context = $context['_parent'];
        echo ">";
        // line 127
        if ((twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "placeholder", [], "any", true, true, false, 127) &&  !(null === twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "placeholder", [], "any", false, false, false, 127)))) {
            // line 128
            echo twig_escape_filter($this->env, (((($context["translation_domain"] ?? null) === false)) ? (twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "placeholder", [], "any", false, false, false, 128)) : ($this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans(twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "placeholder", [], "any", false, false, false, 128), [], ($context["translation_domain"] ?? null)))), "html", null, true);
        }
        // line 130
        echo "</label>
    </";
        // line 131
        echo twig_escape_filter($this->env, (((isset($context["element"]) || array_key_exists("element", $context))) ? (_twig_default_filter(($context["element"] ?? null), "div")) : ("div")), "html", null, true);
        echo ">
";
    }

    // line 134
    public function block_form_widget_simple($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 135
        if (( !(isset($context["type"]) || array_key_exists("type", $context)) || (($context["type"] ?? null) != "hidden"))) {
            // line 136
            $context["attr"] = twig_array_merge(($context["attr"] ?? null), ["class" => twig_trim_filter((((twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", true, true, false, 136)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", false, false, false, 136), "")) : ("")) . ((((((isset($context["type"]) || array_key_exists("type", $context))) ? (_twig_default_filter(($context["type"] ?? null), "")) : ("")) == "file")) ? (" custom-file-input") : (" form-control"))))]);
        }
        // line 138
        if (((isset($context["type"]) || array_key_exists("type", $context)) && ((($context["type"] ?? null) == "range") || (($context["type"] ?? null) == "color")))) {
            // line 139
            echo "        ";
            // line 140
            $context["required"] = false;
        }
        // line 142
        $this->displayParentBlock("form_widget_simple", $context, $blocks);
    }

    // line 145
    public function block_widget_attributes($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 146
        if ( !($context["valid"] ?? null)) {
            // line 147
            echo "        ";
            $context["attr"] = twig_array_merge(($context["attr"] ?? null), ["class" => twig_trim_filter((((twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", true, true, false, 147)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", false, false, false, 147), "")) : ("")) . " is-invalid"))]);
            // line 148
            echo "    ";
        }
        // line 149
        $this->displayParentBlock("widget_attributes", $context, $blocks);
    }

    // line 152
    public function block_button_widget($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 153
        $context["attr"] = twig_array_merge(($context["attr"] ?? null), ["class" => twig_trim_filter((((twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", true, true, false, 153)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", false, false, false, 153), "btn-secondary")) : ("btn-secondary")) . " btn"))]);
        // line 154
        $this->displayParentBlock("button_widget", $context, $blocks);
    }

    // line 157
    public function block_submit_widget($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 158
        $context["attr"] = twig_array_merge(($context["attr"] ?? null), ["class" => twig_trim_filter(((twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", true, true, false, 158)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", false, false, false, 158), "btn-primary")) : ("btn-primary")))]);
        // line 159
        $this->displayParentBlock("submit_widget", $context, $blocks);
    }

    // line 162
    public function block_checkbox_widget($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 163
        $context["parent_label_class"] = (((isset($context["parent_label_class"]) || array_key_exists("parent_label_class", $context))) ? (_twig_default_filter(($context["parent_label_class"] ?? null), ((twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", true, true, false, 163)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", false, false, false, 163), "")) : ("")))) : (((twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", true, true, false, 163)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", false, false, false, 163), "")) : (""))));
        // line 164
        if (twig_in_filter("checkbox-custom", ($context["parent_label_class"] ?? null))) {
            // line 165
            $context["attr"] = twig_array_merge(($context["attr"] ?? null), ["class" => twig_trim_filter((((twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", true, true, false, 165)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", false, false, false, 165), "")) : ("")) . " custom-control-input"))]);
            // line 166
            echo "<div class=\"custom-control custom-checkbox";
            echo ((twig_in_filter("checkbox-inline", ($context["parent_label_class"] ?? null))) ? (" custom-control-inline") : (""));
            echo "\">";
            // line 167
            echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(($context["form"] ?? null), 'label', ["widget" => $this->renderParentBlock("checkbox_widget", $context, $blocks)]);
            // line 168
            echo "</div>";
        } elseif (twig_in_filter("switch-custom",         // line 169
($context["parent_label_class"] ?? null))) {
            // line 170
            $context["attr"] = twig_array_merge(($context["attr"] ?? null), ["class" => twig_trim_filter((((twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", true, true, false, 170)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", false, false, false, 170), "")) : ("")) . " custom-control-input"))]);
            // line 171
            echo "<div class=\"custom-control custom-switch";
            echo ((twig_in_filter("switch-inline", ($context["parent_label_class"] ?? null))) ? (" custom-control-inline") : (""));
            echo "\">";
            // line 172
            echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(($context["form"] ?? null), 'label', ["widget" => $this->renderParentBlock("checkbox_widget", $context, $blocks)]);
            // line 173
            echo "</div>";
        } else {
            // line 175
            $context["attr"] = twig_array_merge(($context["attr"] ?? null), ["class" => twig_trim_filter((((twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", true, true, false, 175)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", false, false, false, 175), "")) : ("")) . " form-check-input"))]);
            // line 176
            echo "<div class=\"form-check";
            echo ((twig_in_filter("checkbox-inline", ($context["parent_label_class"] ?? null))) ? (" form-check-inline") : (""));
            echo "\">";
            // line 177
            echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(($context["form"] ?? null), 'label', ["widget" => $this->renderParentBlock("checkbox_widget", $context, $blocks)]);
            // line 178
            echo "</div>";
        }
    }

    // line 182
    public function block_radio_widget($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 183
        $context["parent_label_class"] = (((isset($context["parent_label_class"]) || array_key_exists("parent_label_class", $context))) ? (_twig_default_filter(($context["parent_label_class"] ?? null), ((twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", true, true, false, 183)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", false, false, false, 183), "")) : ("")))) : (((twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", true, true, false, 183)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", false, false, false, 183), "")) : (""))));
        // line 184
        if (twig_in_filter("radio-custom", ($context["parent_label_class"] ?? null))) {
            // line 185
            $context["attr"] = twig_array_merge(($context["attr"] ?? null), ["class" => twig_trim_filter((((twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", true, true, false, 185)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", false, false, false, 185), "")) : ("")) . " custom-control-input"))]);
            // line 186
            echo "<div class=\"custom-control custom-radio";
            echo ((twig_in_filter("radio-inline", ($context["parent_label_class"] ?? null))) ? (" custom-control-inline") : (""));
            echo "\">";
            // line 187
            echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(($context["form"] ?? null), 'label', ["widget" => $this->renderParentBlock("radio_widget", $context, $blocks)]);
            // line 188
            echo "</div>";
        } else {
            // line 190
            $context["attr"] = twig_array_merge(($context["attr"] ?? null), ["class" => twig_trim_filter((((twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", true, true, false, 190)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["attr"] ?? null), "class", [], "any", false, false, false, 190), "")) : ("")) . " form-check-input"))]);
            // line 191
            echo "<div class=\"form-check";
            echo ((twig_in_filter("radio-inline", ($context["parent_label_class"] ?? null))) ? (" form-check-inline") : (""));
            echo "\">";
            // line 192
            echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(($context["form"] ?? null), 'label', ["widget" => $this->renderParentBlock("radio_widget", $context, $blocks)]);
            // line 193
            echo "</div>";
        }
    }

    // line 197
    public function block_choice_widget_expanded($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 198
        echo "<div ";
        $this->displayBlock("widget_container_attributes", $context, $blocks);
        echo ">";
        // line 199
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["form"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["child"]) {
            // line 200
            echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock($context["child"], 'widget', ["parent_label_class" => ((twig_get_attribute($this->env, $this->source,             // line 201
($context["label_attr"] ?? null), "class", [], "any", true, true, false, 201)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", false, false, false, 201), "")) : ("")), "translation_domain" =>             // line 202
($context["choice_translation_domain"] ?? null), "valid" =>             // line 203
($context["valid"] ?? null)]);
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['child'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 206
        echo "</div>";
    }

    // line 211
    public function block_form_label($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 212
        if ( !(($context["label"] ?? null) === false)) {
            // line 213
            if (((isset($context["compound"]) || array_key_exists("compound", $context)) && ($context["compound"] ?? null))) {
                // line 214
                $context["element"] = "legend";
                // line 215
                $context["label_attr"] = twig_array_merge(($context["label_attr"] ?? null), ["class" => twig_trim_filter((((twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", true, true, false, 215)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", false, false, false, 215), "")) : ("")) . " col-form-label"))]);
            } else {
                // line 217
                $context["label_attr"] = twig_array_merge(($context["label_attr"] ?? null), ["for" => ($context["id"] ?? null)]);
            }
            // line 219
            if (($context["required"] ?? null)) {
                // line 220
                $context["label_attr"] = twig_array_merge(($context["label_attr"] ?? null), ["class" => twig_trim_filter((((twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", true, true, false, 220)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", false, false, false, 220), "")) : ("")) . " required"))]);
            }
            // line 222
            if (twig_test_empty(($context["label"] ?? null))) {
                // line 223
                if ( !twig_test_empty(($context["label_format"] ?? null))) {
                    // line 224
                    $context["label"] = twig_replace_filter(($context["label_format"] ?? null), ["%name%" =>                     // line 225
($context["name"] ?? null), "%id%" =>                     // line 226
($context["id"] ?? null)]);
                } else {
                    // line 229
                    $context["label"] = $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->humanize(($context["name"] ?? null));
                }
            }
            // line 232
            echo "<";
            echo twig_escape_filter($this->env, (((isset($context["element"]) || array_key_exists("element", $context))) ? (_twig_default_filter(($context["element"] ?? null), "label")) : ("label")), "html", null, true);
            if (($context["label_attr"] ?? null)) {
                $__internal_01476f8db28655ee4ee02ea2d17dd5a92599be76304f08cd8bc0e05aced30666 = ["attr" => ($context["label_attr"] ?? null)];
                if (!twig_test_iterable($__internal_01476f8db28655ee4ee02ea2d17dd5a92599be76304f08cd8bc0e05aced30666)) {
                    throw new RuntimeError('Variables passed to the "with" tag must be a hash.', 232, $this->getSourceContext());
                }
                $__internal_01476f8db28655ee4ee02ea2d17dd5a92599be76304f08cd8bc0e05aced30666 = twig_to_array($__internal_01476f8db28655ee4ee02ea2d17dd5a92599be76304f08cd8bc0e05aced30666);
                $context['_parent'] = $context;
                $context = $this->env->mergeGlobals(array_merge($context, $__internal_01476f8db28655ee4ee02ea2d17dd5a92599be76304f08cd8bc0e05aced30666));
                $this->displayBlock("attributes", $context, $blocks);
                $context = $context['_parent'];
            }
            echo ">";
            echo twig_escape_filter($this->env, (((($context["translation_domain"] ?? null) === false)) ? (($context["label"] ?? null)) : ($this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans(($context["label"] ?? null), ($context["label_translation_parameters"] ?? null), ($context["translation_domain"] ?? null)))), "html", null, true);
            $this->displayBlock('form_label_errors', $context, $blocks);
            echo "</";
            echo twig_escape_filter($this->env, (((isset($context["element"]) || array_key_exists("element", $context))) ? (_twig_default_filter(($context["element"] ?? null), "label")) : ("label")), "html", null, true);
            echo ">";
        } else {
            // line 234
            if ((twig_length_filter($this->env, ($context["errors"] ?? null)) > 0)) {
                // line 235
                echo "<div id=\"";
                echo twig_escape_filter($this->env, ($context["id"] ?? null), "html", null, true);
                echo "_errors\" class=\"mb-2\">";
                // line 236
                echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(($context["form"] ?? null), 'errors');
                // line 237
                echo "</div>";
            }
        }
    }

    // line 232
    public function block_form_label_errors($context, array $blocks = [])
    {
        $macros = $this->macros;
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(($context["form"] ?? null), 'errors');
    }

    // line 242
    public function block_checkbox_radio_label($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 244
        if ((isset($context["widget"]) || array_key_exists("widget", $context))) {
            // line 245
            $context["is_parent_custom"] = ((isset($context["parent_label_class"]) || array_key_exists("parent_label_class", $context)) && ((twig_in_filter("checkbox-custom", ($context["parent_label_class"] ?? null)) || twig_in_filter("radio-custom", ($context["parent_label_class"] ?? null))) || twig_in_filter("switch-custom", ($context["parent_label_class"] ?? null))));
            // line 246
            echo "        ";
            $context["is_custom"] = (twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", true, true, false, 246) && ((twig_in_filter("checkbox-custom", twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", false, false, false, 246)) || twig_in_filter("radio-custom", twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", false, false, false, 246))) || twig_in_filter("switch-custom", twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", false, false, false, 246))));
            // line 247
            if ((($context["is_parent_custom"] ?? null) || ($context["is_custom"] ?? null))) {
                // line 248
                $context["label_attr"] = twig_array_merge(($context["label_attr"] ?? null), ["class" => twig_trim_filter((((twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", true, true, false, 248)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", false, false, false, 248), "")) : ("")) . " custom-control-label"))]);
            } else {
                // line 250
                $context["label_attr"] = twig_array_merge(($context["label_attr"] ?? null), ["class" => twig_trim_filter((((twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", true, true, false, 250)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", false, false, false, 250), "")) : ("")) . " form-check-label"))]);
            }
            // line 252
            if ( !($context["compound"] ?? null)) {
                // line 253
                $context["label_attr"] = twig_array_merge(($context["label_attr"] ?? null), ["for" => ($context["id"] ?? null)]);
            }
            // line 255
            if (($context["required"] ?? null)) {
                // line 256
                $context["label_attr"] = twig_array_merge(($context["label_attr"] ?? null), ["class" => twig_trim_filter((((twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", true, true, false, 256)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", false, false, false, 256), "")) : ("")) . " required"))]);
            }
            // line 258
            if ((isset($context["parent_label_class"]) || array_key_exists("parent_label_class", $context))) {
                // line 259
                $context["label_attr"] = twig_array_merge(($context["label_attr"] ?? null), ["class" => twig_trim_filter(twig_replace_filter(((((twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", true, true, false, 259)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["label_attr"] ?? null), "class", [], "any", false, false, false, 259), "")) : ("")) . " ") . ($context["parent_label_class"] ?? null)), ["checkbox-inline" => "", "radio-inline" => "", "checkbox-custom" => "", "radio-custom" => ""]))]);
            }
            // line 261
            if (( !(($context["label"] ?? null) === false) && twig_test_empty(($context["label"] ?? null)))) {
                // line 262
                if ( !twig_test_empty(($context["label_format"] ?? null))) {
                    // line 263
                    $context["label"] = twig_replace_filter(($context["label_format"] ?? null), ["%name%" =>                     // line 264
($context["name"] ?? null), "%id%" =>                     // line 265
($context["id"] ?? null)]);
                } else {
                    // line 268
                    $context["label"] = $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->humanize(($context["name"] ?? null));
                }
            }
            // line 272
            echo ($context["widget"] ?? null);
            echo "
        <label";
            // line 273
            $__internal_01c35b74bd85735098add188b3f8372ba465b232ab8298cb582c60f493d3c22e = ["attr" => ($context["label_attr"] ?? null)];
            if (!twig_test_iterable($__internal_01c35b74bd85735098add188b3f8372ba465b232ab8298cb582c60f493d3c22e)) {
                throw new RuntimeError('Variables passed to the "with" tag must be a hash.', 273, $this->getSourceContext());
            }
            $__internal_01c35b74bd85735098add188b3f8372ba465b232ab8298cb582c60f493d3c22e = twig_to_array($__internal_01c35b74bd85735098add188b3f8372ba465b232ab8298cb582c60f493d3c22e);
            $context['_parent'] = $context;
            $context = $this->env->mergeGlobals(array_merge($context, $__internal_01c35b74bd85735098add188b3f8372ba465b232ab8298cb582c60f493d3c22e));
            $this->displayBlock("attributes", $context, $blocks);
            $context = $context['_parent'];
            echo ">";
            // line 274
            (( !(($context["label"] ?? null) === false)) ? (print (twig_escape_filter($this->env, (((($context["translation_domain"] ?? null) === false)) ? (($context["label"] ?? null)) : ($this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans(($context["label"] ?? null), ($context["label_translation_parameters"] ?? null), ($context["translation_domain"] ?? null)))), "html", null, true))) : (print ("")));
            // line 275
            echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(($context["form"] ?? null), 'errors');
            // line 276
            echo "</label>";
        }
    }

    // line 282
    public function block_form_row($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 283
        if (((isset($context["compound"]) || array_key_exists("compound", $context)) && ($context["compound"] ?? null))) {
            // line 284
            $context["element"] = "fieldset";
        }
        // line 286
        $context["widget_attr"] = [];
        // line 287
        if ( !twig_test_empty(($context["help"] ?? null))) {
            // line 288
            $context["widget_attr"] = ["attr" => ["aria-describedby" => (($context["id"] ?? null) . "_help")]];
        }
        // line 290
        echo "<";
        echo twig_escape_filter($this->env, (((isset($context["element"]) || array_key_exists("element", $context))) ? (_twig_default_filter(($context["element"] ?? null), "div")) : ("div")), "html", null, true);
        $__internal_63ad1f9a2bf4db4af64b010785e9665558fdcac0e8db8b5b413ed986c62dbb52 = ["attr" => twig_array_merge(($context["row_attr"] ?? null), ["class" => twig_trim_filter((((twig_get_attribute($this->env, $this->source, ($context["row_attr"] ?? null), "class", [], "any", true, true, false, 290)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["row_attr"] ?? null), "class", [], "any", false, false, false, 290), "")) : ("")) . " form-group"))])];
        if (!twig_test_iterable($__internal_63ad1f9a2bf4db4af64b010785e9665558fdcac0e8db8b5b413ed986c62dbb52)) {
            throw new RuntimeError('Variables passed to the "with" tag must be a hash.', 290, $this->getSourceContext());
        }
        $__internal_63ad1f9a2bf4db4af64b010785e9665558fdcac0e8db8b5b413ed986c62dbb52 = twig_to_array($__internal_63ad1f9a2bf4db4af64b010785e9665558fdcac0e8db8b5b413ed986c62dbb52);
        $context['_parent'] = $context;
        $context = $this->env->mergeGlobals(array_merge($context, $__internal_63ad1f9a2bf4db4af64b010785e9665558fdcac0e8db8b5b413ed986c62dbb52));
        $this->displayBlock("attributes", $context, $blocks);
        $context = $context['_parent'];
        echo ">";
        // line 291
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(($context["form"] ?? null), 'label');
        // line 292
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(($context["form"] ?? null), 'widget', ($context["widget_attr"] ?? null));
        // line 293
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(($context["form"] ?? null), 'help');
        // line 294
        echo "</";
        echo twig_escape_filter($this->env, (((isset($context["element"]) || array_key_exists("element", $context))) ? (_twig_default_filter(($context["element"] ?? null), "div")) : ("div")), "html", null, true);
        echo ">";
    }

    // line 299
    public function block_form_errors($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 300
        if ((twig_length_filter($this->env, ($context["errors"] ?? null)) > 0)) {
            // line 301
            echo "<span class=\"";
            if ( !Symfony\Bridge\Twig\Extension\twig_is_root_form(($context["form"] ?? null))) {
                echo "invalid-feedback";
            } else {
                echo "alert alert-danger";
            }
            echo " d-block\">";
            // line 302
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["errors"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["error"]) {
                // line 303
                echo "<span class=\"d-block\">
                    <span class=\"form-error-icon badge badge-danger text-uppercase\">";
                // line 304
                echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Error", [], "validators"), "html", null, true);
                echo "</span> <span class=\"form-error-message\">";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["error"], "message", [], "any", false, false, false, 304), "html", null, true);
                echo "</span>
                </span>";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['error'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 307
            echo "</span>";
        }
    }

    // line 313
    public function block_form_help($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 314
        if ( !twig_test_empty(($context["help"] ?? null))) {
            // line 315
            $context["help_attr"] = twig_array_merge(($context["help_attr"] ?? null), ["class" => twig_trim_filter((((twig_get_attribute($this->env, $this->source, ($context["help_attr"] ?? null), "class", [], "any", true, true, false, 315)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["help_attr"] ?? null), "class", [], "any", false, false, false, 315), "")) : ("")) . " form-text text-muted"))]);
            // line 316
            echo "<small id=\"";
            echo twig_escape_filter($this->env, ($context["id"] ?? null), "html", null, true);
            echo "_help\"";
            $__internal_f10a4cc339617934220127f034125576ed229e948660ebac906a15846d52f136 = ["attr" => ($context["help_attr"] ?? null)];
            if (!twig_test_iterable($__internal_f10a4cc339617934220127f034125576ed229e948660ebac906a15846d52f136)) {
                throw new RuntimeError('Variables passed to the "with" tag must be a hash.', 316, $this->getSourceContext());
            }
            $__internal_f10a4cc339617934220127f034125576ed229e948660ebac906a15846d52f136 = twig_to_array($__internal_f10a4cc339617934220127f034125576ed229e948660ebac906a15846d52f136);
            $context['_parent'] = $context;
            $context = $this->env->mergeGlobals(array_merge($context, $__internal_f10a4cc339617934220127f034125576ed229e948660ebac906a15846d52f136));
            $this->displayBlock("attributes", $context, $blocks);
            $context = $context['_parent'];
            echo ">";
            // line 317
            if ((($context["translation_domain"] ?? null) === false)) {
                // line 318
                if ((($context["help_html"] ?? null) === false)) {
                    // line 319
                    echo twig_escape_filter($this->env, ($context["help"] ?? null), "html", null, true);
                } else {
                    // line 321
                    echo ($context["help"] ?? null);
                }
            } else {
                // line 324
                if ((($context["help_html"] ?? null) === false)) {
                    // line 325
                    echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans(($context["help"] ?? null), ($context["help_translation_parameters"] ?? null), ($context["translation_domain"] ?? null)), "html", null, true);
                } else {
                    // line 327
                    echo $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans(($context["help"] ?? null), ($context["help_translation_parameters"] ?? null), ($context["translation_domain"] ?? null));
                }
            }
            // line 330
            echo "</small>";
        }
    }

    public function getTemplateName()
    {
        return "bootstrap_4_layout.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  882 => 330,  878 => 327,  875 => 325,  873 => 324,  869 => 321,  866 => 319,  864 => 318,  862 => 317,  848 => 316,  846 => 315,  844 => 314,  840 => 313,  835 => 307,  825 => 304,  822 => 303,  818 => 302,  810 => 301,  808 => 300,  804 => 299,  798 => 294,  796 => 293,  794 => 292,  792 => 291,  779 => 290,  776 => 288,  774 => 287,  772 => 286,  769 => 284,  767 => 283,  763 => 282,  758 => 276,  756 => 275,  754 => 274,  743 => 273,  739 => 272,  735 => 268,  732 => 265,  731 => 264,  730 => 263,  728 => 262,  726 => 261,  723 => 259,  721 => 258,  718 => 256,  716 => 255,  713 => 253,  711 => 252,  708 => 250,  705 => 248,  703 => 247,  700 => 246,  698 => 245,  696 => 244,  692 => 242,  685 => 232,  679 => 237,  677 => 236,  673 => 235,  671 => 234,  650 => 232,  646 => 229,  643 => 226,  642 => 225,  641 => 224,  639 => 223,  637 => 222,  634 => 220,  632 => 219,  629 => 217,  626 => 215,  624 => 214,  622 => 213,  620 => 212,  616 => 211,  612 => 206,  606 => 203,  605 => 202,  604 => 201,  603 => 200,  599 => 199,  595 => 198,  591 => 197,  586 => 193,  584 => 192,  580 => 191,  578 => 190,  575 => 188,  573 => 187,  569 => 186,  567 => 185,  565 => 184,  563 => 183,  559 => 182,  554 => 178,  552 => 177,  548 => 176,  546 => 175,  543 => 173,  541 => 172,  537 => 171,  535 => 170,  533 => 169,  531 => 168,  529 => 167,  525 => 166,  523 => 165,  521 => 164,  519 => 163,  515 => 162,  511 => 159,  509 => 158,  505 => 157,  501 => 154,  499 => 153,  495 => 152,  491 => 149,  488 => 148,  485 => 147,  483 => 146,  479 => 145,  475 => 142,  472 => 140,  470 => 139,  468 => 138,  465 => 136,  463 => 135,  459 => 134,  453 => 131,  450 => 130,  447 => 128,  445 => 127,  431 => 126,  429 => 125,  427 => 124,  425 => 123,  421 => 122,  417 => 121,  412 => 117,  406 => 113,  403 => 112,  401 => 111,  399 => 110,  397 => 109,  393 => 108,  388 => 104,  384 => 103,  379 => 100,  375 => 99,  372 => 98,  370 => 97,  365 => 94,  361 => 93,  358 => 92,  356 => 91,  351 => 88,  347 => 87,  344 => 86,  342 => 85,  337 => 82,  333 => 81,  330 => 80,  328 => 79,  323 => 76,  319 => 75,  316 => 74,  314 => 73,  309 => 70,  305 => 69,  302 => 68,  300 => 67,  295 => 64,  291 => 63,  288 => 62,  286 => 61,  282 => 60,  280 => 59,  277 => 57,  275 => 56,  272 => 54,  270 => 53,  268 => 52,  264 => 51,  260 => 48,  257 => 46,  255 => 45,  253 => 44,  249 => 43,  245 => 40,  242 => 38,  240 => 37,  238 => 36,  234 => 35,  230 => 32,  227 => 30,  225 => 29,  223 => 28,  219 => 27,  214 => 23,  211 => 21,  206 => 18,  203 => 17,  201 => 16,  199 => 15,  194 => 12,  191 => 11,  189 => 10,  185 => 9,  183 => 8,  181 => 7,  179 => 6,  175 => 5,  171 => 313,  168 => 312,  165 => 310,  163 => 299,  160 => 298,  157 => 296,  155 => 282,  152 => 281,  149 => 279,  147 => 242,  144 => 241,  142 => 211,  139 => 210,  136 => 208,  134 => 197,  131 => 196,  129 => 182,  126 => 181,  124 => 162,  121 => 161,  119 => 157,  116 => 156,  114 => 152,  112 => 145,  110 => 134,  107 => 133,  105 => 121,  102 => 120,  100 => 108,  97 => 107,  95 => 51,  92 => 50,  90 => 43,  87 => 42,  85 => 35,  82 => 34,  80 => 27,  77 => 26,  75 => 5,  72 => 4,  69 => 2,  30 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "bootstrap_4_layout.html.twig", "/var/www/frontastic/paas/catwalk/vendor/symfony/symfony/src/Symfony/Bridge/Twig/Resources/views/Form/bootstrap_4_layout.html.twig");
    }
}
