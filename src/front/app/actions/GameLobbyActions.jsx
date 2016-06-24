'use strict'

var AppDispatcher = require('../dispatcher/AppDispatcher');
var GameLobbyConstants = require('../constants/GameLobbyConstants');

var GameLobbyActions = {

    /**
     * @param  {string} gameId
     */
    join: function(gameId) {
        AppDispatcher.dispatch({
            actionType: GameLobbyConstants.JOIN_GAME_ACTION,
            gameId: gameId
        });
    },

    switchViewMode: function() {
        AppDispatcher.dispatch({
            actionType: GameLobbyConstants.SWITCH_VIEW_MODE_ACTION
        });
    }
};

module.exports = GameLobbyActions;