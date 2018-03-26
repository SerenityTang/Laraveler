'use strict';

var gulp = require('gulp');
var $ = require('gulp-load-plugins')();
var browserify = require('browserify');
var transform = require('vinyl-transform');
var markJSON = require('markit-json');
var docUtil = require('amazeui-doc-util');
var browserSync = require('browser-sync');
var del = require('del');
var runSequence = require('run-sequence');
var reload = browserSync.reload;

gulp.task('clean', function(cb) {
  del('dist', cb);
});

gulp.task('uglify', function() {
  return gulp.src('amazeui.tagsinput.js')
    .pipe($.uglify())
    .pipe($.rename(function(file) {
      file.basename = file.basename.toLowerCase();
      if (file.basename === 'readme') {
        file.basename = 'index';
      }
      file.extname = '.min.js';
    }))
    .pipe(gulp.dest('./'));
});

gulp.task('copy:js', function() {
  return gulp.src('amazeui.tagsinput*.js')
    .pipe(gulp.dest('dist'));
});

gulp.task('copy:assets', function() {
  return gulp.src('docs/js/*')
    .pipe(gulp.dest('dist/docs/js'));
});

gulp.task('copy', function(cb) {
  runSequence(['uglify', 'copy:assets'], 'copy:js', cb);
});

gulp.task('docs', function(){
  return gulp.src(['README.md', 'docs/*.md'])
    .pipe(markJSON(docUtil.markedOptions))
    .pipe(docUtil.applyTemplate(null, {
      pluginTitle: 'Amaze UI Tags Input',
      pluginDesc: 'Amaze UI 风格的 jQuery 标签输入插件。',
      buttons: 'amazeui/tagsinput',
      head: '<link rel="stylesheet" href="../amazeui.tagsinput.css"/>'
    }))
    .pipe($.rename(function(file) {
      file.basename = file.basename.toLowerCase();
      if (file.basename === 'readme') {
        file.basename = 'index';
      }
      file.extname = '.html';
    }))
    .pipe(gulp.dest(function(file) {
      if (file.relative === 'index.html') {
        return 'dist'
      }
      return 'dist/docs';
    }));
});

gulp.task('less', function() {
  return gulp.src('src/amazeui.tagsinput.less')
    .pipe($.less())
    .pipe($.autoprefixer({browsers: docUtil.autoprefixerBrowsers}))
    .pipe($.csso())
    .pipe(gulp.dest('./dist'))
    .pipe(gulp.dest('./'));
});

gulp.task('bundle', function() {
  var bundler = transform(function(filename) {
    var b = browserify({
      entries: filename,
      basedir: './'
    });
    return b.bundle();
  });

  gulp.src('test/main.js')
    .pipe(bundler)
    .pipe($.rename({
      basename: 'bundle'
    }))
    .pipe(gulp.dest('test'))
});

// Watch Files For Changes & Reload
gulp.task('serve', ['default'], function () {
  browserSync({
    notify: false,
    server: 'dist',
    logPrefix: 'AMP'
  }, function(err, bs) {
    console.log(bs.options.getIn(['urls', 'local']));
  });

  gulp.watch('dist/**/*', reload);
});

gulp.task('deploy', ['default'], function() {
  return gulp.src('dist/**/*')
    .pipe($.ghPages());
});

gulp.task('watch', function() {
  gulp.watch(['README.md', 'docs/*.md'], ['docs']);
  gulp.watch('src/*.less', ['less']);
  gulp.watch('./*tagsinput*.js', ['copy']);
});

gulp.task('default', function(cb) {
  runSequence('clean', ['copy', 'less', 'docs', 'watch'], cb);
});
