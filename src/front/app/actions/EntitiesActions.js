export const GAME_CREATED_ACTION = 'GAME_CREATED_ACTION'
export function updateGames(newGame) {
    return {
        type: GAME_CREATED_ACTION,
        newGame: newGame
    }
}

export const INIT_ENTITIES = 'INIT_ENTITIES'
export function initEntities(entities) {
    return {
        type: INIT_ENTITIES,
        entities: entities
    }
}