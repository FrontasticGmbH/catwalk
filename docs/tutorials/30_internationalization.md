# Internationalization in Tastics

Frontastic Catwalk inherently knows about internationalization. The locale of
the consumer browsing the page is available, the configuration of the project
(locales configured for the project and the default locale) as well as
translatable data.

## Making Tastic Data Translatable

If you want to mark a tastic field to be `translatable` you simply set the
corresponding flag to `true` in your Tastic schema specification. For example:

```js
{
    ...
    "schema": [
        {
            "name": "Stream Selection",
            "fields": [
                ...
                {
                    "label": "Show strike price",
                    "field": "showStrikePrice",
                    "type": "boolean",

                    "translatable": true
                }
            ]
        }
    ]
}

```

This will give the frontend manager the possibility to configure a setting
`showStrikePrice` for each of the locales available in the project.

**Note** that there are some field types `translatable` by default. If you
don't want fields of these types to be `translatable` you need to explicitely
switch the setting off. The affected types are:

- `string`
- `text`
- `markdown`

## Defining Translatable Data

Once you receive translatable data in your tastic you will notice that the data
format changes. Instead of retrieving a single instanc of the value type you
receive a hashmap that uses locales as index and has a value type assigned to
each of these keys. For example:

```js
{
    data: {
        // ...
        showStrikePrice: {
            'en_GB': true,
            'de_DE': false,
            'de_CH': true,
        }
    }
}
```

For values such as `boolean` and others you now need to take care of
determining the correct one for the current situation yourself (see later
section on advanced internationalization handling). But for textual values
Catwalk provides a shortcut.

## Translating Textual Data

Given you defined a text field that is `translatable` as follows:

```js
{
    ...
    "schema": [
        {
            "name": "Configuration",
            "fields": [
                ...
                {
                    "label": "Description",
                    "field": "description",
                    "type": "text"
                }
            ]
        }
    ]
}
```

You can simply render the `description` in the user's language using:

```

```js
import Translatable from 'frontastic-catwalk/src/js/component/translatable'

class TranslationExampleTastic extends Component {
    render () {
        return (<Translatable value={this.props.data.description} />
    }
}

```

The `Translatable` component will retrieve the users preferred locale and the
locales configured for the project. It will deduce the best possible match from
the given `value` and render it. In addition it will render a ´<span>` with the
CSS class `untranslated` around it, if no exact match could be found. Feel free
to style this in your development or staging environment so that you can easily
spot missing translations.

# Working Manually with Internationlized Data

If you need to work with more advanced data structures, numbers or you have
advanced requirements than our `Translatable` component provide you can handle
internationalization by yourself. To do this you need to retrieve the involved
locale from the redux store using a connector function:

```js
export default connect(
    (globalState, props) => {
        return {
            currentLocale: globalState.app.context.locale,
            defaultLocale: globalState.app.context.project.defaultLanguage
        }
    }
)(AdvancedTranslationExample)
```

The `currentLocale` prop will contain the locale preferred by the currently
watching user and `defaultLocale` can deal as a fallback for missing
translations.
