'use strict';

/*
      ██████╗ ██╗   ██╗██╗     ██████╗
      ██╔════╝ ██║   ██║██║     ██╔══██╗
      ██║  ███╗██║   ██║██║     ██████╔╝
      ██║   ██║██║   ██║██║     ██╔═══╝
      ╚██████╔╝╚██████╔╝███████╗██║
      ╚═════╝  ╚═════╝ ╚══════╝╚═╝
 */

// module import
import gulp from 'gulp';
import browserSync from 'browser-sync';
import dateUtils from 'date-utils';
import insert from 'gulp-insert';
import plumber from 'gulp-plumber';
import fileinclude from 'gulp-file-include';
import runSequence from 'run-sequence';
import imagemin from 'gulp-imagemin';
import sass from 'gulp-sass';
import sassGlob from 'gulp-sass-glob';
import sourcemaps from 'gulp-sourcemaps';
import please from 'gulp-pleeease';
import webpack from 'webpack';
import webpackStream from 'webpack-stream';
import webpackConfig from './webpack.config.babel.js';
import del from 'del';
import {
  DOMAIN,
  ENV,
  directorySettings,
  styleComment
} from './buildSettings.js';

const DIR = directorySettings();

// 出力先の指定
if (ENV === 'STATIC') {
  DIR.OUTPUT = DIR.DEST;
  DIR.OUTPUT_ASSETS = DIR.DEST_ASSETS;
} else if (ENV === 'WP') {
  DIR.OUTPUT = DIR.THEMES;
  DIR.OUTPUT_ASSETS = DIR.THEMES_ASSETS;
}

// *********** COMMON METHOD ***********

// 現在時刻の取得
const fmtdDate = new Date().toFormat('YYYY-MM-DD HH24MISS');

// clean
let cleanDIR;
gulp.task('clean', () => {
  // if(args.clean) return del([cleanDIR], cb);
  // return cb();
  return del([cleanDIR], { force: true });
});

// *********** DEVELOPMENT TASK ***********

// browserSync
gulp.task('browserSync', () => {
  const settings = () => {
    const base = {
      ghostMode: {
        clicks: true,
        forms: true,
        scroll: false
      }
    };

    if (ENV === 'STATIC') {
      return {
        ...base, server: { baseDir: DIR.OUTPUT }
      };
    } else if (ENV === 'WP') {
      return {
        ...base, proxy: DOMAIN
      };
    }
  };

  browserSync.init(settings());
});

// sass
gulp.task('sass', () => {
  return gulp.src(DIR.SRC_ASSETS + 'sass/**/*.{sass,scss}')
    .pipe(sourcemaps.init())
    .pipe(plumber())
    .pipe(sassGlob())
    .pipe(sass({
      includePaths: 'node_modules/tokyo-shibuya-reset',
      outputStyle: ':expanded'
    })
    .on('error', sass.logError))
    .pipe(please({
      sass: false,
      minifier: false,
      rem: false,
      pseudoElements: false,
      mqpacker: true
    }))
    .pipe(sourcemaps.write('./'))
    .pipe(gulp.dest(DIR.OUTPUT_ASSETS + 'css/'))
    .pipe(browserSync.stream());
});

// js
gulp.task('scripts', () => {
  return gulp.src(DIR.SRC_ASSETS + 'js/**/*.js')
    .pipe(plumber())
    .pipe(webpackStream(webpackConfig.dev, webpack))
    .pipe(gulp.dest(DIR.OUTPUT_ASSETS + 'js'))
    .pipe(browserSync.stream());
});

// imageMin
gulp.task('imageMin', () => {
  return gulp.src(DIR.SRC_ASSETS + 'img/**/*')
    .pipe(imagemin(
      [
        imagemin.gifsicle({
          optimizationLevel: 3,
          interlaced: true
        }),
        imagemin.jpegtran({ progressive: true }),
        imagemin.optipng({ optimizationLevel: 5 }),
        imagemin.svgo({ removeViewBox: false })
      ],
      { verbose: true }
    ))
    .pipe(gulp.dest(DIR.OUTPUT_ASSETS + 'img/'))
    .pipe(browserSync.stream());
});


