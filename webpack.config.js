/**
 * This file added for JetBrains IDEs to work with alias
 *
 * But it used as well on the webpack.config.js
 */
const path = require('path');
const ESLintPlugin = require('eslint-webpack-plugin');

module.exports = {
  plugins: [new ESLintPlugin({
    extensions: ['ts'],
  })],
  context: __dirname,
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'resources/js'),

      '~sass': __dirname + '/resources/sass/',
    },
  },
    module: {
        rules: [
            {
                test: /\.tsx?$/,
                loader: 'ts-loader',
                options: {
                    appendTsSuffixTo: [/\.vue$/],
                },
                exclude: /node_modules/,
            },
            {
                test: /\.vue$/,
                loader: 'vue-loader',
            },
        ]
    },
};
