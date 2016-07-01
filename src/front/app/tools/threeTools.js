import THREE, { Vector3, Object3D, Matrix4, Math } from 'three'

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