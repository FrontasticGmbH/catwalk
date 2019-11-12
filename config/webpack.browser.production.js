const PRODUCTION = true
const SERVER = false

let config = require('./webpack.js')(PRODUCTION, SERVER)
let oldConfig = require('./webpack.browser.production.old.js')

config = require('./webpack/namedModules.js')(config)
config = require('./webpack/buildStatistics.js')(config)
config = require('./webpack/manifest.js')(config)
config = require('./webpack/serviceWorker.js')(config)
config = require('./webpack/extractCss.js')(config)

config.optimization = {
    minimize: true,
    splitChunks: {
        chunks: 'all',
        minSize: 10 * 1024,
        maxSize: 0,
        minChunks: 1,
        automaticNameDelimiter: '~',
        name: true,
        cacheGroups: {
            icons: {
                minChunks: 1,
                test: /\/layout\/icons\//,
                priority: 10,
            },
            vendors: {
                test: /\/node_modules\//,
                priority: 5,
            },
            default: {
                minChunks: 2,
                priority: -20,
                reuseExistingChunk: true,
            },
        },
    },
}

var deepDiffMapper = function () {
  return {
    VALUE_CREATED: 'created',
    VALUE_UPDATED: 'updated',
    VALUE_DELETED: 'deleted',
    VALUE_UNCHANGED: 'unchanged',
    map: function(obj1, obj2) {
      if (this.isFunction(obj1) || this.isFunction(obj2)) {
        throw 'Invalid argument. Function given, object expected.';
      }
      if (this.isValue(obj1) || this.isValue(obj2)) {
        return {
          type: this.compareValues(obj1, obj2),
          data: obj1 === undefined ? obj2 : obj1
        };
      }

      var diff = {};
      for (var key in obj1) {
        if (this.isFunction(obj1[key])) {
          continue;
        }

        var value2 = undefined;
        if (obj2[key] !== undefined) {
          value2 = obj2[key];
        }

        diff[key] = this.map(obj1[key], value2);
      }
      for (var key in obj2) {
        if (this.isFunction(obj2[key]) || diff[key] !== undefined) {
          continue;
        }

        diff[key] = this.map(undefined, obj2[key]);
      }

      return diff;

    },
    compareValues: function (value1, value2) {
      if (value1 === value2) {
        return this.VALUE_UNCHANGED;
      }
      if (this.isDate(value1) && this.isDate(value2) && value1.getTime() === value2.getTime()) {
        return this.VALUE_UNCHANGED;
      }
      if (value1 === undefined) {
        return this.VALUE_CREATED;
      }
      if (value2 === undefined) {
        return this.VALUE_DELETED;
      }
      return this.VALUE_UPDATED;
    },
    isFunction: function (x) {
      return Object.prototype.toString.call(x) === '[object Function]';
    },
    isArray: function (x) {
      return Object.prototype.toString.call(x) === '[object Array]';
    },
    isDate: function (x) {
      return Object.prototype.toString.call(x) === '[object Date]';
    },
    isObject: function (x) {
      return Object.prototype.toString.call(x) === '[object Object]';
    },
    isValue: function (x) {
      return !this.isObject(x) && !this.isArray(x);
    }
  }
}();

console.log(deepDiffMapper.map(config, oldConfig))

module.exports = config
