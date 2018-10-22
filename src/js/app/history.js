import createHistory from 'history/createBrowserHistory'

export default (typeof window !== 'undefined') ?
    createHistory() :
    null
