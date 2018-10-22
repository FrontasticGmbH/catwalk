import { connect } from 'react-redux'
import { IntlProvider, addLocaleData } from 'react-intl'
import dot from 'dot-object'

import en from 'react-intl/locale-data/en'
import de from 'react-intl/locale-data/de'

import deMessages from './i18n/de.js'
import enMessages from './i18n/en.js'

addLocaleData([...en, ...de])

const messages = {
    en: enMessages,
    de: deMessages,
}

export default connect(
    (globalState) => {
        let language = globalState.app.context.getLanguage()

        return {
            locale: language,
            messages: dot.dot(messages[language] || {}),
        }
    }
)(IntlProvider)
