/* eslint no-console: 0 */
import fs from 'fs'
import { filterPayload } from './serverLoggingFilters'

const defaultLogFilePath = '/var/log/frontastic/json.log'
const pathToJsonLogfile = process.env.JSON_LOG_PATH || defaultLogFilePath
const spawnedByCli = process.env.SPAWNED_BY === 'FCLI'

/**
 * Makes sure additional logged data is always an empty array or a string to not
 * confuse logstash which parses the json.log in production.
 */
export const formatAdditionalData = (args) => {
    if (Array.isArray(args)) {
        if (args.length === 0) {
            return args
        } else if (args.length === 1) {
            return []
        } else {
            return args.map((value) => {
                if (typeof value === 'string' || value instanceof String) {
                    return value
                } else {
                    return JSON.stringify(value)
                }
            })
        }
    } else {
        return ['Error! Given data was not an array.', JSON.stringify(args)]
    }
}

/**
 * Will overwrite the console.log command in order to activate logging to elk
 */
export const activateFileLogging = (logSource, project) => {
    const originalConsoleLog = console.log
    const originalConsoleInfo = console.info
    const originalConsoleWarn = console.warn
    const originalConsoleError = console.error
    const originalConsoleDebug = console.debug

    const getPayload = (args, severity) => {
        return {
            message: typeof args[0] === 'string' ? args[0] : JSON.stringify(args[0]),
            severity: severity,
            logSource: logSource,
            project: project,
            additionalData: formatAdditionalData(args),
            '@timestamp': (new Date()).toISOString(),
        }
    }

    /**
    * If server:watch/start is called by the CLI without specifying a JSON_LOG_PATH it wipes this function
    * to avoid the error of the file not existing on the user's PC, this can be revisited later if we want
    * to log to a local file on the user's pc by instead passing the JSON_LOG_PATH env through the CLI with
    * the absolute path to the user's repo (or wherever) and creating that file.
    */
    const writePayloadToFile = (spawnedByCli && pathToJsonLogfile === defaultLogFilePath) ? payload => { } : payload => {
        fs.appendFile(pathToJsonLogfile, JSON.stringify(payload) + '\n', (err) => {
            if (err) {
                originalConsoleError(spawnedByCli ? JSON.stringify(err) : err)
            }
        })
    }

    console.log = (...args) => {
        if (args.length > 0) {
            const payload = getPayload(args, 'INFO')
            if (!filterPayload(payload)) {
                originalConsoleLog(spawnedByCli ? JSON.stringify(payload) : payload)
                writePayloadToFile(payload)
            }
        }
    }
    console.info = (...args) => {
        if (args.length > 0) {
            const payload = getPayload(args, 'INFO')
            if (!filterPayload(payload)) {
                originalConsoleInfo(spawnedByCli ? JSON.stringify(payload) : payload)
                writePayloadToFile(payload)
            }
        }
    }
    console.warn = (...args) => {
        if (args.length > 0) {
            const payload = getPayload(args, 'WARNING')
            if (!filterPayload(payload)) {
                originalConsoleWarn(spawnedByCli ? JSON.stringify(payload) : payload)
                writePayloadToFile(payload)
            }
        }
    }
    console.error = console.exception = (...args) => {
        if (args.length > 0) {
            const payload = getPayload(args, 'ERROR')
            if (!filterPayload(payload)) {
                originalConsoleError(spawnedByCli ? JSON.stringify(payload) : payload)
                writePayloadToFile(payload)
            }
        }
    }
    console.debug = (...args) => {
        if (args.length > 0) {
            const payload = getPayload(args, 'DEBUG')
            if (!filterPayload(payload)) {
                originalConsoleDebug(spawnedByCli ? JSON.stringify(payload) : payload)
                writePayloadToFile(payload)
            }
        }
    }
    console.trace = (...args) => {
        if (args.length > 0) {
            console.error(args)
        }
    }
}
