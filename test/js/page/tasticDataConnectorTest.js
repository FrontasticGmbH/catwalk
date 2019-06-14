import { Tastic } from 'frontastic-common'

import tasticDataConnector from '../../../src/js/page/tasticDataConnector'

describe('tasticDataConnector', () => {
    it('resolves simple field values, even without stream/tastic data', () => {
        const tasticFixture = createTasticFixture({
            label: 'Fixture Field',
            field: 'fixtureField',
            type: 'string',
            default: 'I am the default',
        })

        const actualResult = tasticDataConnector(
            createGlobalStateFixture(),
            { tastic: tasticFixture, data: {} }
        )

        expect(actualResult.resolved.fixtureField).toBe('I am the default')
    })

    it('resolves from stream data', () => {
        const tasticFixture = createTasticFixture({
            label: 'Fixture Field',
            field: 'fixtureField',
            type: 'stream',
        }, { fixtureField: 'fooStream' })

        const actualResult = tasticDataConnector(
            createGlobalStateFixture(),
            {
                tastic: tasticFixture,
                data: {
                    stream: {
                        fooStream: ['some', 'values'],
                    },
                },
            }
        )

        expect(actualResult.resolved.fixtureField).toEqual(['some', 'values'])
    })

    it('resolves from tastic field data', () => {
        const tasticFixture = createTasticFixture({
            label: 'Fixture Field',
            field: 'fixtureField',
            type: 'tree',
        }, { fixtureField: { node: 'someNode', depth: 23 } })

        const actualResult = tasticDataConnector(
            createGlobalStateFixture(),
            {
                tastic: tasticFixture,
                data: {
                    tastic: {
                        '123abc': {
                            fixtureField: ['some', 'values'],
                        },
                    },
                },
            }
        )

        expect(actualResult.resolved.fixtureField).toEqual(['some', 'values'])
    })
})

function createTasticFixture (fieldDefinition, configuration = {}) {
    return new Tastic({
        tasticId: '123abc',
        tasticType: 'some-tastic-type',
        schema: [
            {
                name: 'Fixture Schema',
                fields: [
                    fieldDefinition,
                ],
            },
        ],
        configuration: configuration,
    })
}

function createGlobalStateFixture () {
    return {
        app: {},
    }
}
