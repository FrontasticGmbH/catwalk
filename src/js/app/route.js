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

    for (let property in route) {
        this[property] = route[property]
    }

    this.query = query
    this.urlParameters = _.extend({}, route.parameters)
    this.parameters = _.extend({}, this.urlParameters, this.query)
    this.historyState = historyState
}

export default Route
