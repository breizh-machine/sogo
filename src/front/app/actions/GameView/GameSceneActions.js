import { CALL_API } from '../../middleware/api'

export const RESIZE_SCENE = 'RESIZE_SCENE'
export function resizeScene(width, height) {
    return {
        type: RESIZE_SCENE,
        width: width,
        height: height
    }
}

export const TRANSLATE_CAMERA_ON_Z = 'TRANSLATE_CAMERA_ON_Z'
export function translateCameraOnZ(distance) {
    return {
        type: TRANSLATE_CAMERA_ON_Z,
        distance: distance
    }
}

export const ROTATE_CAMERA_ON_AXIS = 'ROTATE_CAMERA_ON_AXIS'
export function rotateCameraOnAxis(axis, angleInDegrees) {
    return {
        type: ROTATE_CAMERA_ON_AXIS,
        axis: axis,
        angleInDegrees: angleInDegrees
    }
}

export const ROTATE_AROUND_GAMEBOARD = 'ROTATE_AROUND_GAMEBOARD'
export function rotateAroundGameBoard() {
    return {
        type: ROTATE_AROUND_GAMEBOARD
    }
}

export const UPDATE_ROTATION_GAMEBOARD_IMPULSE = 'UPDATE_ROTATION_GAMEBOARD_IMPULSE'
export function updateRotationImpulse() {
    return {
        type: UPDATE_ROTATION_GAMEBOARD_IMPULSE
    }
}

export const ADD_ROTATION_GAMEBOARD_IMPULSE = 'ADD_ROTATION_GAMEBOARD_IMPULSE'
export function addRotationImpulse(rotationImpulse) {
    return {
        type: ADD_ROTATION_GAMEBOARD_IMPULSE,
        rotationImpulse: rotationImpulse
    }
}

export const STOP_GAMEBOARD_ROTATION = 'STOP_GAMEBOARD_ROTATION'
export function stopGameBoardRotation() {
    return {
        type: STOP_GAMEBOARD_ROTATION
    }
}

export const HIDE_PLAY_TURN_ERROR_MESSAGE = 'HIDE_PLAY_TURN_ERROR_MESSAGE'
export function hidePlayTurnErrorMessage() {
    return {
        type: HIDE_PLAY_TURN_ERROR_MESSAGE
    }
}

export const PLAY_TURN_FAILURE = 'PLAY_TURN_FAILURE'
export const PLAY_TURN_REQUEST= 'PLAY_TURN_REQUEST'
export const PLAY_TURN_SUCCESS= 'PLAY_TURN_SUCCESS'
export function requestPlayTurn(position) {
    return {
        type: PLAY_TURN_REQUEST,
        position: position
    }
}

function fetchPlayTurn(callParams, position) {
    return {
        [CALL_API]: {
            types: [ PLAY_TURN_SUCCESS, PLAY_TURN_FAILURE ],
            callParams: callParams,
            context: {
                position: position
            }
        }
    }
}
export function doPlayTurn(userId, gameId, position)
{
    const { x, y, z } = position;
    const url = Routing.generate('scubs_api.game.play', {
        userId: userId,
        gameId: gameId,
        x: x,
        y: y,
        z: z
    });
    const method = 'post';
    const params = [];
    const callParams = { url, method, params };

    return ( dispatch ) => {
        dispatch(requestPlayTurn(position));
        return dispatch(fetchPlayTurn(callParams, position));
    }
}
