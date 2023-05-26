// This file is autogenerated – run `ant apidocs` to update it

import {
    Configuration as FrontendCellConfiguration,
} from './cell/'

import {
    FacetDefinition as ProductProductApiFacetDefinition,
} from '@frontastic/common/src/js/types/product/productapi'

import {
    Configuration as FrontendRegionConfiguration,
} from './region/'

import {
    Context as ApiCoreContext,
} from '../apicore/'

import {
    Configuration as FrontendTasticConfiguration,
} from './tastic/'

export interface Cell {
     cellId: string;
     configuration: FrontendCellConfiguration;
     customConfiguration?: null | any /* \stdClass */;
     tastics: Tastic[];
}

export interface Configuration {
     mobile: boolean;
     tablet: boolean;
     desktop: boolean;
}

export interface Facet extends ProductProductApiFacetDefinition {
     facetId: string;
     sequence: string;
     sort: number;
     isEnabled: boolean;
     /**
      * Translatable strings or null
      */
     label?: null | any;
     urlIdentifier?: string;
     facetOptions?: any;
     metaData: any /* \Frontastic\UserBundle\Domain\MetaData */;
     isDeleted: boolean;
}

export interface Layout {
     layoutId: string;
     sequence: string;
     name: string;
     description?: string;
     image?: string;
     regions: string[];
     metaData: any /* \Frontastic\UserBundle\Domain\MetaData */;
}

export interface MasterPageMatcherRules {
     rulesId?: string;
     rules?: any;
     sequence?: string;
     metaData?: any /* \Frontastic\UserBundle\Domain\MetaData */;
}

export interface Node {
     nodeId: string;
     isMaster: boolean;
     nodeType: string;
     sequence: string;
     configuration: any;
     streams: Stream[];
     name?: string;
     path: string[];
     depth?: number;
     sort: number;
     children: Node[];
     metaData: any /* \Frontastic\Backstage\UserBundle\Domain\MetaData */;
     /**
      * Optional error string during development
      */
     error?: null | string;
     /**
      * Page is live
      */
     hasLivePage?: boolean;
     isDeleted: boolean;
}

export interface Page {
     pageId: string;
     sequence: string;
     node: Node;
     layoutId?: string;
     regions: Region[];
     metaData: any /* \Frontastic\UserBundle\Domain\MetaData */;
     isDeleted: boolean;
     state: string;
     /**
      * This is a UNIX timestamp since doctrine can not persist a \DateTime-object to MySQL and ensure the time point is
      * still the same. It can ensure to maintain the time but the timezone may change which produces a different time
      * point.
      */
     scheduledFromTimestamp?: null | number;
     /**
      * This is a UNIX timestamp since doctrine can not persist a \DateTime-object to MySQL and ensure the time point is
      * still the same. It can ensure to maintain the time but the timezone may change which produces a different time
      * point.
      */
     scheduledToTimestamp?: null | number;
     nodesPagesOfTypeSortIndex?: null | number;
     /**
      * A FECL criterion which can control when this page will be rendered if it is in the scheduled state.
      */
     scheduleCriterion?: string;
     /**
      * An experiment ID from a third party system like Kameleoon
      */
     scheduledExperiment?: null | string;
}

export interface Preview {
     previewId: string;
     createdAt: any /* \DateTime */;
     node: Node;
     page: Page;
     metaData: any /* \FrontendBundle\UserBundle\Domain\MetaData */;
}

export interface ProjectConfiguration {
     projectConfigurationId: string;
     /**
      * array
      */
     configuration: any;
     metaData: any /* \Frontastic\Backstage\UserBundle\Domain\MetaData */;
     sequence: string;
     isDeleted: boolean;
}

export interface Redirect {
     redirectId: string;
     sequence: string;
     path: string;
     query?: string;
     statusCode?: number;
     /**
      * One of TARGET_TYPE_* constants
      */
     targetType: string;
     target: string;
     language?: null | string;
     metaData: any /* \Frontastic\Backstage\UserBundle\Domain\MetaData */;
     isDeleted: boolean;
}

export interface Region {
     regionId: string;
     configuration: FrontendRegionConfiguration;
     elements: Cell[];
     cells?: Cell[];
}

export interface Route {
     nodeId: string;
     route: string;
     locale?: null | string;
}

export interface Schema {
     schemaId: string;
     schemaType: string;
     schema: any;
     metaData: any /* \Frontastic\Backstage\UserBundle\Domain\MetaData */;
     sequence: string;
     isDeleted: boolean;
}

export interface Stream {
     streamId: string;
     type: string;
     name: string;
     configuration: any;
     tastics: Tastic[];
     /**
      * If a stream value was pre-loaded before executing actual stream handlers, the value will be contained here.
      */
     preloadedValue?: any;
}

export interface StreamContext {
     node: Node;
     /**
      * Can be null during sitemap generation
      */
     page: null | Page;
     context: ApiCoreContext;
     usingTastics: Tastic[];
     /**
      * Parameters given to the stream in the current request context.
      */
     parameters: any;
     /**
      * Can be null during sitemap generation
      */
     request?: any;
}

export interface Tastic {
     tasticId: string;
     tasticType: string;
     configuration: FrontendTasticConfiguration;
}

export interface ViewData {
     /**
      * Hash map of streams
      */
     stream?: any;
     /**
      * Hash map of tastic field data
      */
     tastic?: any;
}
