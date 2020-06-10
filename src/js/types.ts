
import {
    ReplicatorNS,
    AccountNS,
    ProductNS,
} from '@frontastic/common/src/js/types'

export namespace ApiCoreNS {

    export interface App {
         appId?: string;
         identifier?: string;
         sequence?: string;
         name?: string;
         description?: string;
         configurationSchema?: any /* \StdClass */;
         environment?: string;
         metaData?: any /* \Frontastic\UserBundle\Domain\MetaData */;
    }

    export interface AppRepository {
         app?: string;
         sequence?: string;
    }

    export namespace AppNS {

        export interface FeatureFlag {
             locale?: string;
             dataId?: string;
             sequence?: string;
             isDeleted?: boolean;
             key?: string;
             on?: boolean;
             onStaging?: boolean;
             onDevelopment?: boolean;
             description?: any /* \Frontastic\Catwalk\ApiCoreBundle\Domain\App\text */;
        }

        export interface Storefinder {
             locale?: string;
             dataId?: string;
             sequence?: string;
             isDeleted?: boolean;
             name?: string;
             image?: any;
             description?: any /* \Frontastic\Catwalk\ApiCoreBundle\Domain\App\text */;
             people?: any;
             street?: string;
             street_ammendment?: string;
             zip?: string;
             city?: string;
             state?: string;
             country?: string;
             latitude?: number;
             longitude?: number;
        }

        export interface Teaser {
             locale?: string;
             dataId?: string;
             sequence?: string;
             isDeleted?: boolean;
             identifier?: string;
             image1?: any;
             text2?: any /* \Frontastic\Catwalk\ApiCoreBundle\Domain\App\text */;
             text3?: any /* \Frontastic\Catwalk\ApiCoreBundle\Domain\App\text */;
             image4?: any;
        }
    }

    export interface Context {
         environment?: string;
         customer?: ReplicatorNS.Customer;
         project?: ReplicatorNS.Project;
         projectConfiguration?: any;
         projectConfigurationSchema?: any;
         locale?: string;
         currency?: string;
         routes?: string[];
         session?: AccountNS.Session;
         featureFlags?: any;
         host?: string;
    }

    export interface Tastic {
         tasticId?: string;
         tasticType?: string;
         sequence?: string;
         name?: string;
         description?: string;
         configurationSchema?: any /* \StdClass */;
         environment?: string;
         metaData?: any /* \Frontastic\UserBundle\Domain\MetaData */;
         isDeleted?: boolean;
    }
}

export namespace FrontendNS {

    export interface Cell {
         cellId?: string;
         configuration?: FrontendNS.CellNS.Configuration;
         customConfiguration?: null | any /* \stdClass */;
         tastics?: FrontendNS.Tastic[];
    }

    export namespace CellNS {

        export interface Configuration extends FrontendNS.Configuration {
             size?: any;
        }
    }

    export interface Configuration {
         mobile?: boolean;
         tablet?: boolean;
         desktop?: boolean;
    }

    export interface Facet extends ProductNS.ProductApiNS.FacetDefinition {
         facetId?: string;
         sequence?: string;
         sort?: number;
         isEnabled?: boolean;
         label?: null | any;
         urlIdentifier?: string;
         facetOptions?: any;
         metaData?: any /* \Frontastic\Catwalk\FrontendBundle\Domain\MetaData */;
         isDeleted?: boolean;
    }

    export interface Layout {
         layoutId?: string;
         sequence?: string;
         name?: string;
         description?: string;
         image?: string;
         regions?: string[];
         metaData?: any /* \Frontastic\UserBundle\Domain\MetaData */;
    }

    export interface MasterPageMatcherRules {
         rulesId?: string;
         rules?: any;
         sequence?: string;
         metaData?: any /* \Frontastic\UserBundle\Domain\MetaData */;
    }

    export interface Node {
         nodeId?: string;
         isMaster?: boolean;
         sequence?: string;
         configuration?: any;
         streams?: FrontendNS.Stream[];
         name?: string;
         path?: string[];
         depth?: number;
         sort?: number;
         children?: FrontendNS.Node[];
         metaData?: any /* \Frontastic\Backstage\UserBundle\Domain\MetaData */;
         error?: null | string;
         isDeleted?: boolean;
    }

    export interface Page {
         pageId?: string;
         sequence?: string;
         node?: FrontendNS.Node;
         layoutId?: string;
         regions?: FrontendNS.Region[];
         metaData?: any /* \Frontastic\UserBundle\Domain\MetaData */;
         isDeleted?: boolean;
         state?: string;
         scheduledFromTimestamp?: null | number;
         scheduledToTimestamp?: null | number;
         nodesPagesOfTypeSortIndex?: null | number;
         scheduleCriterion?: string;
    }

    export namespace PageMatcherNS {

        export interface PageMatcherContext {
             entity?: null | any;
             categoryId?: null | string;
             productId?: null | string;
             contentId?: null | string;
             search?: null | string;
             cart?: null | any;
             checkout?: null | any;
             checkoutFinished?: null | any;
             orderId?: null | string;
             account?: null | any;
             accountForgotPassword?: null | any;
             accountConfirm?: null | any;
             accountProfile?: null | any;
             accountAddresses?: null | any;
             accountOrders?: null | any;
             accountWishlists?: null | any;
             accountVouchers?: null | any;
             error?: null | any;
        }
    }

    export interface Preview {
         previewId?: string;
         createdAt?: any /* \DateTime */;
         node?: FrontendNS.Node;
         page?: FrontendNS.Page;
         metaData?: any /* \FrontendBundle\UserBundle\Domain\MetaData */;
    }

    export interface ProjectConfiguration {
         projectConfigurationId?: string;
         configuration?: any;
         metaData?: any /* \Frontastic\Backstage\UserBundle\Domain\MetaData */;
         sequence?: string;
         isDeleted?: boolean;
    }

    export interface Redirect {
         redirectId?: string;
         sequence?: string;
         path?: string;
         query?: string;
         targetType?: string;
         target?: string;
         language?: null | string;
         metaData?: any /* \Frontastic\Backstage\UserBundle\Domain\MetaData */;
         isDeleted?: boolean;
    }

    export interface Region {
         regionId?: string;
         configuration?: FrontendNS.RegionNS.Configuration;
         elements?: FrontendNS.Cell[];
         cells?: FrontendNS.Cell[];
    }

    export namespace RegionNS {

        export interface Configuration extends FrontendNS.Configuration {
             flexDirection?: string;
             flexWrap?: string;
             justifyContent?: string;
             alignItems?: string;
             alignContent?: string;
        }
    }

    export interface Route {
         nodeId?: string;
         route?: string;
         locale?: null | string;
    }

    export interface Schema {
         schemaId?: string;
         schemaType?: string;
         schema?: any;
         metaData?: any /* \Frontastic\Backstage\UserBundle\Domain\MetaData */;
         sequence?: string;
         isDeleted?: boolean;
    }

    export interface Stream {
         streamId?: string;
         type?: string;
         name?: string;
         configuration?: any;
         tastics?: FrontendNS.Tastic[];
    }

    export interface StreamContext {
         node?: FrontendNS.Node;
         page?: FrontendNS.Page;
         context?: ApiCoreNS.Context;
         usingTastics?: FrontendNS.Tastic[];
         parameters?: any;
    }

    export interface Tastic {
         tasticId?: string;
         tasticType?: string;
         configuration?: FrontendNS.TasticNS.Configuration;
    }

    export namespace TasticNS {

        export interface Configuration extends FrontendNS.Configuration {
        }
    }

    export interface ViewData {
         stream?: any;
         tastic?: any;
    }
}
