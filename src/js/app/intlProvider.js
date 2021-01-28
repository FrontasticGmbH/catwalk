import { connect } from 'react-redux'
import { IntlProvider, addLocaleData } from 'react-intl'
import dot from 'dot-object'

import ComponentInjector from './injector'

import deMessages from './i18n/de.js'
import enMessages from './i18n/en.js'

export default ComponentInjector.return('App.IntlProvider', connect(
    (globalState) => {
        const { addLocaleData } = require('react-intl')
        const en = require('react-intl/locale-data/en')
        const de = require('react-intl/locale-data/de')

        addLocaleData([...en, ...de])

        const messages = {
            en: enMessages,
            de: deMessages,
        }

        let language = globalState.app.context.getLanguage()

        return {
            locale: language,
            messages: dot.dot(messages[language] || {}),
        }
    }
)(IntlProvider))
