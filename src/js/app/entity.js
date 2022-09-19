/**
 * This is the base class for data fetched from the server.
 *
 * This class provides a isCurrent() method based on an entity specific
 * time-to-live (TTL) to check if the entity should be refetched.
 */
let Entity = function (data = null, ttl = 60) {
    this.loading = !data
    this.loaded = data ? new Date() : null
    this.error = null
    this.data = data
    this.ttl = ttl

    this.isComplete = function () {
        return !(this.loading && this.error)
    }

    this.isCurrent = function () {
        if (!this.loaded || this.error) {
            return false
        }

        if (this.loaded.getTime() > ((new Date()).getTime() - this.ttl * 1000)) {
            return true
        }

        return false
    }

    this.setError = function (error) {
        this.loading = false
        this.loaded = null
        this.error = error

        return this
    }
}

Entity.purgeMap = function (map) {
    const newMap = {}
    for (let key in map) {
        if (map[key] && map[key].isCurrent && map[key].isCurrent()) {
            newMap[key] = map[key]
        }
    }

    return newMap
}

Entity.purge = function (entity) {
    if (entity && entity.isCurrent()) {
        return entity
    }

    return new Entity()
}

export default Entity
