'use strict'

var React = require('react');

var GameListItem = React.createClass({
    displayName: 'GameListItem',
    render: function() {
        return  <li>
            {this.props.gameItem}
        </li>;
    }
});

module.exports = GameListItem
