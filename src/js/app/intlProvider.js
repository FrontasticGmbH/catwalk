import { connect } from 'react-redux'
import { IntlProvider } from 'react-intl'
import dot from 'dot-object'

import ComponentInjector from './injector'

import '@formatjs/intl-pluralrules/polyfill'
import '@formatjs/intl-pluralrules/locale-data/en'
import '@formatjs/intl-pluralrules/locale-data/de'
import '@formatjs/intl-relativetimeformat/polyfill'
import '@formatjs/intl-relativetimeformat/locale-data/en'
import '@formatjs/intl-relativetimeformat/locale-data/de'

import deMessages from './i18n/de.js'
import enMessages from './i18n/en.js'

export default ComponentInjector.return('App.IntlProvider', connect(
    (globalState) => {
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
