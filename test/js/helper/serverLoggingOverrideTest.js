import { formatAdditionalData } from '../../../src/js/helper/serverLoggingOverride'

expect.extend({
    toBeAString: (received) => {
        if (typeof received === 'string' || received instanceof String) {
            return {
                message: () => { return `expected ${received} not to be a string` },
                pass: true,
            }
        } else {
            return {
                message: () => { return `expected ${received} to be a string` },
                pass: false,
            }
        }
    },
})

describe('serverLoggingOverride', () => {
    describe('formatAdditionalData', () => {
        it('should return an empty array if input is an array consisting of a single string', () => {
            // because we already have the first string in the "message" field
            expect(formatAdditionalData(['some message'])).toEqual([])
        })
        it('should return an array unchanged if the input is an array of strings', () => {
            let arrayOfStrings = ['foo', 'bar', 'baz']
            expect(formatAdditionalData(arrayOfStrings)).toEqual(arrayOfStrings)
        })
        it('should return an array with an error if given something other than an array', () => {
            expect(formatAdditionalData({})).toBeInstanceOf(Array)
            expect(formatAdditionalData({})).toContain('Error! Given data was not an array.')
        })
        it('should turn any value in the array to a string', () => {
            let result = formatAdditionalData(['message', 42, [3.2, NaN], { key: 'value' }])
            result.forEach((value) => {
                expect(value).toBeAString()
            })
        })
    })
})
