import { connect } from 'react-redux'
import { IntlProvider } from 'react-intl'
import dot from 'dot-object'

import ComponentInjector from './injector'

import deMessages from './i18n/de.js'
import enMessages from './i18n/en.js'

export default ComponentInjector.return('App.IntlProvider', connect(
    (globalState) => {
        require('@formatjs/intl-pluralrules/polyfill')
        require('@formatjs/intl-pluralrules/locale-data/en')
        require('@formatjs/intl-pluralrules/locale-data/de')
        require('@formatjs/intl-relativetimeformat/polyfill')
        require('@formatjs/intl-relativetimeformat/locale-data/en')
        require('@formatjs/intl-relativetimeformat/locale-data/de')

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
