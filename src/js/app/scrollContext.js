import React from 'react'
export const ScrollContext = React.createContext({
    forceScrollToTop: () => {
        // eslint-disable-next-line no-console
        console.warn('ScrollContext.scrollToTop was called, but was not found in Context')
    },
})
