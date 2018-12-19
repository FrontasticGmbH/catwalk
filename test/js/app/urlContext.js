import UrlContext from '../../../src/js/app/urlContext'

it('it filters _ parameters', () => {
    expect(UrlContext.getActionParameters({
        relevantParameter: 'foo',
        _irrelevantParameter: 'bar',
    })).toEqual({
        relevantParameter: 'foo',
    })
})
