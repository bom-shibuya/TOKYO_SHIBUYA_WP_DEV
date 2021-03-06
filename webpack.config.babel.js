'use strict';
/*
      ██╗    ██╗███████╗██████╗ ██████╗  █████╗  ██████╗██╗  ██╗
      ██║    ██║██╔════╝██╔══██╗██╔══██╗██╔══██╗██╔════╝██║ ██╔╝
      ██║ █╗ ██║█████╗  ██████╔╝██████╔╝███████║██║     █████╔╝
      ██║███╗██║██╔══╝  ██╔══██╗██╔═══╝ ██╔══██║██║     ██╔═██╗
      ╚███╔███╔╝███████╗██████╔╝██║     ██║  ██║╚██████╗██║  ██╗
       ╚══╝╚══╝ ╚══════╝╚═════╝ ╚═╝     ╚═╝  ╚═╝ ╚═════╝╚═╝  ╚═╝
 */

import webpack from 'webpack';
import Path from 'path';
import { directorySettings } from './buildSettings.js';

const DIR = directorySettings('./');

const commonConfig = {
  entry: {
    script: DIR.SRC_ASSETS + 'js/script.js'
  },
  output: {
    filename: '[name].js'
  },
  // ファイル名解決のための設定
  resolve: {
    // 拡張子の省略
    extensions: ['.js'],
    // moduleのディレクトリ指定
    modules: ['node_modules'],
    // プラグインのpath解決
    alias: {
      modernizr$: Path.resolve(__dirname, '.modernizrrc'),
      ScrollToPlugin: 'gsap/ScrollToPlugin.js',
      EasePack: 'gsap/EasePack.js'
    }
  },
  // モジュール
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        loader: 'babel-loader',
        options: {
          babelrc: false,
          'presets': [
            ['env', { 'modules': false }]
          ],
          'plugins': [
            'transform-object-rest-spread'
          ]
        }
      },
      {
        test: /\.modernizrrc$/,
        loader: 'modernizr-loader'
      }
    ]
  },
  externals: {
    // jQueryはwp組み込みのものを使用する
    jquery: 'jQuery'
  },
  // プラグイン
  plugins: [
    // ファイルを細かく分析し、まとめられるところはできるだけまとめてコードを圧縮する
    new webpack.optimize.AggressiveMergingPlugin(),
    // jQueryをグローバルに出す
    new webpack.ProvidePlugin({
      jQuery: 'jquery',
      $: 'jquery',
      jquery: 'jquery',
      'window.jQuery': 'jquery'
    })
  ]
};

// for development Config
const devConfig = {
  ...commonConfig,
  devtool: 'cheap-module-source-map'
};

// for production Config
const prodConfig = {...commonConfig,
  plugins: [...commonConfig.plugins, new webpack.optimize.UglifyJsPlugin()]
};

export default {
  dev: devConfig,
  prod: prodConfig
};
