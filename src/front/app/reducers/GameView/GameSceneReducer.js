import { combineReducers } from 'redux'
import { Vector3, Euler, Matrix4 } from 'three'
import {
    RESIZE_SCENE, TRANSLATE_CAMERA_ON_Z, ROTATE_CAMERA_ON_AXIS, ROTATE_AROUND_GAMEBOARD, UPDATE_ROTATION_GAMEBOARD_IMPULSE, ADD_ROTATION_GAMEBOARD_IMPULSE, STOP_GAMEBOARD_ROTATION
} from '../../actions/GameView/GameSceneActions'
import { rotateMatrixAroundWorldAxis, getObjectTranslationZ } from '../../tools/threeTools'

function gameScene(state = {
    width: 800,
    height: 600,
    cameraMatrix: new Matrix4(),
    rotationImpulse: 0,
    cameraRotationY: 0
}, action) {
    switch (action.type) {
        case RESIZE_SCENE:
            return Object.assign({}, state, {
                width: Math.min(action.width, 800),
                height: Math.min(action.width, 600)
            })
        case TRANSLATE_CAMERA_ON_Z:
            const newTranslationMatrix = getObjectTranslationZ(state.cameraMatrix, action.distance);
            return Object.assign({}, state, {
                cameraMatrix: newTranslationMatrix
            })
        case ROTATE_CAMERA_ON_AXIS:
            const newRotationMatrix = rotateMatrixAroundWorldAxis(state.cameraMatrix, action.axis, action.angleInDegrees);
            return Object.assign({}, state, {
                cameraMatrix: newRotationMatrix
            })
        case ROTATE_AROUND_GAMEBOARD:
            const cameraRotationY = (state.cameraRotationY + state.rotationImpulse) * 0.01;
            const newRotationMatrixY = rotateMatrixAroundWorldAxis(state.cameraMatrix, new Vector3(0, 1, 0), cameraRotationY);
            return Object.assign({}, state, {
                cameraRotationY: cameraRotationY,
                cameraMatrix: newRotationMatrixY
            })
        case ADD_ROTATION_GAMEBOARD_IMPULSE:
            const newRotationImpulse = state.rotationImpulse + action.rotationImpulse;
            return Object.assign({}, state, {
                rotationImpulse: newRotationImpulse
            })
        case UPDATE_ROTATION_GAMEBOARD_IMPULSE:
            let rotationImpulse = state.rotationImpulse - (state.rotationImpulse / 25);
            rotationImpulse = Math.abs(rotationImpulse) <= 0.1 ? 0 : rotationImpulse;
            return Object.assign({}, state, {
                rotationImpulse: rotationImpulse
            })
        case STOP_GAMEBOARD_ROTATION:
            return Object.assign({}, state, {
                rotationImpulse: 0
            })
        default:
            return state
    }
}

export default gameScene;