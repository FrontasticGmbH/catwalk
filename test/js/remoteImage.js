import RemoteImage from '../../src/js/remoteImage'

const initialState = {
    loading: true,
    error: false,
    width: null,
    height: null,
}

test.each([
    [{ width: 200, height: 200 }, { width: 200, height: 200 }],
    [{ width: 300, height: 200 }, { width: 300, height: 200 }],
    [{ width: 200, height: 300 }, { width: 200, height: 300 }],
    [{ width: 200, height: 200, cropRatio: '16:9' }, { width: 200, height: 113 }],
    [{ width: null, height: null }, { width: 512, height: 0 }],
    [{ width: 200, height: null }, { width: 200, height: 0 }],
    [{ width: null, height: 200 }, { width: 0, height: 200 }],
    [{ width: null, height: null, cropRatio: '16:9' }, { width: 512, height: 288 }],
    [{ width: 200, height: null, cropRatio: '16:9' }, { width: 200, height: 113 }],
    [{ width: null, height: 200, cropRatio: '16:9' }, { width: null, height: null }],
    [{ width: 200, height: 200, forceWidth: 320 }, { width: 320, height: 0 }],
    [{ width: 300, height: 200, forceWidth: 320 }, { width: 320, height: 0 }],
    [{ width: 200, height: 300, forceWidth: 320 }, { width: 320, height: 0 }],
    [{ width: 200, height: 200, forceWidth: 320, cropRatio: '16:9' }, { width: 320, height: 180 }],
    [{ width: 300, height: 200, forceWidth: 320, cropRatio: '16:9' }, { width: 320, height: 180 }],
    [{ width: 200, height: 300, forceWidth: 320, cropRatio: '16:9' }, { width: 320, height: 180 }],
    [{ width: 200, height: 200, forceHeight: 150 }, { width: 0, height: 150 }],
    [{ width: 300, height: 200, forceHeight: 150 }, { width: 0, height: 150 }],
    [{ width: 200, height: 300, forceHeight: 150 }, { width: 0, height: 150 }],
    [{ width: 200, height: 200, forceHeight: 150, cropRatio: '16:9' }, { width: null, height: null }],
    [{ width: 300, height: 200, forceHeight: 150, cropRatio: '16:9' }, { width: null, height: null }],
    [{ width: 200, height: 300, forceHeight: 150, cropRatio: '16:9' }, { width: null, height: null }],
    [{ width: null, height: null, forceHeight: 150 }, { width: 0, height: 150 }],
    [{ width: 200, height: null, forceHeight: 150 }, { width: 0, height: 150 }],
    [{ width: null, height: 200, forceHeight: 150 }, { width: 0, height: 150 }],
    [{ width: 200, height: 200, forceWidth: 150, forceHeight: 150 }, { width: 150, height: 150 }],
    [{ width: 300, height: 200, forceWidth: 150, forceHeight: 150 }, { width: 150, height: 150 }],
    [{ width: 200, height: 300, forceWidth: 150, forceHeight: 150 }, { width: 150, height: 150 }],
    [{ width: 200, height: 200, forceWidth: 150, forceHeight: 150, cropRatio: '16:9' }, { width: 150, height: 85 }],
    [{ width: 300, height: 200, forceWidth: 150, forceHeight: 150, cropRatio: '16:9' }, { width: 150, height: 85 }],
    [{ width: 200, height: 300, forceWidth: 150, forceHeight: 150, cropRatio: '16:9' }, { width: 150, height: 85 }],
    [{ width: null, height: null, forceWidth: 200 }, { width: 200, height: 0 }],
    [{ width: 200, height: null, forceWidth: 200 }, { width: 200, height: 0 }],
    [{ width: null, height: 200, forceWidth: 200 }, { width: 200, height: 0 }],
])('remote image dimensions of large media are set properly on mobile', (properties, imageSize) => {
    const imageState = RemoteImage.WrappedComponent.getDerivedStateFromProps(
        {
            url: 'http://exaple.com/image',
            deviceType: 'mobile',
            ...properties,
        },
        initialState
    )

    expect(imageState).toEqual({
        loading: true,
        error: false,
        ...imageSize,
    })
})

test.each([
    [{ width: 200, height: 200 }, { width: 250, height: 250 }],
    [{ width: 300, height: 300 }, { width: 250, height: 250 }],
    [{ width: 500, height: 500 }, { width: 500, height: 500 }],
    [{ width: 50, height: 50 }, { width: 50, height: 50 }],
    [{ width: 200, height: 300 }, { width: 200, height: 300 }],
    [{ width: 300, height: 200 }, { width: 300, height: 200 }],
    [{ width: 201, height: 200 }, { width: 250, height: 250 }],
])('remote image should be re-rendered because of relevant dimension change', (properties, imageSize) => {
    const imageState = RemoteImage.WrappedComponent.getDerivedStateFromProps(
        {
            url: 'http://exaple.com/image',
            deviceType: 'mobile',
            ...properties,
        },
        {
            ...initialState,
            width: 250,
            height: 250,
        }
    )

    expect(imageState).toEqual({
        loading: true,
        error: false,
        ...imageSize,
    })
})
