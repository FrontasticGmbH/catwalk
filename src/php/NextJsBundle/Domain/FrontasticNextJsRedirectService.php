<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\Redirect;
use Frontastic\Catwalk\FrontendBundle\Domain\RedirectService;
use Frontastic\Catwalk\FrontendBundle\Gateway\RedirectGateway;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\ParameterBag;

class FrontasticNextJsRedirectService extends RedirectService
{
    private SiteBuilderPageService $siteBuilderPageService;

    public function __construct(
        RedirectGateway $redirectGateway,
        Router $router,
        SiteBuilderPageService $siteBuilderPageService
    ) {
        parent::__construct($redirectGateway, $router);
        $this->siteBuilderPageService = $siteBuilderPageService;
    }

    /**
     * Returns a redirect response if neccessary for the given path and context. There are 2 such scenarios:
     * 1. There is a locale mismatch where a given path exists but does not match the current locale
     * 2. There is a redirect configured for the provided path
     * @param string $path
     * @param ParameterBag $queryParameters Currently unused but will be appended to the redirect target in the future
     * @param Context $context
     */
    public function getRedirectResponseForPath(string $path, ParameterBag $queryParameters, Context $context): ?RedirectResponse
    {
        $redirect = $this->getLocaleMismatchRedirect($path, $context->locale, $context->project->languages);

        if ($redirect !== null) {
            return $redirect;
        }

        $redirect = $this->getRedirectForRequest($path, $queryParameters);

        if ($redirect !== null) {
            return $this->createResponseFromRedirectObject($redirect, $context->locale);
        }

        return null;
    }

    /**
     * If a locale mismatch occurs between the given path and locale this function returns the appropriate RedirectResponse object.
     * A locale mismatch means that a given path exists but not for the given locale.
     * @param string $path The path of the sitebuilder page
     * @param string $currentLocale The locale which the given path should exist in
     * @param array $locales The available locales in the project
     */
    public function getLocaleMismatchRedirect(string $path, string $currentLocale, array $locales): ?RedirectResponse
    {
        // If there is no locale mismatch, return null
        if ($this->siteBuilderPageService->matchSiteBuilderPage($path, $currentLocale) !== null) {
            return null;
        }

        foreach ($locales as $locale) {
            $nodeId = $this->siteBuilderPageService->matchSiteBuilderPage($path, $locale);

            if ($nodeId !== null) {
                $paths = $this->siteBuilderPageService->getPathsForSiteBuilderPage($nodeId);

                return new RedirectResponse([
                    'statusCode' => 301,
                    'reason' => RedirectResponse::REASON_LOCALE_MISMATCH,
                    'targetType' => RedirectResponse::TARGET_TYPE_PAGE_FOLDER,
                    'target' => $paths[$locale]
                ]);
            }
        }

        return null;
    }

    /**
     * Creates a RedirectResponse object from a Redirect object + a locale and returns it.
     * @param Redirect $redirect
     * @param string $locale 
     */
    public function createResponseFromRedirectObject(Redirect $redirect, string $locale): RedirectResponse
    {
        $target = $redirect->target;
        $targetType = $redirect->targetType;

        if ($redirect->targetType === Redirect::TARGET_TYPE_NODE) {
            $target = $this->siteBuilderPageService->getPathsForSiteBuilderPage($redirect->target)[$locale];
            $targetType = RedirectResponse::TARGET_TYPE_PAGE_FOLDER;
        }

        return new RedirectResponse([
            'statusCode' => $redirect->statusCode,
            'reason' => RedirectResponse::REASON_REDIRECT_EXISTS_FOR_PATH,
            'targetType' => $targetType,
            'target' => $target
        ]);
    }
}
