import ProductStreamParameters from '../../../../src/js/app/urlHandler/productStreamParameters'
import UrlState from '../../../../src/js/app/urlHandler/urlState'

test('it creates stream parameters', () => {
    const urlState = new UrlState(
        {},
        [
            {
                streamId: 'foo',
            }
        ]
    )

    expect(urlState.getStream('foo')).toBeInstanceOf(ProductStreamParameters)
})

test('it errors on missing stream', () => {
    const urlState = new UrlState(
        {},
        [
            {
                streamId: 'foo',
            }
        ]
    )

    expect(() => {
        urlState.getStream('bar')
    }).toThrow()
})
