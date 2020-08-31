import { getVariantAttributes } from '../../../src/js/helper/variantHelper'

it('returns all attributes with at least 2 distinct values', () => {
    expect(getVariantAttributes([
        { attributes: {
            color: 'red',
            brand: 'Kore',
            badge: 'awesome',
            size: 'l',
        } },
        { attributes: {
            color: 'red',
            brand: 'Kore',
            size: 'xl',
        } },
        { attributes: {
            color: 'blue',
            brand: 'Kore',
            size: 's',
        } },
        { attributes: {
            color: 'blue',
            brand: 'Kore',
            size: 'xl',
        } },
    ])).toEqual(['color', 'size'])
})
