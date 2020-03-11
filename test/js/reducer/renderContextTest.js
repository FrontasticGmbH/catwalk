import renderContextReducer from '../../../src/js/app/reducer/renderContext'
let breakpointsContextAction = {
    type: 'ApiBundle.Api.context.success',
    data: {
        project: {
            data: {
                layout: {
                    breakpoints: [
                        {
                            identifier: 'mobile',
                            name: 'Mobile',
                            userAgentRegexp:
                '(Android|webOS|iPhone|iPod|BlackBerry|Windows Phone)',
                            maxWidth: 480,
                        },
                        {
                            identifier: 'tablet',
                            name: 'Tablet',
                            userAgentRegexp: 'iPad',
                            userAgentRegexpModifiers: 'i',
                            maxWidth: 767,
                        },
                        {
                            identifier: 'desktop',
                            name: 'Desktop',
                        },
                    ],
                },
            },
        },
    },
}

describe('renderContextReducer', () => {
    it('should have a sensible initial state', () => {
        const initialState = renderContextReducer(undefined, {})
        expect(initialState.deviceType).toEqual(expect.any(String))
        expect(initialState.serverSideRendering).toEqual(expect.any(Boolean))
    })

    it('should fallback to mobile view', () => {
        const initialState = renderContextReducer(undefined, {})
        expect(initialState.deviceType).toEqual('mobile')
    })

    it('should extract breakpoints from context', () => {
        const state = renderContextReducer(undefined, breakpointsContextAction)
        expect(state.breakpoints).toMatchInlineSnapshot(
            false,
            `
      Array [
        Object {
          "identifier": "mobile",
          "maxWidth": 480,
          "name": "Mobile",
          "userAgentRegexp": "(Android|webOS|iPhone|iPod|BlackBerry|Windows Phone)",
        },
        Object {
          "identifier": "tablet",
          "maxWidth": 767,
          "name": "Tablet",
          "userAgentRegexp": "iPad",
          "userAgentRegexpModifiers": "i",
        },
        Object {
          "identifier": "desktop",
          "name": "Desktop",
        },
      ]
    `
        )
    })

    it('should switch to the fallback if no useragent matches', () => {
        let state = renderContextReducer(undefined, breakpointsContextAction)
        state = renderContextReducer(state, {
            type: 'Frontastic.RenderContext.UserAgentDetected',
            userAgent: 'Unknown Browser',
        })
        expect(state.deviceType).toEqual('desktop')
    })

    it('should switch the given breakpoint if useragent matches without modifier', () => {
        let state = renderContextReducer(undefined, breakpointsContextAction)
        state = renderContextReducer(state, {
            type: 'Frontastic.RenderContext.UserAgentDetected',
            userAgent: 'Safari on iPod thing',
        })
        expect(state.deviceType).toEqual('mobile')
    })
    it('should switch the given breakpoint if useragent matches with modifier', () => {
        let state = renderContextReducer(undefined, breakpointsContextAction)
        state = renderContextReducer(state, {
            type: 'Frontastic.RenderContext.UserAgentDetected',
            userAgent: 'Safari on ipad thing',
        })
        expect(state.deviceType).toEqual('tablet')
    })
})
