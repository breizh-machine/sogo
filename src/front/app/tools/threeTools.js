import THREE, { Vector3, Object3D, Matrix4, Math } from 'three'
import { gridSize, cubeSize, gridHeight } from '../components/GameView/GameScene'
import { LEFT_POSITION, RIGHT_POSITION, BOTTOM_POSITION, TOP_POSITION } from '../components/GameView/GameControls'

export function getObjectTranslationZ(objectMatrix, distance)
{
    let rotWorldMatrix = new Matrix4();
    rotWorldMatrix.makeTranslation(0, 0, distance);
    rotWorldMatrix.multiply(objectMatrix);
    return rotWorldMatrix;
}

export function rotateMatrixAroundWorldAxis(objectMatrix, axis, angleInDegrees) {
    let rotWorldMatrix = new Matrix4();
    rotWorldMatrix.makeRotationAxis(axis.normalize(), Math.degToRad(angleInDegrees));
    rotWorldMatrix.multiply(objectMatrix);
    return rotWorldMatrix;
}

export function getCursorNextPosition(currentPosition, direction, angle, turns) {
    let position = new Vector3(0, 0, 0);
    switch (direction) {
        case LEFT_POSITION:
            position = _moveCursorLeft(currentPosition, angle, turns);
            break;
        case RIGHT_POSITION:
            position = _moveCursorRight(currentPosition, angle, turns);
            break;
        case BOTTOM_POSITION:
            position = _moveCursorBottom(currentPosition, angle, turns);
            break;
        case TOP_POSITION:
            position = _moveCursorTop(currentPosition, angle, turns);
            break;
    }
    return position;
}

function _moveCursorLeft(currentPosition, angle, turns)
{
    return getNextAvailablePosition(currentPosition, new Vector3(-1, 0, 0), turns);
}

function _moveCursorRight(currentPosition, angle, turns)
{
    return getNextAvailablePosition(currentPosition, new Vector3(1, 0, 0), turns);
}

function _moveCursorTop(currentPosition, angle, turns)
{
    return getNextAvailablePosition(currentPosition, new Vector3(0, 0, 1), turns);
}

function _moveCursorBottom(currentPosition, angle, turns)
{
    return getNextAvailablePosition(currentPosition, new Vector3(0, 0, -1), turns);
}


function getNextAvailablePosition(currentPosition, direction, turns) {
    let { x, z } = currentPosition;
    if (direction.x == -1) {
        while (x > -1) {
            x--;
            let newPosition = new Vector3(x, 0, z);
            if (isPositionAvailable(newPosition, turns)) {
                newPosition.y = 4;
                return newPosition
            }
        }
    } else if (direction.x == 1) {
        while (x < 4) {
            x++;
            let newPosition = new Vector3(x, 0, z);
            if (isPositionAvailable(newPosition, turns)) {
                newPosition.y = 4;
                return newPosition
            }
        }
    } else if (direction.z == -1) {
        while (z > -1) {
            z--;
            let newPosition = new Vector3(x, 0, z);
            if (isPositionAvailable(newPosition, turns)) {
                newPosition.y = 4;
                return newPosition
            }
        }
    } else if (direction.z == 1) {
        while (z < 4) {
            z++;
            let newPosition = new Vector3(x, 0, z);
            if (isPositionAvailable(newPosition, turns)) {
                newPosition.y = 4;
                return newPosition
            }
        }
    }
    return currentPosition;
}

function isPositionAvailable(position, turns)
{
    const { x, z } = position;
    const yAtPosition = getYatPosition(position.x, position.z, turns);
    return (x > -1 && x < 4 && yAtPosition > -1 && yAtPosition < 4 && z > -1 && z < 4);
}

function getYatPosition(x, z, turns)
{
    for (var turnKey in turns) {
        var turn = turns[turnKey]
        if (turn.x == x && turn.z == z) {
            return turn.y < 3 ? turn.y + 1 : -1;
        }
    }
    return 0;
}


export function calculateWorldPosition(turnPosition) {
    let {x, y, z} = turnPosition;

    const initialYOffset = 3 * gridHeight;
    const unit = gridSize / 9;

    x =  2 * x * unit - 3 * unit;
    z =  -1 * (2 * z * unit - 3 * unit);
    y = ( initialYOffset + cubeSize) * 0.5 + y * (unit + gridHeight);

    return new Vector3(x, y, z);
}