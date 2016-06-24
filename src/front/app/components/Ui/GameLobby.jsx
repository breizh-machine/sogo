'use strict'

var React = require('react');
var If = require('react-if').If;
var Then = If.Then;
var Else = If.Else;

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
            <div className="game-lobby-header">
                <button onClick={this._onSwitchViewModeClicked}>Switch view</button>
            </div>
            <div className="game-lobby-body">
                <If condition={this.state.viewMode == GameLobbyConstants.VIEW_MODE_LIST} >
                    <Then>
                        <GameList gameItems={this.state.allGames} />
                    </Then>
                    <Else>
                        <If condition={this.state.viewMode == GameLobbyConstants.VIEW_MODE_GAME_CREATION} >
                            <Then>
                                <GameCreation />
                            </Then>
                            <Else>Undefined view mode !</Else>
                        </If>
                    </Else>
                </If>
            </div>
        </div>;
    },

    _onSwitchViewModeClicked: function() {
        GameLobbyActions.switchViewMode();
    },

    _onChange: function() {
        this.setState(getGameLobbyState());
    }
});


module.exports = GameLobby;
window.GameLobby = GameLobby;