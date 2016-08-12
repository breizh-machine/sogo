export function isUuid(value)
{
    if (!isValidString(value)) {
        return false;
    }
    return value.match('^[A-Za-z0-9]{8}-[A-Za-z0-9]{4}-[A-Za-z0-9]{4}-[A-Za-z0-9]{4}-[A-Za-z0-9]{12}$')
}


export function isValidString(value)
{
    return (value !== undefined && value !== '' && value !== null && typeof value === 'string');
}