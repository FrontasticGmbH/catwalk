import UrlContext from '../../../src/js/app/urlContext'

it('it filters _ parameters', () => {
    expect(UrlContext.getActionParameters({
        relevantParameter: 'foo',
        _irrelevantParameter: 'bar',
    })).toEqual({
        relevantParameter: 'foo',
    })
})

it('it filters the s parameter', () => {
    expect(UrlContext.getActionParameters({
        relevantParameter: 'foo',
        s: {
            foo: 'bar',
        },
    })).toEqual({
        relevantParameter: 'foo',
    })
})

it('it does not filter parameters starting with s', () => {
    expect(UrlContext.getActionParameters({
        server: 'foo',
    })).toEqual({
        server: 'foo',
    })
})

it('it filters the nocrawl parameter', () => {
    expect(UrlContext.getActionParameters({
        relevantParameter: 'foo',
        nocrawl: '1',
    })).toEqual({
        relevantParameter: 'foo',
    })
})
