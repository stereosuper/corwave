const gulp = require('gulp');
const $ = require('gulp-load-plugins')();

const browserSync = require('browser-sync');

const reload = browserSync.reload;

const browserify = require('browserify');
const babelify = require('babelify');
const source = require('vinyl-source-stream');
const buffer = require('vinyl-buffer');
const sitemap = require('gulp-sitemap');
const es = require('event-stream');

const WP = require('wp-cli');
const shell = require('gulp-shell');

const reportError = function(error) {
    $.notify({
        title: 'An error occured with a Gulp task',
        message: 'Check you terminal for more informations',
    }).write(error);

    console.log(error.toString());
    this.emit('end');
};

gulp.task('styles', () =>
    gulp
        .src('src/scss/main.scss')
        .pipe($.sourcemaps.init())
        .pipe(
            $.sass({
                precision: 6,
                outputStyle: 'compressed',
                sourceComments: false,
                indentWidth: 4,
            })
        )
        .on('error', reportError)
        .pipe(
            $.autoprefixer({
                browsers: [
                    'ie >= 11',
                    'ie_mob >= 11',
                    'ff >= 40',
                    'chrome >= 50',
                    'safari >= 9',
                    'opera >= 23',
                    'ios >= 9',
                    'android >= 4.4',
                    'bb >= 10',
                ],
            })
        )
        .pipe($.sourcemaps.write())
        .pipe(gulp.dest('dest/wp-content/themes/corwave/css'))
        .pipe($.size({ title: 'styles' }))
);

gulp.task('fonts', () =>
    gulp
        .src('src/fonts/**/*')
        .pipe(gulp.dest('dest/wp-content/themes/corwave/fonts'))
        .pipe($.size({ title: 'fonts' }))
);

gulp.task('img', () =>
    gulp
        .src('src/img/**/*')
        .pipe(gulp.dest('dest/wp-content/themes/corwave/img'))
        .pipe($.size({ title: 'img' }))
);

gulp.task('layoutImg', () =>
    gulp
        .src('src/layoutImg/**/*')
        .pipe(gulp.dest('dest/wp-content/themes/corwave/layoutImg'))
        .pipe($.size({ title: 'layoutImg' }))
);

gulp.task('js', () =>
    browserify({
        entries: 'src/js/main.js',
        debug: true,
    })
        .transform(
            babelify.configure({
                presets: ['es2015'],
            })
        )
        .bundle()
        .pipe(source('main.js'))
        .pipe(buffer())
        .pipe($.sourcemaps.init({ loadMaps: false }))
        .pipe($.uglify())
        .pipe($.sourcemaps.write('./'))
        .pipe(gulp.dest('dest/wp-content/themes/corwave/js'))
        .pipe($.size({ title: 'js' }))
);

gulp.task('theme', () =>
    gulp
        .src('src/theme/**/*')
        .pipe(gulp.dest('dest/wp-content/themes/corwave'))
        .pipe(shell(['mkdir -p dest/wp-content/themes/corwave/acf-json']))
        .pipe(shell(['mkdir -p dest/wp-content/themes/corwave/../../plugins']))
        .pipe(shell(['mkdir -p dest/wp-content/themes/corwave/../../uploads']))
        .pipe($.size({ title: 'theme' }))
);
gulp.task('wp', () => {
    WP.discover({ path: 'dest' }, WP => {
        WP.plugin.delete('hello', (err, results) => {
            console.log(err + results);
        });
        WP.plugin.delete('askimet', (err, results) => {
            console.log(err + results);
        });
        WP.theme.activate('corwave', (err, results) => {
            console.log(err + results);

            WP.theme.delete('twentyfifteen', (err, results) => {
                console.log(err + results);
            });
            WP.theme.delete('twentyseventeen', (err, results) => {
                console.log(err + results);
            });
            WP.theme.delete('twentysixteen', (err, results) => {
                console.log(err + results);
            });
        });
    });
});

gulp.task('sitemap', () =>
    gulp
        .src('dest/wp-content/themes/corwave/**/*.html', {
            read: false,
        })
        .pipe(
            sitemap({
                siteUrl: 'http://www.corwave.fr',
            })
        )
        .pipe(gulp.dest('dest/wp-content/themes/corwave'))
);

gulp.task('root', () =>
    es.concat(
        gulp
            .src('src/.*')
            .pipe(gulp.dest('dest'))
            .pipe($.size({ title: 'root' })),
        gulp
            .src('src/*.*')
            .pipe(gulp.dest('dest'))
            .pipe($.size({ title: 'root' }))
    )
);

gulp.task('watch', () => {
    browserSync({
        notify: false,
        proxy: 'localhost',
    });

    $.watch('src/theme/**/*', () => {
        gulp.start(['theme'], reload);
    });

    $.watch('src/scss/**/*', () => {
        gulp.start(['styles'], reload);
    });
    $.watch('src/fonts/**/*', () => {
        gulp.start(['fonts'], reload);
    });
    $.watch('src/img/**/*', () => {
        gulp.start(['img'], reload);
    });
    $.watch('src/layoutImg/**/*', () => {
        gulp.start(['layoutImg'], reload);
    });
    $.watch('src/js/**/*', () => {
        gulp.start(['js'], reload);
    });
    $.watch('src/*.*', () => {
        gulp.start(['root'], reload);
    });
    $.watch('src/.*', () => {
        gulp.start(['root'], reload);
    });
});

gulp.task('start', [
    'styles',
    'theme',
    'fonts',
    'img',
    'layoutImg',
    'js',
    'root',
    'sitemap',
    'wp',
]);
