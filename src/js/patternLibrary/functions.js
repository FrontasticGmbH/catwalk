import _ from 'lodash'

function ucFirst (string) {
    return string.charAt(0).toUpperCase() + string.slice(1)
}

function displayName (string) {
    return _.map(string.split(/[_ -]/), ucFirst).join('')
}

function isReactComponent (component) {
    return !!(component.component && component.name && component.path)
}

export {
    ucFirst,
    displayName,
    isReactComponent,
}