/**
 * only 'STATIC' task
 */

// html include
gulp.task('fileinclude', () => {
  return gulp.src([DIR.SRC + '**/*.html', '!' + DIR.SRC + '_inc/**/*.html'])
    .pipe(plumber())
    .pipe(fileinclude({
      prefix: '@@',
      basepath: 'app/src/_inc'
    }))
    .pipe(gulp.dest(DIR.DEST))
    .pipe(browserSync.stream());
});

/**
 * only 'WP' task
 */

// php copy
gulp.task('phpCopy', () => {
  return gulp.src(DIR.SRC + 'php/**/*.php')
    .pipe(gulp.dest(DIR.THEMES))
    .pipe(browserSync.stream());
});

// css for themes
gulp.task('cssThemes', () => {
  return gulp.src(DIR.SRC + 'style.css')
    .pipe(insert.prepend(styleComment))
    .pipe(gulp.dest(DIR.THEMES));
});

// css for editor
gulp.task('cssEditor', () => {
  return gulp.src(DIR.SRC + 'style-editor.css')
    .pipe(insert.prepend('@import url("./assets/css/main.css");'))
    .pipe(gulp.dest(DIR.THEMES));
});

// screenshot copy for themes
gulp.task('screenshot', () => {
  return gulp.src(DIR.SRC + 'screenshot.png')
    .pipe(gulp.dest(DIR.THEMES));
});


/**
 * WATCH && BUILD TASK
 */

const viewWatchDir = ENV === 'STATIC' ? '**/*.html' : 'php/**/*.php';
const viewWatchTask = ENV === 'STATIC' ? 'fileinclude' : 'phpCopy';

// watch
gulp.task('watch', () => {
  gulp.watch(DIR.SRC + viewWatchDir, [viewWatchTask]);
  gulp.watch(DIR.SRC_ASSETS + 'sass/**/*.{sass,scss}', ['sass']);
  gulp.watch(DIR.SRC_ASSETS + 'js/**/*.js', ['scripts']);
});


const initTasks = ENV === 'STATIC' ?
  ['fileinclude', 'scripts', 'sass', 'imageMin'] :
  ['phpCopy', 'cssThemes', 'cssEditor', 'screenshot', 'scripts', 'sass', 'imageMin'];

// only build
gulp.task('build', () => {
  cleanDIR = DIR.OUTPUT;
  runSequence(
    'clean',
    initTasks,
  );
});


// default
gulp.task('default', () => {
  cleanDIR = DIR.OUTPUT;
  runSequence(
    'clean',
    initTasks,
    'browserSync',
    'watch'
  );
});


// *********** RELEASE TASK ***********

// css
gulp.task('release_CSS', () => {
  return gulp.src(DIR.SRC_ASSETS + 'sass/**/*.{sass,scss}')
    .pipe(sassGlob())
    .pipe(sass({
      includePaths: 'node_modules/tokyo-shibuya-reset',
      outputStyle: ':expanded'
    }))
    .pipe(please({
      sass: false,
      minifier: true,
      rem: false,
      pseudoElements: false,
      mqpacker: true
    }))
    .pipe(insert.prepend('\n/*! compiled at:' + fmtdDate + ' */\n'))
    .pipe(gulp.dest(DIR.OUTPUT_ASSETS + 'css/'));
});

// js conat
gulp.task('release_JS', () => {
  return webpackStream(webpackConfig.prod, webpack)
  .pipe(gulp.dest(DIR.OUTPUT_ASSETS + 'js'));
});

const releaseTasks = ENV === 'STATIC' ?
  ['fileinclude', 'release_JS', 'release_CSS', 'imageMin'] :
  ['phpCopy', 'cssThemes', 'cssEditor', 'screenshot', 'release_JS', 'release_CSS', 'imageMin'];

// for release
gulp.task('release', () =>{
  cleanDIR = DIR.OUTPUT_ASSETS;
  runSequence(
    'clean',
    releaseTasks
  );
});
