import { createBrowserHistory} from 'history'

export default (typeof window !== 'undefined') ?
    createBrowserHistory() :
    null
