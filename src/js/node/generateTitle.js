import getTranslation from '../getTranslation'

function generateTitle (node, context) {
    if (node.configuration.seoTitle) {
        return getTranslation(
            node.configuration.seoTitle,
            context.locale,
            context.project.defaultLanguage
        ).text
    }

    if (node.name) {
        return node.name
    }

    return node.configuration.path || ''
}

export default generateTitle
