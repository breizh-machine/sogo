export const MOVE_CURSOR = 'MOVE_CURSOR'
export function moveCursor(direction, turns) {
    return {
        type: MOVE_CURSOR,
        direction: direction,
        turns: turns
    }
}
