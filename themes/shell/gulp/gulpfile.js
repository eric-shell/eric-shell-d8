// For Livereload on Chrome, download the extention:
// https://chrome.google.com/webstore/detail/livereload/jnihajbhpnppcggbcgedagnkighmdlei
// Enable it, allow access to URLs, restart Chrome
// Click the extension icon to begin using it

var gulp = require('gulp')
    sass = require('gulp-sass')
    rename = require('gulp-rename')
    minify = require('gulp-minify-css')
    prefixer = require('gulp-autoprefixer')
    sourcemaps = require('gulp-sourcemaps');
    livereload = require('gulp-livereload');

gulp.task('sass', function () {
  gulp.src('../styles/source.scss')
    .pipe(sourcemaps.init())
      .pipe(sass().on('error', sass.logError))
      .pipe(prefixer())
      .pipe(minify())
      .pipe(rename('styles.min.css'))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest('../styles'))
    .pipe(livereload());
});

gulp.task('default', function() {
  livereload.listen();
  gulp.watch('../styles/**/*.scss', ['sass']);
});
