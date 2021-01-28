if (typeof Promise === 'undefined') {
    // Rejection tracking prevents a common issue where React gets into an
    // inconsistent state due to an error, but it gets swallowed by a Promise,
    // and the user has no idea what causes React's erratic future behavior.
    require('promise/lib/rejection-tracking').enable()
    window.Promise = require('promise/lib/es6-extensions.js')
}

// fetch() polyfill for making API calls.
require('whatwg-fetch')

// Includes polyfills for ECMAScript 5, ECMAScript 6: promises, symbols, collections, iterators, typed arrays, ECMAScript 7+ proposals, setImmediate, etc
require('core-js/stable')

// async poylfill
require('regenerator-runtime/runtime')

// Object.assign() is commonly used with React.
// It will use the native implementation if it's present and isn't buggy.
Object.assign = require('object-assign')

// String.repeat Polyfill via MDN
// https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/String/repeat#Polyfill
if (!String.prototype.repeat) {
    String.prototype.repeat = function (count) {
        if (this == null) {
            throw new TypeError("can't convert " + this + ' to object')
        }

        var str = '' + this
        // To convert string to integer.
        count = +count
        // Check NaN
        if (count != count) {
            count = 0
        }

        if (count < 0) {
            throw new RangeError('repeat count must be non-negative')
        }

        if (count == Infinity) {
            throw new RangeError('repeat count must be less than infinity')
        }

        count = Math.floor(count)
        if (str.length == 0 || count == 0) {
            return ''
        }

        // Ensuring count is a 31-bit integer allows us to heavily optimize the
        // main part. But anyway, most current (August 2014) browsers can't handle
        // strings 1 << 28 chars or longer, so:
        if (str.length * count >= 1 << 28) {
            throw new RangeError('repeat count must not overflow maximum string size')
        }

        var maxCount = str.length * count
        count = Math.floor(Math.log(count) / Math.log(2))
        while (count) {
            str += str
            count--
        }
        str += str.substring(0, maxCount - str.length)
        return str
    }
}
