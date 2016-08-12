import { combineReducers } from 'redux'
import { Vector3, Euler, Matrix4 } from 'three'
import {
    JOIN_GAME_ACTION, JOIN_GAME_SUCCESS, JOIN_GAME_ERROR
} from './GamesPageAction'
import {
    MOVE_CURSOR
} from '../PlayPage/GameScene/GameControls/GameControlsActions'

import { getCursorNextPosition } from '../../../core/utils/threeTools'

function gamesPage(state = {
    joinGameLoading: false,
    joinGameError: {message: '', code: 0},
    joinGameSucceeded: false,
    displayJoinGameError: false,
    cursorPosition: new Vector3(0, 0, 0)
}, action) {
    switch (action) {
        case JOIN_GAME_ACTION:
            return Object.assign({}, state, {
                joinGameLoading: true,
                displayJoinGameError: false
            })
        case JOIN_GAME_SUCCESS:
            return Object.assign({}, state, {
                joinGameLoading: false,
                joinGameSucceeded: true
            })
        case JOIN_GAME_ERROR:
            return Object.assign({}, state, {
                joinGameLoading: false,
                joinGameError: action.error,
                displayJoinGameError: true
            })
        case MOVE_CURSOR:
            console.log('getting MOVE_CURSOR');
            const currentPosition = state.cursorPosition;
            const direction = action.direction;
            const angle = state.cameraRotationY;
            const turns = state.game.turns !== undefined ? state.game.turns : [];

            const nextPosition = getCursorNextPosition(currentPosition, direction, angle, turns);
            return Object.assign({}, state, {
                cursorPosition: nextPosition
            });
        default:
            return state
    }
}

export default gamesPage;