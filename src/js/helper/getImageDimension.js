const getInputImageDimensions = (mediaApi, props, media = null) => {
    const { forceWidth, forceHeight, width, height } = props
    const cropRatio = media ? mediaApi.getFloatRatio(media, props.cropRatio) : null

    if (forceWidth || forceHeight) {
        return [
            forceWidth || (cropRatio ? forceHeight / cropRatio : null),
            forceHeight || (cropRatio ? forceWidth * cropRatio : null),
        ]
    }

    // On initial load it can happen, especially in production, that
    // props.width and height are NULL. This caused a bunch of issues with
    // images being only a few pixels tall. So we do a NULL check on that.
    // If that's the case we check if the media object is there and if we
    // can use the metadata in there. Lastly it falls back to a device
    // default.
    let inputHeight = height || null
    let inputWidth = width || null

    if (!inputWidth && inputHeight && cropRatio) {
        inputWidth = inputHeight / cropRatio
    } else if (!inputWidth && !inputHeight) {
        inputWidth = props.deviceType === 'mobile' ? 512 : 1024
    }

    return [inputWidth, inputHeight]
}

export default (mediaApi, props, state) => {
    const [inputWidth, inputHeight] = getInputImageDimensions(mediaApi, props, props.media || null)

    const [width, height] = mediaApi.getImageDimensions(
        props.media || null,
        inputWidth,
        inputHeight,
        props.cropRatio
    )

    // Obnly update actually rendered image width if the size of the image
    // differes in a relevant way (needs be larger, needs to be more then
    // three times smaller). Otherwise jsut keep the original image to not
    // load stuff again and again.
    if ((width > (state.width * 1.25)) ||
        (height > (state.height * 1.25)) ||
        (width < (state.width / 3)) ||
        (Math.abs(width / height - state.width / state.height) > 0.01)) {
        return {
            ...state,
            width,
            height,
        }
    } else {
        return state
    }
}
