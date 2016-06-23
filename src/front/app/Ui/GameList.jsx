'use strict'

var React = require('react');
var GameListItem = require('./GameListItem');

var GameList = React.createClass({
    displayName: 'GameList',
    getInitialState: function() {
        return {
            list: [1, 2, 3]
        };
    },
    componentDidMount: function() {
    },
    render: function() {
        return  <ul>
            {this.state.list.map(function(listValue){
                return <GameListItem data={listValue} />;
            })}
        </ul>;
    }
});

module.exports = GameList;
window.GameList = GameList;
