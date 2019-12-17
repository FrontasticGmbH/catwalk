import { isTranslatableString } from '../../../src/js/helper/translateTasticData'

describe('isTranslatableString', () => {
    it('returns true if "translatable" is set to true', () => {
        const fieldSchema = {
            type: 'string',
            translatable: true,
        }

        expect(isTranslatableString(fieldSchema)).toBe(true)
    })

    it('returns false if "translatable" is set to false', () => {
        const fieldSchema = {
            type: 'text',
            translatable: false,
        }

        expect(isTranslatableString(fieldSchema)).toBe(false)
    })

    it('returns true if the field is of type "string" and "translatable" is not defined (as it defaults to true)', () => {
        const fieldSchema = {
            type: 'string',
        }

        expect(isTranslatableString(fieldSchema)).toBe(true)
    })

    it('returns true if the field is of type "text" and "translatable" is not defined (as it defaults to true)', () => {
        const fieldSchema = {
            type: 'text',
        }

        expect(isTranslatableString(fieldSchema)).toBe(true)
    })

    it('returns false even if "translatable" is set to true, but we are not on a string field', () => {
        const fieldSchema = {
            type: 'number',
            translatable: true,
        }

        expect(isTranslatableString(fieldSchema)).toBe(false)
    })
})
