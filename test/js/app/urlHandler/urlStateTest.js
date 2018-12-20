import ProductStreamParameters from '../../../../src/js/app/urlHandler/productStreamParameters'
import UrlState from '../../../../src/js/app/urlHandler/urlState'

test('it creates stream parameters', () => {
    const urlState = new UrlState(
        {},
        [
            { streamId: 'foo', }
        ]
    )

    expect(urlState.getStream('foo')).toBeInstanceOf(ProductStreamParameters)
})

test('it errors on missing stream', () => {
    const urlState = new UrlState(
        {},
        [
            { streamId: 'foo', }
        ]
    )

    expect(() => {
        urlState.getStream('bar')
    }).toThrow()
})

test('it collects parameters', () => {
    const urlState = new UrlState(
        {
            someOther: 23,
            s: {
                foo: {
                    offset: 42,
                },
            },
        },
        [
            { streamId: 'foo', },
            { streamId: 'bar', },
        ]
    )

    expect(urlState.getParameters()).toEqual({
        someOther: 23,
        s: {
            foo: {
                offset: 42,
            },
            bar: {}
        },
    })
})

test('it sets nocrawl parameter', () => {
    const urlState = new UrlState(
        {
            s: {
                foo: {
                    facets: { color: 'red' }
                },
            },
        },
        [
            { streamId: 'foo', },
        ]
    )

    expect(urlState.getParameters()).toMatchObject({
        nocrawl: '1',
    })
})

test('it remove nocrawl parameter', () => {
    const urlState = new UrlState(
        {
            nocrawl: '1',
            s: {
                foo: {
                    offset: 23
                },
            },
        },
        [
            { streamId: 'foo', },
        ]
    )

    expect(urlState.getParameters()).not.toMatchObject({
        nocrawl: '1',
    })
})
