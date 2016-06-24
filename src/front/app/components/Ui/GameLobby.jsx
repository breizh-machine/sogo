'use strict'

var React = require('react');

var GameLobbyActions = require('../../actions/GameLobbyActions');
var GameLobbyConstants = require('../../constants/GameLobbyConstants');
var GameLobbyStore = require('../../stores/GameLobbyStore');
var GameList = require('./GameList');
var GameCreation = require('./GameCreation');

/**
 * Retrieve state from Lobby store
 */
function getGameLobbyState() {
    return {
        allGames: GameLobbyStore.getAllGames(),
        viewMode: GameLobbyStore.getViewMode()
    };
}

var GameLobby = React.createClass({
    displayName: 'GameLobby',

    componentDidMount: function() {
        GameLobbyStore.addChangeListener(this._onChange);
    },

    componentWillUnmount: function() {
        GameLobbyStore.removeChangeListener(this._onChange);
    },

    getInitialState: function() {
        return getGameLobbyState();
    },

    render: function() {
        return <div className="game-lobby">
            <GameCreation />
            <GameList gameItems={this.state.allGames} />
        </div>;
    },

    _onChange: function() {
        this.setState(getGameLobbyState());
    }
});


module.exports = GameLobby;
window.GameLobby = GameLobby;