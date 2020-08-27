import React, { useState } from 'react'
import PropTypes from 'prop-types'

import DefaultLayout from './layout'
import Page from 'frontastic-common/src/js/domain/page'

const PageView = ({ page, tastics, node, data, highlight }) => {
    const [pageState, setStatePage] = useState(
        new Page(page || {}, Object.keys(page.regions), tastics || [])
    )
    const [tasticsState, seTasticsState] = useState(tastics)

    if (
        pageState.pageId !== page.pageId ||
        tasticsState.length !== tastics.length
    ) {
        setStatePage(
            new Page(page || {}, Object.keys(page.regions), tastics || [])
        )
        seTasticsState(tastics)
    }

    const layouts = {
        // @TODO: Add more complex layouts here
    }

    const getLayout = () => {
        if (!pageState.layoutId) {
            return DefaultLayout
        }

        return layouts[pageState.layoutId] || DefaultLayout
    }

    let Layout = getLayout()
    return (
        <Layout
            node={node}
            page={pageState}
            data={!Array.isArray(data) ? data : {}}
            highlight={highlight}
        />
    )
}

PageView.propTypes = {
    node: PropTypes.object.isRequired,
    page: PropTypes.object.isRequired,
    data: PropTypes.object.isRequired,
    tastics: PropTypes.array.isRequired,
    highlight: PropTypes.any,
}

PageView.defaultProps = {
    highlight: null,
}

export default PageView
