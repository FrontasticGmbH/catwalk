import { deprecate } from '@frontastic/common'

let Locale = function () {
    this.language = null

    this.territory = null

    this.currency = null

    this.original = null

    const LOCALE = /^(?<language>[a-z]{2,})(?:_(?<territory>[A-Z]{2,}))?(?:\.(?<codeset>[A-Z0-9_+-]+))?(?:@(?<modifier>[A-Za-z]+))?$/

    const DEFAULT_CURRENCY = 'EUR'

    this.createFromPosix = function (locale) {
        let matches = locale.match(LOCALE)
        if (matches === null) {
            throw new Error(
                'The given locale $locale does not match <language[_territory[.codeset]][@modifier]> (en_DE.UTF-8@EUR)'
            )
        }

        this.language = matches.groups.language
        this.territory = matches.groups.territory || this.guessTerritory(matches.groups.language)
        this.currency = matches.groups.modifier ?
            this.modifierToCurrency(matches.groups.modifier) :
            DEFAULT_CURRENCY

        return this
    }

    this.guessTerritory = function (language) {
        return language.toUpperCase()
    }

    this.modifierToCurrency = function (modifier) {
        switch (modifier) {
        case 'euro':
            return 'EUR'
        default:
            return modifier || DEFAULT_CURRENCY
        }
    }
}

let Context = function (context = {}) {
    this.environment = 'production'
    this.locale = 'en_GB'
    this.currency = null
    this.session = {
        loggedIn: false,
        user: null,
        message: null,
    }
    this.featureFlags = {}

    this.hasFeature = function (feature) {
        return !!this.featureFlags[feature]
    }

    this.isLoggedIn = function () {
        return this.session && this.session.loggedIn
    }

    this.fromParameters = function () {
    }

    this.toParameters = function () {
        return {
            // @TODO: Re-add *non-default* essential context parameters, there
            // a none yet (languiage selections, currency-selections, …)
        }
    }

    this.getEnvironment = function () {
        return this.environment
    }

    this.isDevelopment = function () {
        return this.environment === 'development' || this.environment === 'dev'
    }

    this.isStaging = function () {
        return this.environment === 'staging'
    }

    this.isProduction = function () {
        return this.environment !== 'development' &&
            this.environment !== 'dev' &&
            this.environment !== 'staging'
    }

    this.getLanguage = function () {
        return this.parsedLocale.language
    }

    /**
     * @deprecated Use getTerritory() instead
     */
    this.getCountry = function () {
        deprecate('getCountry() should actually return a country and not the territory – please use getTerritory() instead.')
        return this.parsedLocale.territory
    }

    this.getTerritory = function () {
        return this.parsedLocale.territory
    }

    this.getCurrency = function () {
        return this.currency || this.parsedLocale.currency
    }

    this.getOriginal = function () {
        return this.locale
    }

    this.getSession = function () {
        return this.session
    }

    this.update = function (context = {}) {
        let properties = {}

        for (const [property, value] of Object.entries(this)) {
            properties[property] = value
        }

        if (context.project && (typeof context.project === 'string')) {
            properties.project.projectId = context.project
            delete context.project
        }

        for (const [property, value] of Object.entries(context)) {
            properties[property] = value
        }

        if (context.project && (typeof context.project === 'string')) {
            properties.project.projectId = context.project
        }

        return new Context(properties)
    }

    for (const [property, value] of Object.entries(context)) {
        this[property] = value
    }

    if (!this.project && this.customer) {
        this.project = this.customer.projects[0]
    }

    this.parsedLocale = new Locale().createFromPosix(this.locale)
}

export default Context
