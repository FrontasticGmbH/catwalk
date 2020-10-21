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

/* FrontasticCatwalkFrontendBundle::missing_homepage.html.twig */
class __TwigTemplate_44181f77b35d10ff91f6b56eb5b14607eded7a4af2b02df122b994607934ed12 extends \Twig\Template
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
        echo "<!DOCTYPE html>
<html>
<head>
    <meta charset=\"UTF-8\" />
    <title>Woops ...</title>
    <style>
        body { background: #F5F5F5; font: 18px/1.5 sans-serif; }
        h1, h2 { line-height: 1.2; margin: 0 0 .5em; }
        h1 { font-size: 36px; }
        h2 { font-size: 21px; margin-bottom: 1em; }
        p { margin: 0 0 1em 0; }
        a { color: #0000F0; }
        a:hover { text-decoration: none; }
        code { background: #F5F5F5; max-width: 100px; padding: 2px 6px; word-wrap: break-word; }
        #wrapper { background: #FFF; margin: 1em auto; max-width: 800px; width: 95%; }
        #container { padding: 2em; }
        #welcome, #status { margin-bottom: 2em; }
        #welcome h1 span { display: block; font-size: 75%; }
    </style>
</head>
<body>
<div id=\"wrapper\">
    <div id=\"container\">
        <div id=\"welcome\">
            <h1>Welcome to Frontastic Catwalk</h1>
        </div>

        <div id=\"status\">
            <p>
                Your Catwalk is up and running. However, no homepage route seems to be configured.
                The reason for this might be one of the following:
            </p>
            <ul>
                <li>
                    If you are running on your own Backstage data, it might be that there is no Node configured for the URL <code>/</code>. Login to your Backstage to verify that.
                </li>
                <li>
                    If you are running on Frontastic example data, it can be that replication to your VM hangs or is in lag. Go to your <a href=\"http://frontastic.io.local\">developer landing page</a> and check the replicator logs to see what's going on.
                </li>
            </ul>
        </div>
    </div>
    <div id=\"comment\">
        <p>
            You're seeing this page because debug mode is enabled, production won't show this page.
        </p>
    </div>
</div>
</body>
</html>
";
    }

    public function getTemplateName()
    {
        return "FrontasticCatwalkFrontendBundle::missing_homepage.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "FrontasticCatwalkFrontendBundle::missing_homepage.html.twig", "/var/www/frontastic/paas/catwalk/src/php/FrontendBundle/Resources/views/missing_homepage.html.twig");
    }
}
