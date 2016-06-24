'use strict'

var React = require('react');
var GameListItem = require('./GameListItem');

var GameList = React.createClass({
    displayName: 'GameList',
    render: function() {
        return  <ul>
            {this.props.gameItems.map(function(gameItem, key) {
                return <GameListItem key={key} gameItem={gameItem} />;
            })}
        </ul>;
    }
});

module.exports = GameList;
window.GameList = GameList;


