import _ from 'lodash'

import { ucFirst } from './../patternLibrary/functions'

class LoremIpsum {
    static words = [
        'a', 'ac', 'accumsan', 'ad', 'adipiscing', 'aenean', 'aenean',
        'aliquam', 'aliquam', 'aliquet', 'amet', 'ante', 'aptent', 'arcu',
        'at', 'auctor', 'augue', 'bibendum', 'blandit', 'class', 'commodo',
        'condimentum', 'congue', 'consectetur', 'consequat', 'conubia',
        'convallis', 'cras', 'cubilia', 'curabitur', 'curabitur', 'curae',
        'cursus', 'dapibus', 'diam', 'dictum', 'dictumst', 'dolor', 'donec',
        'donec', 'dui', 'duis', 'egestas', 'eget', 'eleifend', 'elementum',
        'elit', 'enim', 'erat', 'eros', 'est', 'et', 'etiam', 'etiam', 'eu',
        'euismod', 'facilisis', 'fames', 'faucibus', 'felis', 'fermentum',
        'feugiat', 'fringilla', 'fusce', 'gravida', 'habitant', 'habitasse',
        'hac', 'hendrerit', 'himenaeos', 'iaculis', 'id', 'imperdiet', 'in',
        'inceptos', 'integer', 'interdum', 'ipsum', 'justo', 'lacinia',
        'lacus', 'laoreet', 'lectus', 'leo', 'libero', 'ligula', 'litora',
        'lobortis', 'lorem', 'luctus', 'maecenas', 'magna', 'malesuada',
        'massa', 'mattis', 'mauris', 'metus', 'mi', 'molestie', 'mollis',
        'morbi', 'nam', 'nec', 'neque', 'netus', 'nibh', 'nisi', 'nisl', 'non',
        'nostra', 'nulla', 'nullam', 'nunc', 'odio', 'orci', 'ornare',
        'pellentesque', 'per', 'pharetra', 'phasellus', 'placerat', 'platea',
        'porta', 'porttitor', 'posuere', 'potenti', 'praesent', 'pretium',
        'primis', 'proin', 'pulvinar', 'purus', 'quam', 'quis', 'quisque',
        'quisque', 'rhoncus', 'risus', 'rutrum', 'sagittis', 'sapien',
        'scelerisque', 'sed', 'sem', 'semper', 'senectus', 'sit', 'sociosqu',
        'sodales', 'sollicitudin', 'suscipit', 'suspendisse', 'taciti',
        'tellus', 'tempor', 'tempus', 'tincidunt', 'torquent', 'tortor',
        'tristique', 'turpis', 'ullamcorper', 'ultrices', 'ultricies', 'urna',
        'ut', 'ut', 'varius', 'vehicula', 'vel', 'velit', 'venenatis',
        'vestibulum', 'vitae', 'vivamus', 'viverra', 'volutpat', 'vulputate',
    ]

    static word = (min, max) => {
        var result = []
        var count = _.random(min, max, false)

        while (result.length < count) {
            result.push(_.nth(LoremIpsum.words, _.random(0, LoremIpsum.words.length, false)))
        }

        return result.join(' ')
    }

    static heading = (min, max) => {
        return _.map(LoremIpsum.word(min, max).split(' '), ucFirst).join(' ')
    }

    static sentence = (min, max) => {
        var result = []
        var count = _.random(min, max, false)

        while (result.length < count) {
            result.push(ucFirst(LoremIpsum.word(3, 10) + '. '))
        }

        return _.trim(result.join(' '))
    }
}

export default {
    headline: {
        xs: LoremIpsum.heading(2, 2),
        short: LoremIpsum.heading(2, 4),
    },
    excerpt: {
        short: LoremIpsum.sentence(2, 6),
        medium: LoremIpsum.sentence(5, 10),
    },
    image: {
        person: {
            mediaId: 'Bank-entspannung-frau-616384',
            name: 'Woman posing in park',
            width: 5237,
            height: 3572,
        },
    },
    product: {
        name: LoremIpsum.heading(1, 3),
        productId: 'test-product',
        slug: 'test-product',
        _url: '/',
        variants: [
            {
                attributes: {
                    designer: LoremIpsum.heading(1, 2),
                },
                images: [
                    'https://s3-eu-west-1.amazonaws.com/commercetools-maximilian/products/071983_1_large.jpg',
                ],
                discountedPrice: 11999,
                price: 14999,
            },
        ],
    },
}
