export function addOrUpdateEntity(entities, newEntity, idKey = 'id')
{
    let entitiesCopy = Object.assign({}, entities);
    let index = newEntity[idKey];
    entitiesCopy[index] = newEntity;
    return entitiesCopy;
}

export function convertEntitiesArrayToObject(entities, idKey = 'id')
{
    let convertedArr = {};
    for (var i = 0; i < entities.length; i++) {
        convertedArr[entities[i][idKey]] = entities[i];
    }
    return convertedArr;
}