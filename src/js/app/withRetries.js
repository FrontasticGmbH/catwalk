/**
 *
 * @param {function} callback to execute, receives retry() function to schedule a retry
 * @param {int} tryCount how often a retry should be tried
 * @param {function} callback to be executed if trials ran out without success
 */
const withRetries = (callback, tryCount, giveUpCallback = null) => {
    if (tryCount === 0) {
        if (giveUpCallback !== null) {
            giveUpCallback()
        }
        return
    }

    const retry = () => {
        withRetries(callback, tryCount - 1, giveUpCallback)
    }

    callback(retry, tryCount)
}

export default withRetries
