//import { CALL_API } from '../../middleware/api'

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
