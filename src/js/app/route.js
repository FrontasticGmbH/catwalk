import _ from 'lodash'

let Route = function (route, query, historyState = null) {
    this.get = function (parameter, fallback = null) {
        if (this.parameters &&
            this.parameters[parameter]) {
            return this.parameters[parameter]
        }

        return fallback
    }

    this.has = function (parameter) {
        return (this.parameters && this.parameters[parameter])
    }

    this.route = route.route
    this.query = query
    this.urlParameters = _.cloneDeep(route.parameters)
    this.parameters = { ...route.parameters, ...this.query }
    this.historyState = historyState
}

export default Route
