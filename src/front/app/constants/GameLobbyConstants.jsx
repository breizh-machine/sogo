'use strict'

var keyMirror = require('keymirror');

module.exports = keyMirror({
    //Actions
    JOIN_GAME_ACTION: null,
    CREATE_GAME_ACTION: null,
    SWITCH_VIEW_MODE_ACTION: null,

    //View modes
    VIEW_MODE_LIST: null,
    VIEW_MODE_GAME_CREATION: null
});