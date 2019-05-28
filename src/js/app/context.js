
let Context = function (context = {}) {
    this.environment = 'production'
    this.locale = 'en_GB'
    this.currency = 'EUR'
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

    this.fromParameters = function (parameters) {
    }

    this.toParameters = function () {
        return {
            // @TODO: Re-add *non-default* essential context parameters, there
            // a none yet (languiage selections, currency-selections, …)
        }
    }

    this.isDevelopment = function () {
        return this.environment === 'development' || this.environment === 'dev'
    }

    this.getLanguage = function () {
        return this.locale.substr(0, this.locale.indexOf('_'))
    }

    this.getCountry = function () {
        return this.locale.substr(this.locale.indexOf('_') + 1, 2)
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
}

export default Context
