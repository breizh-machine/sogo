import React, { Component, PropTypes } from 'react'
import THREE, { Vector3 } from 'three'

class Resources extends Component {

    constructor(props) {
        super(props);
    }

    render() {
        return <resources>
            <texture
                resourceId="localCubeTexture"
                url={this.props.localCubeTexture}
                wrapS={THREE.RepeatWrapping}
                wrapT={THREE.RepeatWrapping}
            />
            <meshPhongMaterial
                resourceId="localCubePhongMaterial"
                side={THREE.DoubleSide}
            >
                <textureResource
                    resourceId="localCubeTexture"
                />
            </meshPhongMaterial>
            <texture
                resourceId="visitorCubeTexture"
                url={this.props.visitorCubeTexture}
                wrapS={THREE.RepeatWrapping}
                wrapT={THREE.RepeatWrapping}
            />
            <meshPhongMaterial
                resourceId="visitorCubePhongMaterial"
                side={THREE.DoubleSide}
            >
                <textureResource
                    resourceId="visitorCubeTexture"
                />
            </meshPhongMaterial>
            <texture
                resourceId="gameboardTexture"
                url={this.props.gameboardTexture}
                wrapS={THREE.RepeatWrapping}
                wrapT={THREE.RepeatWrapping}
            />
            <meshPhongMaterial
                resourceId="gameboardPhongMaterial"
                side={THREE.DoubleSide}
            >
                <textureResource
                    resourceId="gameboardTexture"
                />
            </meshPhongMaterial>
        </resources>
    }
}

export default Resources