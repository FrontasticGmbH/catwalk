import Router from '../../../src/js/app/router'

test.each([
    [{ urlParameter: 'test' }, '/path/to/test', ''],
    [{ urlParameter: 'test', foo: 23 }, '/path/to/test', '?foo=23'],
    [{}, '/', ''],
])('it is created with sensible defaults', (parameters, url, query) => {
    let router = new Router({}, { 'My.Test.Route': { path: '/path/to/{urlParameter}' } })
    expect(router.location('My.Test.Route', parameters)).toEqual({ pathname: url, search: query })
})

test('Route not found', () => {
    let router = new Router({}, { 'My.Test.Route': { path: '/path/to/{urlParameter}' } })

    expect(() => { return router.location('Undefined.Route', {}) }).toThrow(
        new Error('Route Undefined.Route not defined, did you mean any of these: My.Test.Route')
    )
})

test('Route not found with sorted suggestions', () => {
    let router = new Router({}, {
        'My.Test.Route': { path: '/path/to/{urlParameter}' },
        'Undefined.Route.': { path: '/path/to/{urlParameter}' },
    })

    expect(() => { return router.location('Undefined.Route', {}) }).toThrow(
        new Error('Route Undefined.Route not defined, did you mean any of these: Undefined.Route., My.Test.Route')
    )
})

test('get react route', () => {
    let router = new Router({}, { 'My.Test.Route': { path: '/path/to/{urlParameter}' } })
    expect(router.reactRoute('My.Test.Route')).toEqual('/path/to/:urlParameter')
})
