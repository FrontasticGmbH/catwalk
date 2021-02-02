/* eslint no-console: 0 */
import fs from 'fs'
import { filterPayload } from './serverLoggingFilters'

const pathToJsonLogfile = process.env.JSON_LOG_PATH || '/var/log/frontastic/json.log'

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

    const writePayloadToFile = payload => {
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
