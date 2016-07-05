import THREE, { Vector3, Object3D, Matrix4, Math } from 'three'
import { gridSize, cubeSize, gridHeight } from '../components/GameView/GameScene'
import { LEFT_POSITION, RIGHT_POSITION, BOTTOM_POSITION, TOP_POSITION } from '../components/GameView/GameControls'

export const ORIENTATION_NORTH = 'N';
export const ORIENTATION_WEST = 'W';
export const ORIENTATION_SOUTH = 'S';
export const ORIENTATION_EAST = 'E';

export function radToDegrees(radians) {
    const PI = 3.141592653589793;
    return radians * 180 / PI;
};

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
    let orientation;
    let cAngle = radToDegrees(angle) + 180;
    if (cAngle > 150 && cAngle < 210) {
        orientation = ORIENTATION_NORTH;
    } else if (cAngle > 210 && cAngle < 330) {
        orientation = ORIENTATION_EAST;
    } else if (cAngle > 330 || cAngle < 30) {
        orientation = ORIENTATION_SOUTH;
    } else if (cAngle > 30 && cAngle < 150) {
        orientation = ORIENTATION_WEST;
    }
    switch (direction) {
        case LEFT_POSITION:
            switch (orientation) {
                case ORIENTATION_NORTH:
                    position = _moveCursorLeft(currentPosition, angle, turns);
                    break;
                case ORIENTATION_EAST:
                    position = _moveCursorBottom(currentPosition, angle, turns);
                    break;
                case ORIENTATION_SOUTH:
                    position = _moveCursorRight(currentPosition, angle, turns);
                    break;
                case ORIENTATION_WEST:
                    position = _moveCursorTop(currentPosition, angle, turns);
                    break;
            }
            break;
        case RIGHT_POSITION:
            switch (orientation) {
                case ORIENTATION_NORTH:
                    position = _moveCursorRight(currentPosition, angle, turns);
                    break;
                case ORIENTATION_EAST:
                    position = _moveCursorTop(currentPosition, angle, turns);
                    break;
                case ORIENTATION_SOUTH:
                    position = _moveCursorLeft(currentPosition, angle, turns);
                    break;
                case ORIENTATION_WEST:
                    position = _moveCursorBottom(currentPosition, angle, turns);
                    break;
            }
            break;
        case BOTTOM_POSITION:
            switch (orientation) {
                case ORIENTATION_NORTH:
                    position = _moveCursorBottom(currentPosition, angle, turns);
                    break;
                case ORIENTATION_EAST:
                    position = _moveCursorRight(currentPosition, angle, turns);
                    break;
                case ORIENTATION_SOUTH:
                    position = _moveCursorTop(currentPosition, angle, turns);
                    break;
                case ORIENTATION_WEST:
                    position = _moveCursorLeft(currentPosition, angle, turns);
                    break;
            }
            break;
        case TOP_POSITION:
            switch (orientation) {
                case ORIENTATION_NORTH:
                    position = _moveCursorTop(currentPosition, angle, turns);
                    break;
                case ORIENTATION_EAST:
                    position = _moveCursorLeft(currentPosition, angle, turns);
                    break;
                case ORIENTATION_SOUTH:
                    position = _moveCursorBottom(currentPosition, angle, turns);
                    break;
                case ORIENTATION_WEST:
                    position = _moveCursorRight(currentPosition, angle, turns);
                    break;
            }
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
            if (!isPositionOutOfBound(newPosition, turns)) {
                newPosition.y = 4;
                return newPosition
            }
        }
    } else if (direction.x == 1) {
        while (x < 4) {
            x++;
            let newPosition = new Vector3(x, 0, z);
            if (!isPositionOutOfBound(newPosition, turns)) {
                newPosition.y = 4;
                return newPosition
            }
        }
    } else if (direction.z == -1) {
        while (z > -1) {
            z--;
            let newPosition = new Vector3(x, 0, z);
            if (!isPositionOutOfBound(newPosition, turns)) {
                newPosition.y = 4;
                return newPosition
            }
        }
    } else if (direction.z == 1) {
        while (z < 4) {
            z++;
            let newPosition = new Vector3(x, 0, z);
            if (!isPositionOutOfBound(newPosition, turns)) {
                newPosition.y = 4;
                return newPosition
            }
        }
    }
    return currentPosition;
}

function isPositionOutOfBound(position)
{
    const { x, z } = position;
    return !(x > -1 && x < 4 && z > -1 && z < 4);
}

export function isPositionAvailable(position, turns)
{
    const yAtPosition = getYatPosition(position.x, position.z, turns);
    return !isPositionOutOfBound(position) && yAtPosition > -1 && yAtPosition < 4;
}

function getYatPosition(x, z, turns)
{
    let maxY = -1;
    for (var turnKey in turns) {
        var turn = turns[turnKey]
        if (turn.x == x && turn.z == z) {
            if (turn.y > maxY) {
                maxY = turn.y;
            }

        }
    }
    return maxY < 3 ? maxY + 1 : -1;
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