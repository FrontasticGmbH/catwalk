/* eslint no-console: 0 */
import fs from 'fs'
import { filterPayload } from './serverLoggingFilters'

const pathToJsonLogfile = process.env.JSON_LOG_PATH || '/var/log/frontastic/json.log'
const spawnedByCli = process.env.SPAWNED_BY === 'FCLI'

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
            additionalData: args,
            '@timestamp': (new Date()).toISOString(),
        }
    }

    /**
    * If server:watch/start is called by the CLI it wipes this function to avoid the error of the 
    * file not existing on the user's PC, this can be revisited later if we want to log to a local 
    * file on the user's pc by instead passing the JSON_LOG_PATH env through the CLI with the absolute 
    * path to the user's repo (or wherever) and creating that file.
    */
    const writePayloadToFile = spawnedByCli ? payload => {} : payload => {
        fs.appendFile(pathToJsonLogfile, JSON.stringify(payload) + '\n', (err) => {
            if (err) {
                originalConsoleError(err)
            }
        })
    }

    console.log = (...args) => {
        if (args.length > 0) {
            const payload = getPayload(args, 'INFO')
            if (!filterPayload(payload)) {
                originalConsoleLog(payload)
                writePayloadToFile(payload)
            }
        }
    }
    console.info = (...args) => {
        if (args.length > 0) {
            const payload = getPayload(args, 'INFO')
            if (!filterPayload(payload)) {
                originalConsoleInfo(payload)
                writePayloadToFile(payload)
            }
        }
    }
    console.warn = (...args) => {
        if (args.length > 0) {
            const payload = getPayload(args, 'WARNING')
            if (!filterPayload(payload)) {
                originalConsoleWarn(payload)
                writePayloadToFile(payload)
            }
        }
    }
    console.error = console.exception = (...args) => {
        if (args.length > 0) {
            const payload = getPayload(args, 'ERROR')
            if (!filterPayload(payload)) {
                originalConsoleError(payload)
                writePayloadToFile(payload)
            }
        }
    }
    console.debug = (...args) => {
        if (args.length > 0) {
            const payload = getPayload(args, 'DEBUG')
            if (!filterPayload(payload)) {
                originalConsoleDebug(payload)
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
