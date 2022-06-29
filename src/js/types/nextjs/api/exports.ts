/*
 * This file is the main entry point of the module and re-exports all relevant
 * type. This way we can also include types from files in subdirectories,
 * manually defined ones and rename types which don't get distinct names from
 * the generator.
 */
export type {
    ActionContext,
    Configuration,
    Context,
    DataSourceConfiguration,
    DataSourceContext,
    DataSourceResult,
    DynamicPageContext,
    DynamicPageRedirectResult,
    DynamicPageSuccessResult,
    LayoutElement,
    Page,
    PageFolder,
    Project,
    Request,
    Response,
    Section,
    Tastic,
} from './index';

export type { PageFolderTreeValue, PageFolderValue } from './tasticfieldvalue/index';
export type { Configuration as LayoutElementConfiguration } from './layoutelement/index';
export type { Configuration as TasticConfiguration } from './tastic/index';
export type {
    ComponentValueLinkReference,
    ComponentValuePageFolderReference,
    ComponentValueReference,
} from './reference';
export type { ComponentValueMedia } from './media';
export type {
    ComponentValueString,
    ComponentValueText,
    ComponentValueMarkdown,
    ComponentValueNumber,
    ComponentValueInteger,
    ComponentValueDecimal,
    ComponentValueEnum,
    ComponentValueBoolean,
    ComponentValueJson,
} from './basicComponentValues';
export type {
    ExtensionRegistry,
    DataSourceRegistry,
    ActionRegistry,
    ActionHandler,
    DataSourceHandler,
    DynamicPageHandler
} from './extensions';
