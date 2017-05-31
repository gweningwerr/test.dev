var gulp = require('gulp'),
    less = require('gulp-less'),
    clean = require('gulp-clean'),
    concatJs = require('gulp-concat'),
    minifyJs = require('gulp-uglify'),
    glob = require('glob'),
    browserify  = require('browserify'),
    source = require('vinyl-source-stream');

gulp.task('less', function () {
    return gulp.src(['web-src/less/*.less'])
        .pipe(less({compress: true}))
        .pipe(gulp.dest('web/css/'));
});

gulp.task('images', function () {
    return gulp.src([
            'web-src/images/*'
        ])
        .pipe(gulp.dest('web/images/'))
});

gulp.task('fonts', function () {
    return gulp.src(['bower_components/bootstrap/fonts/*'])
        .pipe(gulp.dest('web/fonts/'))
});

gulp.task('lib-js', function() {
    return gulp.src([
            'bower_components/jquery/dist/jquery.js',
            'bower_components/bootstrap/dist/js/bootstrap.js',
            'bower_components/spin.js/spin.js',
            'web-src/js/plagins/spin.js'
        ])
        .pipe(concatJs('lib.js'))
        .pipe(minifyJs())
        .pipe(gulp.dest('web/js/'));
});

gulp.task('js', function() {
    return gulp.src([
            'web-src/js/*.js'
        ])
        .pipe(minifyJs())
        .pipe(gulp.dest('web/js/'));
});

gulp.task('clean', function () {
    return gulp.src(['web/css/*', 'web/js/*', 'web/images/*', 'web/fonts/*'])
        .pipe(clean());
});

gulp.task('default', ['clean'], function () {
    var tasks = ['images', 'fonts', 'less', 'lib-js', 'js'];
    tasks.forEach(function (val) {
        gulp.start(val);
    });
});

gulp.task('watch', function () {
    var less = gulp.watch('web-src/less/*.less', ['less']),
        js = gulp.watch('web-src/js/*.js', ['js']);
});