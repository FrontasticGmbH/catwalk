import React from 'react'
export const ScrollContext = React.createContext({
    forceScrollToTop: () => {
        console.warn('ScrollContext.scrollToTop was called, but was not found in Context')
    },
})
