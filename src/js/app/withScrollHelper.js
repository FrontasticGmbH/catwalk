import { useContext } from 'react'
import PropTypes from 'prop-types'
import { ScrollContext } from './scrollContext'

/**
 * Component with a render prop that helps you with scrolling-related stuff
 *
 * @example
 * <WithScrollHelper
 *   render={({forceScrollToTop}) => {
 *     return <button onClick={forceScrollToTop}>Scroll to top!</button>
 *   }}
 * />
 */
const WithScrollHelper = ({ render }) => {
    const scrollContext = useContext(ScrollContext)

    return render({
        forceScrollToTop: scrollContext.forceScrollToTop,
    })
}
WithScrollHelper.propTypes = {
    render: PropTypes.func.isRequired,
}

export default WithScrollHelper
