import _ from 'lodash'

import { ucFirst } from '../patternLibrary/functions'
import icon from '../../layout/heart.jsx'

class LoremIpsum {
    static words = [
        'a',
        'ac',
        'accumsan',
        'ad',
        'adipiscing',
        'aenean',
        'aenean',
        'aliquam',
        'aliquam',
        'aliquet',
        'amet',
        'ante',
        'aptent',
        'arcu',
        'at',
        'auctor',
        'augue',
        'bibendum',
        'blandit',
        'class',
        'commodo',
        'condimentum',
        'congue',
        'consectetur',
        'consequat',
        'conubia',
        'convallis',
        'cras',
        'cubilia',
        'curabitur',
        'curabitur',
        'curae',
        'cursus',
        'dapibus',
        'diam',
        'dictum',
        'dictumst',
        'dolor',
        'donec',
        'donec',
        'dui',
        'duis',
        'egestas',
        'eget',
        'eleifend',
        'elementum',
        'elit',
        'enim',
        'erat',
        'eros',
        'est',
        'et',
        'etiam',
        'etiam',
        'eu',
        'euismod',
        'facilisis',
        'fames',
        'faucibus',
        'felis',
        'fermentum',
        'feugiat',
        'fringilla',
        'fusce',
        'gravida',
        'habitant',
        'habitasse',
        'hac',
        'hendrerit',
        'himenaeos',
        'iaculis',
        'id',
        'imperdiet',
        'in',
        'inceptos',
        'integer',
        'interdum',
        'ipsum',
        'justo',
        'lacinia',
        'lacus',
        'laoreet',
        'lectus',
        'leo',
        'libero',
        'ligula',
        'litora',
        'lobortis',
        'lorem',
        'luctus',
        'maecenas',
        'magna',
        'malesuada',
        'massa',
        'mattis',
        'mauris',
        'metus',
        'mi',
        'molestie',
        'mollis',
        'morbi',
        'nam',
        'nec',
        'neque',
        'netus',
        'nibh',
        'nisi',
        'nisl',
        'non',
        'nostra',
        'nulla',
        'nullam',
        'nunc',
        'odio',
        'orci',
        'ornare',
        'pellentesque',
        'per',
        'pharetra',
        'phasellus',
        'placerat',
        'platea',
        'porta',
        'porttitor',
        'posuere',
        'potenti',
        'praesent',
        'pretium',
        'primis',
        'proin',
        'pulvinar',
        'purus',
        'quam',
        'quis',
        'quisque',
        'quisque',
        'rhoncus',
        'risus',
        'rutrum',
        'sagittis',
        'sapien',
        'scelerisque',
        'sed',
        'sem',
        'semper',
        'senectus',
        'sit',
        'sociosqu',
        'sodales',
        'sollicitudin',
        'suscipit',
        'suspendisse',
        'taciti',
        'tellus',
        'tempor',
        'tempus',
        'tincidunt',
        'torquent',
        'tortor',
        'tristique',
        'turpis',
        'ullamcorper',
        'ultrices',
        'ultricies',
        'urna',
        'ut',
        'ut',
        'varius',
        'vehicula',
        'vel',
        'velit',
        'venenatis',
        'vestibulum',
        'vitae',
        'vivamus',
        'viverra',
        'volutpat',
        'vulputate',
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
        beach: {
            mediaId: 'mezexly30gy5hvs81ky3',
            name: '11',
            width: 4032,
            height: 3024,
        },
        flower: {
            mediaId: 'ycizl3j7ddh4nrvj8bfn',
            name: 'DSC 3516',
            width: 4928,
            height: 3264,
        },
        logo: {
            mediaId: 'Ray Ban',
            name: 'Syntastic',
            width: 640,
            height: 390,
        },
        brille: {
            mediaId: 'vetarlqiuvf13jtpbwzc',
            name: 'Brille',
            width: 365,
            height: 121,
        },
        category: {
            mediaId: 'gfk4gqujg2gsrf8nupvi',
            name: 'Category',
            width: 382,
            height: 382,
        },
        glasses: {
            name: 'Glasses-big',
            mediaId: 'fjk2rq11kjivixlacgvg',
            width: 1171,
            height: 638,
        },
        paypal: {
            name: 'PayPal',
            mediaId: 'wckwbnhu9uo9xh0mbut8',
            width: 87,
            height: 23,
        },
        visa: {
            name: 'Visa',
            mediaId: 'ymkhgpa77hejydj9qvek',
            width: 70,
            height: 23,
        },
        klarna: {
            name: 'Klarna',
            mediaId: 'hbqylhpn4usjtguaryfx',
            width: 112,
            height: 29,
        },
        mastercard: {
            name: 'MasterCard',
            mediaId: 'adkmc1bgb0t35yzjjeje',
            width: 49,
            height: 38,
        },
    },
    product: {
        name: LoremIpsum.heading(1, 3),
        productId: 'test-product',
        slug: 'test-product',
        _url: '/',
        variants: [
            {
                sku: '6921103619958',
                attributes: {
                    designer: LoremIpsum.heading(1, 1),
                    // Any attributes you want
                },
                images: ['https://s3-eu-west-1.amazonaws.com/commercetools-maximilian/products/071983_1_large.jpg'],
                discountedPrice: 15000, // in cents
                price: 5000, // in cents
                priceDescription: 'Preis inkl. Basisgläser',
            },
            {
                attributes: {
                    designer: LoremIpsum.heading(1, 1),
                    promo_sticker: 'top_seller',
                },
                images: ['https://s3-eu-west-1.amazonaws.com/commercetools-maximilian/products/071983_1_large.jpg'],
                discountedPrice: 10000, // in cents
                price: 9900, // in cents
            },
        ],
    },
    products: _.range(1, 5).map(
        (number) => {
            return {
                name: LoremIpsum.heading(1, 3) + ' ' + number,
                productId: 'test-product-' + number,
                slug: 'test-product-' + number,
                _url: '/',
                variants: [
                    {
                        sku: 'test-product-' + number + '-1',
                        attributes: {
                            designer: LoremIpsum.heading(1, 1),
                            // Any attributes you want
                        },
                        images: ['https://s3-eu-west-1.amazonaws.com/commercetools-maximilian/products/071983_1_large.jpg'],
                        discountedPrice: 15000, // in cents
                        price: 5000, // in cents
                        priceDescription: 'Preis inkl. Basisgläser',
                    },
                    {
                        sku: 'test-product-' + number + '-2',
                        attributes: {
                            designer: LoremIpsum.heading(1, 1),
                            promo_sticker: 'top_seller',
                        },
                        images: ['https://s3-eu-west-1.amazonaws.com/commercetools-maximilian/products/071983_1_large.jpg'],
                        discountedPrice: 10000, // in cents
                        price: 9900, // in cents
                    },
                ],
            }
        }
    ),
    icon: icon,
    cart: {
        cartId: '560e2e2f-f465-4292-968e-dc31ca2d0330',
        cartVersion: 36,
        lineItems: [
            {
                variant: {
                    id: 1,
                    sku: '10269365',
                    groupId: null,
                    price: -1,
                    discountedPrice: null,
                    currency: 'EUR',
                    attributes: {
                        // Product attributes
                        brand_description: 'D_brand_description',
                    },
                    images: [
                        'https://154dedb718850721d17e-0c40ab1383a8876c3617f61378dcc10c.ssl.cf3.rackcdn.com/4723353900638_iWearf-Rq4cC4OQ.png',
                        'https://154dedb718850721d17e-0c40ab1383a8876c3617f61378dcc10c.ssl.cf3.rackcdn.com/4723353900638_iwear_-D847aGXN.png',
                        'https://154dedb718850721d17e-0c40ab1383a8876c3617f61378dcc10c.ssl.cf3.rackcdn.com/4723353900638_iWearf-ioT8B36S.png',
                        'https://154dedb718850721d17e-0c40ab1383a8876c3617f61378dcc10c.ssl.cf3.rackcdn.com/4723353900638_iWearf-MruGoK52.png',
                        'https://154dedb718850721d17e-0c40ab1383a8876c3617f61378dcc10c.ssl.cf3.rackcdn.com/4723353900638_iWearf-_NLyLn9B.png',
                    ],
                    isOnStock: false,
                },
                type: 'variant',
                lineItemId: '768d2f36-b2c5-449a-836e-fe76be19b340',
                name: 'iWear fit astigmatism',
                custom: {
                    // Custom line item attributes
                },
                count: 2,
                price: 3000, // in cents
                discountedPrice: 4500, // in cents
                discountTexts: [{ de: 'Test-Discount' }],
                totalPrice: 4500, // in cents
                isGift: false,
            },
            {
                variant: {
                    id: 1,
                    sku: '10269365',
                    groupId: null,
                    price: -1,
                    discountedPrice: null,
                    currency: 'EUR',
                    attributes: {
                        // Product attributes
                        brand_description: 'D_brand_description',
                    },
                    images: [
                        'https://154dedb718850721d17e-0c40ab1383a8876c3617f61378dcc10c.ssl.cf3.rackcdn.com/4723353900638_iWearf-Rq4cC4OQ.png',
                        'https://154dedb718850721d17e-0c40ab1383a8876c3617f61378dcc10c.ssl.cf3.rackcdn.com/4723353900638_iwear_-D847aGXN.png',
                        'https://154dedb718850721d17e-0c40ab1383a8876c3617f61378dcc10c.ssl.cf3.rackcdn.com/4723353900638_iWearf-ioT8B36S.png',
                        'https://154dedb718850721d17e-0c40ab1383a8876c3617f61378dcc10c.ssl.cf3.rackcdn.com/4723353900638_iWearf-MruGoK52.png',
                        'https://154dedb718850721d17e-0c40ab1383a8876c3617f61378dcc10c.ssl.cf3.rackcdn.com/4723353900638_iWearf-_NLyLn9B.png',
                    ],
                    isOnStock: false,
                },
                type: 'variant',
                lineItemId: '1b91f4e9-eb3c-48cb-b76b-a22850c470dd',
                name: 'iWear fit astigmatism',
                custom: {
                    // Custom line item attributes
                },
                count: 1,
                price: 3000, // in cents
                discountedPrice: 2250, // in cents
                discountTexts: [{ de: 'Test-Discount' }],
                totalPrice: 2250, // in cents
                isGift: false,
            },
        ],
        sum: 37000, // in cents
        email: null,
        birthday: null,
        shippingMethod: null,
        shippingAddress: null,
        billingAddress: null,
        payments: [],
    },
    user: {
        salutation: 'Herr',
        firstName: 'Max',
        lastName: 'Mustermann',
        email: 'm.mustermann@gmail.com',
        addresses: [
            {
                isDefaultShippingAddress: true,
                isDefaultBillingAddress: false,
                salutation: 'Herr',
                firstName: 'Max',
                lastName: 'Mustermann',
                streetName: 'Musterstraße',
                streetNumber: '4',
                additionalStreetInfo: null,
                postalCode: '28476',
                city: 'Musterstadt',
                country: 'Deutschland',
            },
            {
                isDefaultShippingAddress: false,
                isDefaultBillingAddress: true,
                salutation: 'Frau',
                firstName: 'Maxine',
                lastName: 'Musterfrau',
                streetName: 'Musterstr',
                streetNumber: '367',
                additionalStreetInfo: 'Innenhof links',
                postalCode: '238364',
                city: 'Musterstadt',
                country: 'Deutschland',
            },
            {
                isDefaultShippingAddress: false,
                isDefaultBillingAddress: false,
                salutation: 'Frau',
                firstName: 'Lisa',
                lastName: 'Lachmuskel',
                streetName: 'Leverkusenerstr',
                streetNumber: '56',
                additionalStreetInfo: false,
                postalCode: '238364',
                city: 'Musterstadt',
                country: 'Deutschland',
            },
            {
                isDefaultShippingAddress: false,
                isDefaultBillingAddress: false,
                salutation: 'Filiale',
                firstName: 'Essen',
                lastName: null,
                streetName: 'Marktstr.35',
                streetNumber: null,
                additionalStreetInfo: 12345,
                postalCode: '45355',
                city: 'Essen',
                country: 'Deutschland',
            },
        ],
    },
}
