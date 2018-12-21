import UrlHandler from '../urlHandler'

const connector = (globalState, props) => {
    return {
        urlHandler: props.node ?
            new UrlHandler(
                globalState.app.route,
                props.node.streams
            ) : null,
    }
}

export default connector
