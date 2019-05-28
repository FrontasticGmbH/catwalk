import React, { Component, Fragment } from 'react'
import PropTypes from 'prop-types'

import Region from './region'

class Layout extends Component {
    render () {
        if (this.props.page.layoutId === 'kit') {
            return (
                <main className='c-page-body'>
                    <div className='c-page-wrapper o-wrapper'>
                        <Region
                            identifier='kit'
                            region={this.props.page.regions.kit}
                            highlight={this.props.highlight}
                            data={this.props.data}
                            node={this.props.node}
                            page={this.props.page}
                        />
                    </div>
                </main>
            )
        }

        return (
            <Fragment>
                <header className='c-page-head'>
                    {this.props.page.regions.head ? (
                        <Region
                            identifier='head'
                            region={this.props.page.regions.head}
                            highlight={this.props.highlight}
                            data={this.props.data}
                            node={this.props.node}
                            page={this.props.page}
                        />
                    ) : null}
                </header>
                <main className='c-page-body'>
                    <div className='c-page-wrapper o-wrapper'>
                        {this.props.page.regions.main ? (
                            <Region
                                identifier='main'
                                region={this.props.page.regions.main}
                                highlight={this.props.highlight}
                                data={this.props.data}
                                node={this.props.node}
                                page={this.props.page}
                            />
                        ) : null}
                    </div>
                </main>
                <footer className='c-page-foot'>
                    {this.props.page.regions.footer ? (
                        <Region
                            identifier='footer'
                            region={this.props.page.regions.footer}
                            highlight={this.props.highlight}
                            data={this.props.data}
                            node={this.props.node}
                            page={this.props.page}
                        />
                    ) : null}
                </footer>
            </Fragment>
        )

        /*
         * @TODO: This is the genric layout which we replaced by this hardcoded
         * template. Handle this somehow:
            {_.map(this.props.page.regions, (region, regionId) => {
                return (<div className='row' key={regionId}>
                    <div className='col-xs-12'>
                        <Region
                            identifier='regionId'
                            region={region}
                            highlight={this.props.highlight}
                            data={this.props.data}
                            page={this.props.page}
                        />
                    </div>
                </div>)
            })}
         */
    }
}

Layout.propTypes = {
    node: PropTypes.object.isRequired,
    page: PropTypes.object.isRequired,
    data: PropTypes.object.isRequired,
    highlight: PropTypes.any,
}

Layout.defaultProps = {
    highlight: null,
}

export default Layout
