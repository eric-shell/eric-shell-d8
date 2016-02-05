var gulp = require('gulp')
    sass = require('gulp-sass')
    rename = require('gulp-rename')
    minify = require('gulp-minify-css')
    prefixer = require('gulp-autoprefixer')
    sourcemaps = require('gulp-sourcemaps');

gulp.task('sass', function () {
  gulp.src('../styles/source.scss')
    .pipe(sourcemaps.init())
      .pipe(sass().on('error', sass.logError))
      .pipe(prefixer())
      .pipe(minify())
      .pipe(rename('styles.min.css'))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest('../styles'));
});

gulp.task('default', function() {
  gulp.watch('../styles/**/*.scss', ['sass']);
});
