import _ from 'lodash'

import UrlHandler from '../../../src/js/app/urlHandler'
import UrlState from '../../../src/js/app/urlHandler/urlState'

it('it creates new parameters', () => {

    const inputParameters = {
        some: 'value'
    }
    const originalInputParemeters = _.cloneDeep(inputParameters)

    const routeMock = jest.genMockFromModule('../../../src/js/app/route').default
    routeMock.query = inputParameters

    const handler = new UrlHandler(
        routeMock,
        [
            { streamId: 'someStream' },
        ]
    )

    const outputParameters = handler.deriveParameters((urlState) => {
        expect(urlState).toBeInstanceOf(UrlState)

        urlState.getStream('someStream').setOffset(23)
    })

    expect(outputParameters).toMatchObject({
        some: 'value',
        s: {
            someStream: {
                offset: 23,
            },
        },
    })

    // Should not be the same object
    expect(outputParameters).not.toBe(inputParameters)

    // No change to inputParameters
    expect(inputParameters).toEqual(originalInputParemeters)
})
