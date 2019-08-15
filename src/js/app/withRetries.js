/**
 *
 * @param {function} callback to execute, receives retry() function to schedule a retry
 * @param {int} trialCount how often a retry should be tried
 * @param {function} callback to be executed if trials ran out without success
 */
const withRetries = (callback, trialCount, giveUpCallback = null) => {
    if (trialCount === 0) {
        if (giveUpCallback !== null) {
            giveUpCallback()
        }
        return
    }

    const retry = () => {
        withRetries(callback, trialCount - 1, giveUpCallback)
    }

    callback(retry)
}

export default withRetries
