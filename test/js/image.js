import Image from '../../src/js/image'

const largeMedia = {
    width: 4800,
    height: 3600,
    name: 'Test',
    mediaId: 'id23',
}

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
    [{ width: null, height: null }, { width: 512, height: 384 }],
    [{ width: 200, height: null }, { width: 200, height: 150 }],
    [{ width: null, height: 200 }, { width: 267, height: 200 }],
    [{ width: null, height: null, cropRatio: '16:9' }, { width: 512, height: 288 }],
    [{ width: 200, height: null, cropRatio: '16:9' }, { width: 200, height: 113 }],
    [{ width: null, height: 200, cropRatio: '16:9' }, { width: 356, height: 201 }], // Rounding problem…
    [{ width: 200, height: 200, forceWidth: 320 }, { width: 320, height: 240 }],
    [{ width: 300, height: 200, forceWidth: 320 }, { width: 320, height: 240 }],
    [{ width: 200, height: 300, forceWidth: 320 }, { width: 320, height: 240 }],
    [{ width: 200, height: 200, forceWidth: 320, cropRatio: '16:9' }, { width: 320, height: 180 }],
    [{ width: 300, height: 200, forceWidth: 320, cropRatio: '16:9' }, { width: 320, height: 180 }],
    [{ width: 200, height: 300, forceWidth: 320, cropRatio: '16:9' }, { width: 320, height: 180 }],
    [{ width: 200, height: 200, forceHeight: 150 }, { width: 200, height: 150 }],
    [{ width: 300, height: 200, forceHeight: 150 }, { width: 200, height: 150 }],
    [{ width: 200, height: 300, forceHeight: 150 }, { width: 200, height: 150 }],
    [{ width: 200, height: 200, forceHeight: 150, cropRatio: '16:9' }, { width: 267, height: 151 }], // Rounding problem…
    [{ width: 300, height: 200, forceHeight: 150, cropRatio: '16:9' }, { width: 267, height: 151 }], // Rounding problem…
    [{ width: 200, height: 300, forceHeight: 150, cropRatio: '16:9' }, { width: 267, height: 151 }], // Rounding problem…
    [{ width: 200, height: 200, forceWidth: 150, forceHeight: 150 }, { width: 150, height: 150 }],
    [{ width: 300, height: 200, forceWidth: 150, forceHeight: 150 }, { width: 150, height: 150 }],
    [{ width: 200, height: 300, forceWidth: 150, forceHeight: 150 }, { width: 150, height: 150 }],
    [{ width: 200, height: 200, forceWidth: 150, forceHeight: 150, cropRatio: '16:9' }, { width: 150, height: 85 }],
    [{ width: 300, height: 200, forceWidth: 150, forceHeight: 150, cropRatio: '16:9' }, { width: 150, height: 85 }],
    [{ width: 200, height: 300, forceWidth: 150, forceHeight: 150, cropRatio: '16:9' }, { width: 150, height: 85 }],
])('image dimensions of large media are set properly on mobile', (properties, imageSize) => {
    const imageState = Image.WrappedComponent.getDerivedStateFromProps(
        {
            media: largeMedia,
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
])('image should be re-rendered because of relevant dimension change', (properties, imageSize) => {
    const imageState = Image.WrappedComponent.getDerivedStateFromProps(
        {
            media: largeMedia,
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
