import logger from '../../../../src/js/app/htmlDiff/diffLogger'
import htmlFixture from './_fixture/ssr'
import diffFixture from './_fixture/diff'
import expectedLogFixture from './_fixture/expectedLog'

describe('diffLogger', () => {
    it('formats a diff to log', () => {
        const actualLog = logger(htmlFixture, diffFixture)
        // console.log(JSON.stringify(actualLog, null, 4))
        expect(actualLog).toEqual(expectedLogFixture)
    })
})
