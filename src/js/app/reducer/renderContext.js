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
        if (!action.data || !action.data.project || !action.data.project.data || !action.data.project.data.layout) {
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
        const breakpointsSortedByMinWidth = renderContext.breakpoints
            .map((breakpoint) => {
                if (breakpoint.minWidth) {
                    return breakpoint
                }
                return {
                    ...breakpoint,
                    minWidth: -Infinity,
                }
            })
            .sort((a, b) => {
                return b.minWidth - a.minWidth
            })

        const possibleBreakpoints = breakpointsSortedByMinWidth.filter((breakpoint) => {
            return breakpoint.minWidth <= renderContext.viewportDimension.width
        })
        if (possibleBreakpoints.length > 0) {
            return possibleBreakpoints[0].identifier
        }

        // eslint-disable-next-line no-console
        console.warn('No breakpoint matched. Did you forget setting a default breakpoint without min-width?')
    }

    if (renderContext.userAgent && renderContext.breakpoints) {
        const matchedBreakpoint = renderContext.breakpoints.reduce((matchedBreakpoint, breakpoint) => {
            if (matchedBreakpoint && matchedBreakpoint.userAgentRegexp) {
                return matchedBreakpoint
            }

            if (breakpoint.userAgentRegexp) {
                // regex string in form of /(Android|webOS|iPhone|...)/i must be passed to RegExp constructor without slashes (/)
                let regexpParts = breakpoint.userAgentRegexp.split("/")
                const regexp = new RegExp(regexpParts[1], regexpParts[2] || breakpoint.userAgentRegexpModifiers)
                return renderContext.userAgent.match(regexp) ? breakpoint : matchedBreakpoint
            }

            // The current breakpoint and the matchedBreakpoint does not have a userAgentRegexp, therefore there
            // must be two default breakpoints so let's raise a warning here
            if (matchedBreakpoint) {
                // eslint-disable-next-line no-console
                console.warn(
                    'More than one breakpoint is missing a userAgentRegexp, using the last one in array. Did you' +
                    ' forget to add them for the other entries?'
                )
            }

            // No userAgentRegexp has been set, therefore this must be the default
            return breakpoint
        }, null)

        return matchedBreakpoint.identifier
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
        deviceType: detectDeviceTypeByRenderContext(renderContext),
    }
}

export default detectingReducer
