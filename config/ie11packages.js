/* This whole thing is neither well named nor optimal
way of describing it and is just here for convenience.
This should be refactored ASAP. *marcel
*/

const ie11regex = /node_modules\/(?!(frontastic-catwalk|frontastic-common|ansi-styles|chalk|diff-dom|react-intl|domino\/lib\/NodeList.es6.js|react-dev-utils)).*/

module.exports = ie11regex
