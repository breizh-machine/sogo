'use strict'

var AppDispatcher = require('../dispatcher/AppDispatcher');
var EventEmitter = require('events').EventEmitter;
var GameLobbyConstants = require('../constants/GameLobbyConstants');
var assign = require('object-assign');
var $ = require('jquery');

var CHANGE_EVENT = 'change';

var _gameItems = [];
var _viewMode = GameLobbyConstants.VIEW_MODE_LIST;

function _createGame(bet, local, localCubeId, guest) {
    //TODO
}

function _joinGame(gameId, visitorId, visitorCubeId) {
    //TODO
}

function _switchViewMode() {
    if (_viewMode === GameLobbyConstants.VIEW_MODE_LIST) {
        _viewMode = GameLobbyConstants.VIEW_MODE_GAME_CREATION;
    } else if (_viewMode === GameLobbyConstants.VIEW_MODE_GAME_CREATION) {
        _viewMode = GameLobbyConstants.VIEW_MODE_LIST;
    }
}

var GameLobbyStore = assign({}, EventEmitter.prototype, {

    getViewMode: function() {
        return _viewMode;
    },

    getAllGames: function() {
        return _gameItems;
    },

    emitChange: function() {
        this.emit(CHANGE_EVENT);
    },

    /**
     * @param {function} callback
     */
    addChangeListener: function(callback) {
        this.on(CHANGE_EVENT, callback);
    },

    /**
     * @param {function} callback
     */
    removeChangeListener: function(callback) {
        this.removeListener(CHANGE_EVENT, callback);
    }
});

// Register callback to handle all updates
AppDispatcher.register(function(action) {

    switch(action.actionType) {

        case GameLobbyConstants.CREATE_GAME_ACTION:
            //TODO
            GameLobbyStore.emitChange();
            break;

        case GameLobbyConstants.JOIN_GAME_ACTION:
            //TODO
            GameLobbyStore.emitChange();
            break;

        case GameLobbyConstants.SWITCH_VIEW_MODE_ACTION:
            _switchViewMode();
            GameLobbyStore.emitChange();
            break;

        default:
    }
});

module.exports = GameLobbyStore;





