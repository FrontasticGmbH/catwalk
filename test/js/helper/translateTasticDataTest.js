import { ConfigurationSchema } from '@frontastic/common'
import { translateTasticData } from '../../../src/js/helper/translateTasticData'

describe('translateTasticData', () => {
    it('should work with a simple tastic schema', () => {
        const data = {
            text: {
                'de_DE': 'This is some german text',
                'en_GB': 'This is some english text',
            },
        }

        const tasticSchema = new ConfigurationSchema([{
            name: 'Content',
            fields: [{
                label: 'Content',
                field: 'text',
                type: 'markdown',
                default: '* Enter\n* some\n* Markdown',
                translatable: true,
            }],
        }])

        const context = {
            locale: 'de_DE',
            project: {
                defaultLanguage: 'en_GB',
            },
        }

        const expectedTranslatedData = {
            text: 'This is some german text',
        }

        expect(translateTasticData(data, tasticSchema, context)).toEqual(expectedTranslatedData)
    })

    it('should work with a simple tastic schema and fallback to different locale', () => {
        const data = {
            text: {
                'en_GB': 'This is some english text',
            },
        }

        const tasticSchema = new ConfigurationSchema([{
            name: 'Content',
            fields: [{
                label: 'Content',
                field: 'text',
                type: 'markdown',
                default: '* Enter\n* some\n* Markdown',
                translatable: true,
            }],
        }])

        const context = {
            locale: 'de_DE',
            project: {
                defaultLanguage: 'en_GB',
            },
        }

        const expectedTranslatedData = {
            text: 'This is some english text',
        }

        expect(translateTasticData(data, tasticSchema, context)).toEqual(expectedTranslatedData)
    })

    it('should work with group in tastic schema', () => {
        const data = {
            content: [{
                text: {
                    'de_DE': 'This is some german text',
                    'en_GB': 'This is some english text',
                },
            }],
        }

        const tasticSchema = new ConfigurationSchema([{
            name: 'Content',
            fields: [{
                label: 'Content',
                field: 'content',
                type: 'group',
                min: 0,
                itemLabelField: 'text',
                fields: [{
                    label: 'Markdown',
                    field: 'text',
                    type: 'markdown',
                    default: '* Enter\n* some\n* Markdown',
                    translatable: true,
                }],
            }],
        }])

        const context = {
            locale: 'de_DE',
            project: {
                defaultLanguage: 'en_GB',
            },
        }

        const expectedTranslatedData = {
            content: [{
                text: 'This is some german text',
            }],
        }

        expect(translateTasticData(data, tasticSchema, context)).toEqual(expectedTranslatedData)
    })

    it('should work with fields without a default in tastic schema', () => {
        const data = {
            title: 'Hello World!',
            content: [{
                text: {
                    'de_DE': 'This is some german text',
                    'en_GB': 'This is some english text',
                },
            }],
        }

        const tasticSchema = new ConfigurationSchema([{
            name: 'Content',
            fields: [{
                label: 'Title',
                field: 'title',
                type: 'string',
                translatable: false,
            }, {
                label: 'Content',
                field: 'content',
                type: 'group',
                min: 0,
                itemLabelField: 'text',
                fields: [{
                    label: 'Markdown',
                    field: 'text',
                    type: 'markdown',
                    default: '* Enter\n* some\n* Markdown',
                    translatable: true,
                }],
            }],
        }])

        const context = {
            locale: 'de_DE',
            project: {
                defaultLanguage: 'en_GB',
            },
        }

        const expectedTranslatedData = {
            title: 'Hello World!',
            content: [{
                text: 'This is some german text',
            }],
        }

        expect(translateTasticData(data, tasticSchema, context)).toEqual(expectedTranslatedData)
    })

    it('should work with fields without a translation and numbers in tastic schema', () => {
        const data = {
            headlineSize: 1,
            title: 'Hello World!',
            content: [{
                text: 'This is some english text',
            }],
        }

        const tasticSchema = new ConfigurationSchema([{
            name: 'Content',
            fields: [{
                label: 'Headline',
                field: 'headlineSize',
                type: 'number',
            }, {
                label: 'Title',
                field: 'title',
                type: 'string',
                translatable: false,
            }, {
                label: 'Content',
                field: 'content',
                type: 'group',
                min: 0,
                itemLabelField: 'text',
                fields: [{
                    label: 'Markdown',
                    field: 'text',
                    type: 'markdown',
                    default: '* Enter\n* some\n* Markdown',
                    translatable: true,
                }],
            }],
        }])

        const context = {
            locale: 'de_DE',
            project: {
                defaultLanguage: 'en_GB',
            },
        }

        const expectedTranslatedData = {
            headlineSize: 1,
            title: 'Hello World!',
            content: [{
                text: 'This is some english text',
            }],
        }

        expect(translateTasticData(data, tasticSchema, context)).toEqual(expectedTranslatedData)
    })

    it('should work also with fields without a translation and translatable numbers in tastic schema', () => {
        const data = {
            headlineSize: {
                'de_DE': 2,
                'en_GB': 1,
            },
            title: 'Hello World!',
            content: [{
                text: 'This is some english text',
            }],
        }

        const tasticSchema = new ConfigurationSchema([{
            name: 'Content',
            fields: [{
                label: 'Headline',
                field: 'headlineSize',
                type: 'number',
                translatable: true,
            }, {
                label: 'Title',
                field: 'title',
                type: 'string',
                translatable: false,
            }, {
                label: 'Content',
                field: 'content',
                type: 'group',
                min: 0,
                itemLabelField: 'text',
                fields: [{
                    label: 'Markdown',
                    field: 'text',
                    type: 'markdown',
                    default: '* Enter\n* some\n* Markdown',
                    translatable: true,
                }],
            }],
        }])

        const context = {
            locale: 'de_DE',
            project: {
                defaultLanguage: 'en_GB',
            },
        }

        const expectedTranslatedData = {
            headlineSize: 2,
            title: 'Hello World!',
            content: [{
                text: 'This is some english text',
            }],
        }

        expect(translateTasticData(data, tasticSchema, context)).toEqual(expectedTranslatedData)
    })
})
