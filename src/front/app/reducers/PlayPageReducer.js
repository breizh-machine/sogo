import { combineReducers } from 'redux'
import { Vector3, Euler, Matrix4 } from 'three'
import {
    MOVE_CURSOR
} from '../actions/GameView/GameControlsActions'

import { getCursorNextPosition } from '../tools/threeTools'

function gamesPage(state = {
    cursorPosition: new Vector3(0, 0, 0)
}, action) {
    switch (action) {
        default:
            return state
    }
}

export default gamesPage;