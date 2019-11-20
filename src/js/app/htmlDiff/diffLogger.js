/* eslint-disable no-console */
function logDiff (htmlStructure, diff) {
    return diff.map((change) => {
        if (change.action === 'addTextElement' && change.value.trim() === '') {
            return null
        }

        // Clone
        const route = change.route.slice()

        if (change.action === 'addElement') {
            // Route might point to a non existing element
            route.pop()
        }

        const path = htmlPath(htmlStructure, route)

        return formatChange(path, change)
    }).filter((change) => { return (change !== null) })
}

function formatChange (path, change) {
    const formattedChange = {
        path: formatPath(path),
        action: change.action,
        payload: 'unknown',
    }
    /* eslint-disable-next-line default-case */
    switch (change.action) {
    case 'addElement':
        formattedChange.payload = textDiff(formatElement(change.element), '+')
        break
    case 'removeElement':
        formattedChange.payload = textDiff(formatElement(change.element), '-')
        break
    case 'replaceElement':
        formattedChange.payload = textDiff(formatElement(change.oldValue), '-') + '\n' +
                textDiff(formatElement(change.newValue), '+ ')
        break
    case 'addTextElement':
        formattedChange.payload = textDiff(change.value, '+')
        break
    case 'removeTextElement':
        formattedChange.payload = textDiff(change.value, '-')
        break
    case 'modifyTextElement':
        formattedChange.payload = textDiff(change.oldValue, '-') + '\n' + textDiff(change.newValue, '+')
        break
    case 'addAttribute':
        formattedChange.payload = '+ ' + change.name + '="' + change.value + '"'
        break
    case 'modifyAttribute':
        formattedChange.payload = '- ' + change.name + '="' + change.oldValue + '"\n' +
                '+ ' + change.name + '="' + change.newValue + '"'
        break
    }

    return formattedChange
}

function textDiff (text, sign) {
    return text.replace(/^/gm, sign + ' ')
}

function formatElement (element, indent = 0) {
    if (isEmptyNode(element)) {
        return ''
    }

    const attributes = element.attributes || {}

    return ' '.repeat(indent) + '<' + element.nodeName.toLowerCase() +
            (attributes.class ? ' class="' + attributes.class + '"' : '') +
            (attributes.id ? 'id="' + attributes.id + '"' : '') +
        '>\n' +
        (element.childNodes || []).map((childElement) => {
            return formatElement(childElement, indent + 2)
        }).join('\n') +
        ' '.repeat(indent) + '</' + element.nodeName.toLowerCase() + '>\n'
}

/**
 * @param {object} htmlStructure
 * @param {Array} route
 */
function htmlPath (htmlStructure, route) {
    if (route.length === 0) {
        return htmlPathElements(htmlStructure)
    }

    // Clone
    let subRoute = route.slice()

    const nextRouteStep = subRoute.shift()

    if (!htmlStructure.childNodes || !htmlStructure.childNodes[nextRouteStep]) {
        console.error('Cannot find route step', nextRouteStep, 'from route', route, 'in', htmlStructure)
    }
    const nextLevelHtml = htmlStructure.childNodes[nextRouteStep]

    return htmlPathElements(htmlStructure).concat(htmlPath(nextLevelHtml, subRoute))
}

function htmlPathElements (htmlStructure) {
    if (isEmptyNode(htmlStructure)) {
        return []
    }

    return [{
        node: htmlStructure.nodeName.toLowerCase(),
        id: (htmlStructure.attributes.id || '').trim(),
        classes: (htmlStructure.attributes['class'] || '').trim().split(/ +/).filter((classElement) => {
            return classElement.trim() !== ''
        }),
    }]
}

function isEmptyNode (node) {
    return node.nodeName === '!--' || node.nodeName === '#text'
}

function formatPath (pathArray) {
    return pathArray.map((pathElement) => {
        return pathElement.node +
            (pathElement.id !== '' ? ('#' + pathElement.id) : '') +
            (pathElement.classes.length ? ('.' + pathElement.classes.join('.')) : '')
    }).join(' > ')
}

export default logDiff
