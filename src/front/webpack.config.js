const path = require('path');
var webpack = require('webpack');

const PATHS = {
    app: path.join(__dirname, 'app'),
    build: path.join(__dirname, '../../web/assets')
};

module.exports = {
    entry: path.join(PATHS.app, 'index.jsx'),
    output: {
        path: PATHS.build,
        publicPath: "/assets/",
        filename: "bundle.js"
    },
    module: {
        loaders: [
            {
                //test: /\.jsx$/,
                //loader: 'jsx-loader?insertPragma=React.DOM&harmony',
                test: /.jsx?$/,
                loader: 'babel-loader',
                exclude: /node_modules/,
                query: {
                    presets: ['es2015', 'react']
                }
            }
        ]
    },
    resolve: {
        extensions: ['', '.js', '.jsx']
    }/*,
    plugins: [
        new webpack.optimize.UglifyJsPlugin({minimize: true})
    ]
    */
};