import Context from '../../../src/js/app/context'

test.each([
    ['getEnvironment', 'production'],
    ['getOriginal', 'en_GB'],
    ['getCurrency', 'EUR'],
    ['getSession', { loggedIn: false, user: null, message: null }],
])('it is created with sensible defaults', (property, expectedValue) => {
    let context = new Context()

    expect(context[property]()).toEqual(expectedValue)
})

test.each([
    ['environment', 'development'],
    ['locale', 'de_DE'],
    ['currency', 'EUR'],
    ['session', { loggedIn: true, user: { name: 'Kore' }, message: null }],
])('it is constructed from context', (property, expectedValue) => {
    let context = new Context({
        environment: 'development',
        locale: 'de_DE',
        currency: 'EUR',
        session: { loggedIn: true, user: { name: 'Kore' }, message: null },
    })

    expect(context[property]).toEqual(expectedValue)
})

test.each([
    ['de', { 'language': 'de', 'territory': 'DE', 'currency': 'EUR', 'original': 'de' }],
    ['de_DE', { 'language': 'de', 'territory': 'DE', 'currency': 'EUR', 'original': 'de_DE' }],
    ['de_DE@EUR', { 'language': 'de', 'territory': 'DE', 'currency': 'EUR', 'original': 'de_DE@EUR' }],
    ['de_DE@euro', { 'language': 'de', 'territory': 'DE', 'currency': 'EUR', 'original': 'de_DE@euro' }],
    ['de_DE.UTF8@EUR', { 'language': 'de', 'territory': 'DE', 'currency': 'EUR', 'original': 'de_DE.UTF8@EUR' }],
    ['de_AT', { 'language': 'de', 'territory': 'AT', 'currency': 'EUR', 'original': 'de_AT' }],
    ['en_GB@GDB', { 'language': 'en', 'territory': 'GB', 'currency': 'GDB', 'original': 'en_GB@GDB' }],
    // Since the server has the authority on the locale we expect the client to even parse unknown locale data
    ['aa_AA@AAA', { 'language': 'aa', 'territory': 'AA', 'currency': 'AAA', 'original': 'aa_AA@AAA' }],
])('it is constructed from POSIX locale', (locale, expectedProperties) => {
    let context = new Context({
        locale: locale,
    })

    expect(context.getLanguage()).toEqual(expectedProperties.language)
    expect(context.getTerritory()).toEqual(expectedProperties.territory)
    expect(context.getCurrency()).toEqual(expectedProperties.currency)
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

test.each([
    ['prod', 'isProduction', true],
    ['prod', 'isStaging', false],
    ['prod', 'isDevelopment', false],
    ['production', 'isProduction', true],
    ['production', 'isStaging', false],
    ['production', 'isDevelopment', false],
    ['staging', 'isProduction', false],
    ['staging', 'isStaging', true],
    ['staging', 'isDevelopment', false],
    ['development', 'isProduction', false],
    ['development', 'isStaging', false],
    ['development', 'isDevelopment', true],
    ['dev', 'isProduction', false],
    ['dev', 'isStaging', false],
    ['dev', 'isDevelopment', true],
    ['unknown', 'isProduction', true],
    ['unknown', 'isStaging', false],
    ['unknown', 'isDevelopment', false],
])('environment functions return correct boolean', (environment, method, expectedValue) => {
    let context = new Context({ environment })
    expect(context[method]()).toBe(expectedValue)
})
