
import {
    Customer,
    Project,
} from '@frontastic/common/src/js/types/replicator'

import {
    Session,
} from '@frontastic/common/src/js/types/account'

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

export interface Context {
     environment?: string;
     customer?: Customer;
     project?: Project;
     projectConfiguration?: any;
     projectConfigurationSchema?: any;
     locale?: string;
     currency?: string;
     routes?: string[];
     session?: Session;
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
