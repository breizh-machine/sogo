import { combineReducers } from 'redux'
import { Vector3, Euler, Matrix4 } from 'three'
import {
    MOVE_CURSOR
} from '../actions/GameView/GameControlsActions'

import { getCursorNextPosition } from '../tools/threeTools'

function playPage(state = {
    cursorPosition: new Vector3(0, 0, 0)
}, action) {
    switch (action) {
        case MOVE_CURSOR:
            const currentPosition = state.cursorPosition;
            const direction = action.direction;
            const turns = action.turns;
            let position = new Vector3();
            let rotation = new Euler();
            let scale = new Vector3();
            state.cameraMatrix.decompose(position, rotation, scale);
            const angle = rotation.z;

            const nextPosition = getCursorNextPosition(currentPosition, direction, angle, turns);
            return Object.assign({}, state, {
                cursorPosition: nextPosition
            });

        default:
            return state
    }
}

export default playPage;