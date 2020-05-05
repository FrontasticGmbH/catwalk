/**
 * Creates polyfills for window and document globals.
 * Should result in less problems with SSR.
 */
import { activateFileLogging } from './serverLoggingOverride'

const activateSsrPolyfills = () => {
    activateFileLogging('ssr')
    if (typeof window === 'undefined') {
        const domino = require('domino')
        const win = domino.createWindow()

        global['window'] = win
        global['document'] = win.document
        global['branch'] = null
        global['object'] = win.object
        global['HTMLElement'] = win.HTMLElement
        global['navigator'] = win.navigator
        global['localStorage'] = win.localStorage
        global['Element'] = domino.impl.Element
    }
}

export default activateSsrPolyfills
