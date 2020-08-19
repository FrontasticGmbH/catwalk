import Context from '../../../src/js/app/context'

test.each([
    ['environment', 'production'],
    ['locale', 'en_GB'],
    ['currency', 'EUR'],
    ['session', { loggedIn: false, user: null, message: null }],
])('it is created with sensible defaults', (property, expectedValue) => {
    let context = new Context()

    expect(context[property]).toEqual(expectedValue)
})

test.each([
    ['environment', 'development'],
    ['locale', 'de_DE'],
    ['currency', 'GB'],
    ['session', { loggedIn: true, user: { name: 'Kore' }, message: null }],
])('it is constructed from context', (property, expectedValue) => {
    let context = new Context({
        environment: 'development',
        locale: 'de_DE',
        currency: 'GB',
        session: { loggedIn: true, user: { name: 'Kore' }, message: null },
    })

    expect(context[property]).toEqual(expectedValue)
})

test.each([
    ['de', { 'language': 'de', 'territory': 'DE', 'currency': 'EUR', 'country': 'Germany', 'original': 'de' }],
    ['de_DE', { 'language': 'de', 'territory': 'DE', 'currency': 'EUR', 'country': 'Germany', 'original': 'de_DE' }],
    ['de_DE@EUR', { 'language': 'de', 'territory': 'DE', 'currency': 'EUR', 'country': 'Germany', 'original': 'de_DE@EUR' }],
    ['de_DE@euro', { 'language': 'de', 'territory': 'DE', 'currency': 'EUR', 'country': 'Germany', 'original': 'de_DE@euro' }],
    ['de_DE.UTF8@EUR', { 'language': 'de', 'territory': 'DE', 'currency': 'EUR', 'country': 'Germany', 'original': 'de_DE.UTF8@EUR' }],
    ['de_AT', { 'language': 'de', 'territory': 'AT', 'currency': 'EUR', 'country': 'Austria', 'original': 'de_AT' }],
    ['en_GB@EUR', { 'language': 'en', 'territory': 'GB', 'currency': 'EUR', 'country': 'United Kingdom', 'original': 'en_GB@EUR' }],
])('it is constructed from context', (locale, expectedProperties) => {
    let context = new Context({
        locale: locale,
    })

    expect(context.getLanguage()).toEqual(expectedProperties.language)
    expect(context.getTerritory()).toEqual(expectedProperties.territory)
    expect(context.getCurrency()).toEqual(expectedProperties.currency)
    expect(context.getCountry()).toEqual(expectedProperties.country)
    expect(context.getOriginal()).toEqual(expectedProperties.original)
})

test.each([
    ['undefinedFeature', false],
    ['activeFeature', true],
    ['deactivatedFeature', false],
])('returns if feature is active', (feature, hasFeature) => {
    let context = new Context({
        featureFlags: {
            activeFeature: true,
            deactivatedFeature: false,
        },
    })

    expect(context.hasFeature(feature)).toBe(hasFeature)
})
