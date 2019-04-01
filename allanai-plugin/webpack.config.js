const path = require('path');

module.exports = (env, argv) => {
  let production = argv.mode === 'production'

  return {
    entry: {
      'js/admin':['@babel/polyfill', path.resolve(__dirname, 'app/admin.js')] ,
      'js/shortcode': ['@babel/polyfill', path.resolve(__dirname, 'app/shortcode.js')],
      'js/widget': ['@babel/polyfill', path.resolve(__dirname, 'app/widget.js')],
    },

    output: {
      filename: '[name].js',
      path: path.resolve(__dirname, 'assets'),
    },

    devtool: production ? '' : 'source-map',

    resolve: {
      extensions: [".js", ".jsx", ".json"],
    },

    module: {
      rules: [
        {
          test: /\.jsx?$/,
          exclude: /node_modules/,
          loader: 'babel-loader',
        },
        {
          test: /\.css?$/,
          use:[{loader:'style-loader'},{loader:'css-loader'}],
        }
      ],
    },
  };
}
