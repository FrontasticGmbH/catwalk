import { ConfigurationSchema } from 'frontastic-common'

import configurationResolver from '../../../src/js/app/configurationResolver'

describe('configurationResolver', () => {
    it('resolves simple field values', () => {
        const schemaFixture = createSchemaFixture({
            label: 'Fixture Field',
            field: 'fixtureField',
            type: 'string',
            default: 'I am the default',
        }, {})

        const actualResult = configurationResolver(schemaFixture, {})

        expect(actualResult.fixtureField).toBe('I am the default')
    })

    it('resolves stream field data', () => {
        const schemaFixture = createSchemaFixture({
            label: 'Fixture Field',
            field: 'fixtureField',
            type: 'stream',
        }, { fixtureField: 'fooStream' })

        const actualResult = configurationResolver(
            schemaFixture,
            {
                fooStream: ['some', 'values'],
            }
        )

        expect(actualResult.fixtureField).toEqual(['some', 'values'])
    })

    it('resolves additional field data', () => {
        const schemaFixture = createSchemaFixture({
            label: 'Fixture Field',
            field: 'fixtureField',
            type: 'tree',
        }, { fixtureField: { node: 'someNode', depth: 23 } })

        const actualResult = configurationResolver(
            schemaFixture,
            {},
            {
                fixtureField: ['some', 'values'],
            }
        )

        expect(actualResult.fixtureField).toEqual(['some', 'values'])
    })
})

function createSchemaFixture (fieldDefinition, configuration = {}) {
    return new ConfigurationSchema(
        [{
            name: 'Some name',
            fields: [
                fieldDefinition
            ]
        }],
        configuration
    )
}
