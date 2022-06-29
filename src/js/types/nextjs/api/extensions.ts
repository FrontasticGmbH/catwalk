import {
    ActionContext,
    DataSourceConfiguration,
    DataSourceContext,
    DataSourceResult,
    DynamicPageRedirectResult,
    DynamicPageSuccessResult,
    Request,
    Response,
} from './index';

export interface ExtensionRegistry {
    'data-sources'?: DataSourceRegistry;
    actions?: ActionRegistry;
    'dynamic-page-handler'?: DynamicPageHandler;
}

export interface DataSourceRegistry {
    [dataSourceType: string]: DataSourceHandler;
}

export interface ActionRegistry {
    [actionNamespace: string]: {
        [actionIdentifier: string]: ActionHandler;
    };
}

export interface DataSourceHandler {
    (config: DataSourceConfiguration, context: DataSourceContext): Promise<DataSourceResult> | DataSourceResult;
}

export interface ActionHandler {
    (request: Request, actionContext: ActionContext): Promise<Response> | Response;
}

export interface DynamicPageHandler {
    (request: Request, context: DataSourceContext): PossibleDynamicPageResults | Promise<PossibleDynamicPageResults>;
}

type PossibleDynamicPageResults = DynamicPageSuccessResult | DynamicPageRedirectResult | null;
