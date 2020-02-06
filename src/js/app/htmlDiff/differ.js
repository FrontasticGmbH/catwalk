import { DiffDOM, stringToObj } from 'diff-dom'
import formatDiffLog from './diffLogger'

function differ (beforeHtmlString, afterHtmlString) {
    try {
        const domDiffer = new DiffDOM()
        const before = stringToObj(beforeHtmlString)
        const after = stringToObj(afterHtmlString)

        const diff = domDiffer.diff(before, after)

        return formatDiffLog(before, diff)
    } catch (e) {
        return [{
            action: 'DIFFER-Error',
            path: '-',
            payload: e,
        }]
    }
}

export default differ
