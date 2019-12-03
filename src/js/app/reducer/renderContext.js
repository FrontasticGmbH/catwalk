const initialState = {
    serverSideRendering: true,
    viewportDimension: undefined,
    userAgent: undefined,
    deviceType: 'mobile',
    breakpoints: undefined,
}

const detectingReducer = (state = initialState, action) => {
    switch (action.type) {
        case 'Frontastic.RenderContext.ClientSideDetected':
            return {
                ...state,
                serverSideRendering: false,
            }
        case 'Frontastic.RenderContext.UserAgentDetected':
            return renderContextWithDeviceType({
                ...state,
                userAgent: action.userAgent,
            })
        case 'Frontastic.RenderContext.ViewportDimensionChanged':
            return renderContextWithDeviceType({
                ...state,
                viewportDimension: action.viewportDimension,
            })
        case 'ApiBundle.Api.context.success':
            if (!action.data.project.data.layout) {
                // eslint-disable-next-line no-console
                console.warn('Frontastic needs the breakpoints configured in project.yml')
                return state
            }
            return {
                ...state,
                breakpoints: action.data.project.data.layout.breakpoints,
            }
        default:
            return state
    }
}

const detectDeviceTypeByRenderContext = (renderContext) => {
    if (renderContext.viewportDimension && renderContext.breakpoints) {
        const breakpointsSortedByMaxWidth = renderContext.breakpoints.map(breakpoint => {
            if(breakpoint.maxWidth) {
                return breakpoint
            }
            return {
                ...breakpoint,
                maxWidth: Infinity,
            }
        }).sort((a, b) => {
            return a.maxWidth - b.maxWidth
        })

        const possibleBreakpoints = breakpointsSortedByMaxWidth.filter(breakpoint => {
            return breakpoint.maxWidth > renderContext.viewportDimension.width
        })

        if (possibleBreakpoints.length > 0) {
            return possibleBreakpoints[0].identifier;
        }

        console.warn('No breakpoint matched. Did you forget setting a default breakpoint without max-width?')
    }

    if (renderContext.userAgent) {
        // @TODO we should probably replace this by a smart library
        return (
            renderContext.userAgent.match(/Android/i) ||
            renderContext.userAgent.match(/webOS/i) ||
            renderContext.userAgent.match(/iPhone/i) ||
            renderContext.userAgent.match(/iPad/i) || // TODO we should return tablet here
            renderContext.userAgent.match(/iPod/i) ||
            renderContext.userAgent.match(/BlackBerry/i) ||
            renderContext.userAgent.match(/Windows Phone/i)
        ) ? 'mobile' : 'desktop'
    }

    // Default to mobile. We do this for performance reasons.
    // We decided that, if we cannot be sure if the device, falling back to mobile is a good choice.
    // Why? Because mobiles usually have a slower CPU then desktops. 
    // That means, if we accidentally render the desktop page on the mobile and then switch to the mobile view,
    // this would be more "expensive" then accidentally rendering the mobile view on desktop.
    return 'mobile'
}

const renderContextWithDeviceType = (renderContext) => {
    return {
        ...renderContext,
        deviceType: detectDeviceTypeByRenderContext(renderContext)
    }
}

export default detectingReducer
