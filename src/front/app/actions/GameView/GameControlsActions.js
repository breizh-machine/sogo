export const MOVE_CURSOR = 'MOVE_CURSOR'
export function moveCursor(direction) {
    return {
        type: MOVE_CURSOR,
        direction: direction
    }
}
