class ComponentInjector {
    static registry = {}

    static get (name) {
        return this.registry[name]
    }

    static set (name, component) {
        this.registry[name] = component
    }

    static return (name, component) {
        return this.registry[name] || component
    }
}

export default ComponentInjector
