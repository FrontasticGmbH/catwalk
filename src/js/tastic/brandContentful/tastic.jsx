import React, { Component } from 'react'
import PropTypes from 'prop-types'
import Translatable from '../../component/translatable'
import _ from 'lodash'

class BrandContentfulTastic extends Component {
    haveAllTheAttributes = (attributes) => {
        const { companyName, logo, companyDescription, website } = attributes

        const hasCompanyName = !_.isEmpty(companyName) && !_.isEmpty(companyName.content)
        const hasLogo = !_.isEmpty(logo) && !_.isEmpty(logo.content) && !_.isEmpty(logo.content.url)
        const hasCompanyDescription = !_.isEmpty(companyDescription) && !_.isEmpty(companyDescription.content)
        const hasWebsite = !_.isEmpty(website) && !_.isEmpty(website.content)

        return hasCompanyName && hasLogo && hasCompanyDescription && hasWebsite
    }

    render () {
        let content = this.props.rawData.stream[this.props.tastic.configuration.stream]

        if (!content || !content.attributes || !this.haveAllTheAttributes(content.attributes)) {
            return null
        }

        const showLogo = this.props.tastic.schema.get('showLogo') || false
        const { companyName, logo, companyDescription, website } = content.attributes
        const descriptionTeaser = companyDescription.content.length > 350
            ? `${companyDescription.content.substr(0, 350)}...`
            : companyDescription.content

        return (
            <div className='contentful-brand-company-preview'>

                <div className='fist-row'>
                    {showLogo && <div
                        className='bc-logo'
                        style={{ backgroundImage: `url(${logo.content.url})` }}
                    />}

                    <div className='bc-contact'>
                        <div className='bc-name'>{companyName.content}</div>
                        <div className='bc-website'>
                            <a
                                href={website.content}
                                rel='noopener noreferrer'
                                target='_blank'
                            >
                                {website.content}
                            </a>
                        </div>
                    </div>

                </div>

                <p className=' second-row bc-description'>
                    <Translatable value={descriptionTeaser} />
                </p>

            </div>

        )
    }
}

BrandContentfulTastic.propTypes = {
    rawData: PropTypes.object.isRequired,
    tastic: PropTypes.object.isRequired,
}

BrandContentfulTastic.defaultProps = {
}

export default BrandContentfulTastic