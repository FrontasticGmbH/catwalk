
import withRetries from '../../../src/js/app/withRetries'

describe('withRetries', () => {
    it('tries exactly trialCount times', () => {
        let executions = 0

        withRetries((retry) => {
            executions++
            retry()
        }, 3)

        expect(executions).toBe(3)
    })

    it('tries calls giveUpCallback when failing eventually', () => {
        let giveUpCalled = false

        withRetries(
            (retry) => {
                retry()
            },
            3,
            () => {
                console.log('Setting')
                giveUpCalled = true
            }
        )

        expect(giveUpCalled).toBe(true)
    })
})
