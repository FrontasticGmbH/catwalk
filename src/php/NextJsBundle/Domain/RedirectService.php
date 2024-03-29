<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\Redirect;
use Frontastic\Catwalk\FrontendBundle\Domain\RedirectService as FrontasticReactRedirectService;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\DynamicPageRedirectResult;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\Frontend\RedirectResponse;
use Symfony\Component\HttpFoundation\ParameterBag;

class RedirectService
{
    private SiteBuilderPageService $siteBuilderPageService;
    private FrontasticReactRedirectService $reactRedirectService;

    public function __construct(
        FrontasticReactRedirectService $reactRedirectService,
        SiteBuilderPageService $siteBuilderPageService
    ) {
        $this->reactRedirectService = $reactRedirectService;
        $this->siteBuilderPageService = $siteBuilderPageService;
    }

    /**
     * Returns a redirect response if neccessary for the given path and context. There are 2 such scenarios:
     * 1. There is a locale mismatch where a given path exists but does not match the current locale
     * 2. There is a redirect configured for the provided path
     * @param string $path
     * @param array $queryParameters Array of query params provided as key-value pairs
     * @param Context $context
     */
    public function getRedirectResponseForPath(
        string $path,
        array $queryParameters,
        Context $context
    ): ?RedirectResponse {
        $redirect = $this->getLocaleMismatchRedirect($path, $context->locale, $context->project->languages);

        if ($redirect !== null) {
            return $redirect;
        }

        $redirect = $this->reactRedirectService->getRedirectForRequest($path, new ParameterBag($queryParameters));

        if ($redirect !== null) {
            return $this->createResponseFromRedirectObject($redirect, $context->locale);
        }

        return null;
    }

    /**
     * If a locale mismatch occurs between the given path and locale,
     * this function returns the appropriate RedirectResponse object.
     * A locale mismatch means that a given path exists but not for the given locale.
     * @param string $path The path of the sitebuilder page
     * @param string $currentLocale The locale which the given path should exist in
     * @param array $languages The available languages in the project
     */
    public function getLocaleMismatchRedirect(string $path, string $currentLocale, array $languages): ?RedirectResponse
    {
        // If there is no locale mismatch, return null
        if ($this->siteBuilderPageService->matchSiteBuilderPage($path, $currentLocale) !== null) {
            return null;
        }

        foreach ($languages as $language) {
            $nodeId = $this->siteBuilderPageService->matchSiteBuilderPage($path, $language);

            if ($nodeId !== null) {
                $paths = $this->siteBuilderPageService->getPathsForSiteBuilderPage($nodeId);

                if (array_key_exists($currentLocale, $paths)) {
                    return new RedirectResponse([
                        'statusCode' => 301,
                        'reason' => RedirectResponse::REASON_LOCALE_MISMATCH,
                        'targetType' => RedirectResponse::TARGET_TYPE_PAGE_FOLDER,
                        'target' => $paths[$currentLocale]
                    ]);
                }
            }
        }

        return null;
    }

    /**
     * Creates a RedirectResponse object from a Redirect object + a locale and returns it.
     * @param Redirect $redirect
     * @param string $locale
     */
    public function createResponseFromRedirectObject(Redirect $redirect, string $locale): ?RedirectResponse
    {
        $target = $redirect->target;
        $targetType = $redirect->targetType;

        if ($redirect->targetType === Redirect::TARGET_TYPE_NODE) {
            $paths = $this->siteBuilderPageService->getPathsForSiteBuilderPage($redirect->target);

            if (!array_key_exists($locale, $paths)) {
                return null;
            }

            $target = $paths[$locale];
            $targetType = RedirectResponse::TARGET_TYPE_PAGE_FOLDER;
        }

        return new RedirectResponse([
            'statusCode' => $redirect->statusCode,
            'reason' => RedirectResponse::REASON_REDIRECT_EXISTS_FOR_PATH,
            'targetType' => $targetType,
            'target' => $target
        ]);
    }

    /**
     * Creates a RedirectResponse from a DynamicPageRedirectResult object
     * @param DynamicPageRedirectResult $result
     */
    public function createResponseFromDynamicPageRedirectResult(DynamicPageRedirectResult $result): RedirectResponse
    {
        return new RedirectResponse([
            'statusCode' => $result->statusCode,
            'reason' => RedirectResponse::REASON_DYNAMIC_PAGE_REDIRECT,
            'targetType' => RedirectResponse::TARGET_TYPE_UNKNOWN,
            'target' => $result->redirectLocation
        ]);
    }
}
