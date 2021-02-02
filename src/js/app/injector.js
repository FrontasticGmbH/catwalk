class ComponentInjector {
    static registry = {}

    static reducer = {}

    static middleware = []

    static registerReducer (name, reducer) {
        if (!name.match(/^[a-z]+-/)) {
            throw new Error('The reducer should be prefixed with your customer name, like "demo-reducer".')
        }

        this.reducer[name] = reducer
    }

    static getReducer () {
        return this.reducer
    }

    static registerMiddleware (middleware) {
        this.middleware.push(middleware)
    }

    static getMiddlewares () {
        return this.middleware
    }

    static get (name) {
        return this.registry[name] || null
    }

    static set (name, component) {
        this.registry[name] = component
    }

    static return (name, component) {
        return this.registry[name] || component
    }
}

export default ComponentInjector
