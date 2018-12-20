import ProductStreamParameters from '../../../../src/js/app/urlHandler/productStreamParameters'
import UrlState from '../../../../src/js/app/urlHandler/urlState'

test('is sets filter value correctly', () => {
    let parameters = new ProductStreamParameters({})

    parameters.setFilter('color', 'red')

    expect(parameters.getParameters()).toEqual({
        facets: {
            color: 'red',
        },
    })
})

test('it removes filter value correctly', () => {
    let parameters = new ProductStreamParameters({
        facets: {
            color: 'red',
        },
    })

    parameters.removeFilter('color')

    expect(parameters.getParameters()).toEqual({})
})

test('it sets offset correctly', () => {
    let parameters = new ProductStreamParameters({})

    parameters.setOffset(23)

    expect(parameters.getParameters()).toEqual({
        offset: 23,
    })
})

test('it removes offset correctly', () => {
    let parameters = new ProductStreamParameters({})

    parameters.setOffset()

    expect(parameters.getParameters()).toEqual({})
})

test('it detects filters as non-crawlable', () => {
    let parameters = new ProductStreamParameters({})

    parameters.setFilter('color', 'red')

    expect(parameters.isCrawlable()).toBe(false)
})

test('it detects filters as non-crawlable with offset', () => {
    let parameters = new ProductStreamParameters({})

    parameters.setFilter('color', 'red')
    parameters.setOffset(23)

    expect(parameters.isCrawlable()).toBe(false)
})

test('it detects empty as crawlable', () => {
    let parameters = new ProductStreamParameters({})

    expect(parameters.isCrawlable()).toBe(true)
})

test('it detects offset only as crawlable', () => {
    let parameters = new ProductStreamParameters({})

    parameters.setOffset(23)

    expect(parameters.isCrawlable()).toBe(true)
})
