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

/* TwigBundle:images:favicon.png.base64 */
class __TwigTemplate_bb50fe38b2ec9ca83b63597f824e2dca313686a1140b2fc6fd8e4f88814b62f0 extends \Twig\Template
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
        echo "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABwAAAAgCAYAAAABtRhCAAADVUlEQVRIx82XX0jTURTHLYPyqZdefQx66CEo80+aYpoIkqzUikz6Z5klQoWUWYRIJYEUGpQ+lIr9U5dOTLdCtkmWZis3rbnC5fw/neYW002307mX/cZvP3/7o1PwwOdh95x7vnf39zvnd29AgBer2xO6DclAXiMqZAqxIiNIN/IYSUS2BPhjmGATchUxI+ADWiRhpWK7HKuHFVBFdmU5YvnI4grFGCaReF/EBH4KsZlGgj2JBTuCYBWRIYF8YoEOJ6wBt/gEs7mBbyOjQXruPLSdOgPCiEiPSUUHDoL8Ug5IUo9B/d5wrt+G7OAKNrODPuVdB6vRCIzN6SdBlpW9RIgk/1FeAXabzRlrUPVCS/JhbmwudztnGeeH9AyXBIwtmM3wLinZJZHifjHw2V+NBoRh+9ixQrbgbnaSIcl7cGea6hoXQbNe7za241oeO5Z0p42M4BV2EqP2D50wo+6HzvwC6C4sApNOR8cmOrtcnhtj2kYRyC9eBvXzKrBZrXSs72kFd1t3MoKVbMekQkEnSNKOO8fac3LpmK6l1TlGtsxmsdKFsecPYgwxst0cwROMYDXboSotg0WLBRqjY51jLYcENElXwW2XJKPydvoI2GN9T8rBtrAArYIUruBJXkFheCQYlCpQP6uk5dAQFQNaUROMSGVQFxLmkoQsxDJrhLbTZ+nvVsERME9MgPJRKV/58AsyomTSzE813WLFvWK++qI0xSfQl8k8Pg46sYRuv5t6dS+4RqxDwaa4BGjYH+NTQvKScIp9+YL/hoZh3jDtLRHtt2C3g6bmhX+CpsFBWg7ilDSPgj0lD2ncr5ev/BP8VvyAJhqVyZeUhPOrEhEFxgEtjft846Z/guQTNT89Q5P9flMLoth4F7808wKtWWKzAwNQHxrh/1vaid2F+XpYTSbQf1XA2McOmOpROnvpvMEA4tSjq1cW0sws2gCYxswY6TKkvzYnJq1NHZLnRU4BX+4U0uburvusu8Kv8iHY7qefkM4IFngJHEOUXmLEPgiGsI8YnlZILit3vSSLRTQe/MPIZva5pshNIEmyFQlCvruJKXPkCEfmePzkphXHdzZNQdoRI9KPlBAxlj/I8U97ERPS5bjGbWDFbEdqHVe5caTBeZZx2H/IMvzeN15yoQAAAABJRU5ErkJggg==
";
    }

    public function getTemplateName()
    {
        return "TwigBundle:images:favicon.png.base64";
    }

    public function getDebugInfo()
    {
        return array (  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "TwigBundle:images:favicon.png.base64", "/var/www/frontastic/paas/catwalk/vendor/symfony/symfony/src/Symfony/Bundle/TwigBundle/Resources/views/images/favicon.png.base64");
    }
}
