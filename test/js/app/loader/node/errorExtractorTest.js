
import errorExtractor from '../../../../../src/js/app/loader/node/errorExtractor'

import fixtureValid from './_fixture/errorExtractorValid'
import fixtureStreamError from './_fixture/errorExtractorStreamError'
import fixtureTasticError from './_fixture/errorExtractorTasticError'

describe('errorExtractor', () => {
    it('does not report on valid node data', () => {
        const actualResult = errorExtractor(fixtureValid.data)

        expect(actualResult.length).toBe(0)
    })

    it('reports invalid stream data', () => {
        const actualResult = errorExtractor(fixtureStreamError.data)

        expect(actualResult.length).toBe(1)
        expect(actualResult[0]).toMatchObject({
           stream: {
               "0141f924-9376-444a-b2ca-5c3fc810c801": {
                   ok: false,
                   message: "Notice: Undefined index: HTTP_REFERER"
               }
           }
        })
    })
})
