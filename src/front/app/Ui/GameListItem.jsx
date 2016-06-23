'use strict'

var React = require('react');

var GameListItem = React.createClass({
    displayName: 'GameListItem',
    componentDidMount: function() {
    },
    render: function() {
        return  <li>
            {this.props.data}
        </li>;
    }
});

module.exports = GameListItem
