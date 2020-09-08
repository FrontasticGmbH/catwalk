
import {
    Configuration as FrontendConfiguration,
} from '..'

export interface Configuration extends FrontendConfiguration {
     flexDirection?: string;
     flexWrap?: string;
     justifyContent?: string;
     alignItems?: string;
     alignContent?: string;
}
