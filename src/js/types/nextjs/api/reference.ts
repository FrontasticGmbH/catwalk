/* This interface describes the data structure a component gets back for fields
 * of type "reference" in the component schema, when an external link is
 * configured in the studio. See
 * https://docs.frontastic.cloud/docs/frontastic-schemas#schema-field-types
 */
export interface ComponentValueLinkReference {
    type: 'link';
    link: string;
    target?: string;
    openInNewWindow: boolean;
}

/* This interface describes the data structure a component gets back for fields
 * of type "reference" in the component schema, when a page folder is configured
 * in the studio. See
 * https://docs.frontastic.cloud/docs/frontastic-schemas#schema-field-types
 */
export interface ComponentValuePageFolderReference {
    type: 'page-folder';
    pageFolder: {
        pageFolderId: string;
        name: string;
        _urls: {
            [locale: string]: string;
        };
        _url: string;
    };
    openInNewWindow: boolean;
}

export type ComponentValueReference = ComponentValueLinkReference | ComponentValuePageFolderReference;
