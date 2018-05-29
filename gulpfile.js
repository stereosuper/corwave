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



const reportError = function( error ){
    $.notify({
        title: 'An error occured with a Gulp task',
        message: 'Check you terminal for more informations'
    }).write(error);

    console.log(error.toString());
    this.emit('end');
};


gulp.task('styles', function () {
    return gulp.src('src/scss/main.scss')
        .pipe($.sourcemaps.init())
        .pipe($.sass({
            precision: 6, outputStyle: 'compressed', sourceComments: false, indentWidth: 4,
        }))
        .on('error', reportError)
        .pipe($.autoprefixer({
            browsers: [
            'ie >= 11',
            'ie_mob >= 11',
            'ff >= 40',
            'chrome >= 50',
            'safari >= 9',
            'opera >= 23',
            'ios >= 9',
            'android >= 4.4',
            'bb >= 10'
            ]
        }))
        .pipe($.sourcemaps.write())
        .pipe(gulp.dest('dest/wp-content/themes/corwave/css'))
        .pipe($.size({title: 'styles'}));
});

gulp.task('fonts', function() {
    return gulp.src('src/fonts/**/*')
        .pipe(gulp.dest('dest/wp-content/themes/corwave/fonts'))
        .pipe($.size({ title: 'fonts' }));
});

gulp.task('img', function() {
    return gulp.src('src/img/**/*')
        .pipe(gulp.dest('dest/wp-content/themes/corwave/img'))
        .pipe($.size({ title: 'img' }));
});

gulp.task('layoutImg', function() {
    return gulp.src('src/layoutImg/**/*')
        .pipe(gulp.dest('dest/wp-content/themes/corwave/layoutImg'))
        .pipe($.size({ title: 'layoutImg' }));
});

gulp.task('js', function () {
    return browserify({
            entries: 'src/js/main.js', debug: true
        })
        .transform(babelify.configure({
            presets: ['es2015']
        }))
        .bundle()
        .pipe(source('main.js'))
        .pipe(buffer())
        .pipe($.sourcemaps.init({loadMaps: true}))
        .pipe($.uglify())
        .pipe($.sourcemaps.write('./'))
        .pipe(gulp.dest('dest/wp-content/themes/corwave/js'))
        .pipe($.size({ title: 'js' }));
});


gulp.task('theme', function() {
    return gulp.src('src/theme/**/*')
        .pipe(gulp.dest('dest/wp-content/themes/corwave'))
        .pipe(shell(['mkdir -p dest/wp-content/themes/corwave/acf-json']))
        .pipe(shell(['mkdir -p dest/wp-content/themes/corwave/../../plugins']))
        .pipe(shell(['mkdir -p dest/wp-content/themes/corwave/../../uploads']))
        .pipe($.size({title: 'theme'}));
});
gulp.task('wp', function() {
    WP.discover({path: 'dest'}, function( WP ){
        WP.plugin.delete('hello', function( err, results ){
            console.log(err + results);
        });
        WP.plugin.delete('askimet', function( err, results ){
            console.log(err + results);
        });
        WP.theme.activate('corwave', function( err, results ){
            console.log(err + results);

            WP.theme.delete('twentyfifteen', function( err, results ){
                console.log(err + results);
            });
            WP.theme.delete('twentyseventeen', function( err, results ){
                console.log(err + results);
            });
            WP.theme.delete('twentysixteen', function( err, results ){
                console.log(err + results);
            });
        });
    });
});


gulp.task('sitemap', function () {
    return gulp.src('dest/wp-content/themes/corwave/**/*.html', {
            read: false
        })
        .pipe(sitemap({
            siteUrl: 'http://www.corwave.fr'
        }))
        .pipe(gulp.dest('dest/wp-content/themes/corwave'));
});

gulp.task('root', function() {
    return es.concat(
        gulp.src('src/.*')
            .pipe(gulp.dest('dest'))
            .pipe($.size({title: 'root'})),
        gulp.src('src/*.*')
            .pipe(gulp.dest('dest'))
            .pipe($.size({title: 'root'}))
    );
});

gulp.task('watch', function () {
    
    browserSync({
        notify: false,
        proxy: 'localhost'
    });

    $.watch('src/theme/**/*', function(){
        gulp.start(['theme'], reload);
    });
    
    $.watch('src/scss/**/*', function(){
        gulp.start(['styles'], reload);
    });
    $.watch('src/fonts/**/*', function(){
        gulp.start(['fonts'], reload);
    });
    $.watch('src/img/**/*', function(){
        gulp.start(['img'], reload);
    });
    $.watch('src/layoutImg/**/*', function(){
        gulp.start(['layoutImg'], reload);
    });
    $.watch('src/js/**/*', function(){
        gulp.start(['js'], reload);
    });
    $.watch('src/*.*', function(){
        gulp.start(['root'], reload);
    });
    $.watch('src/.*', function(){
        gulp.start(['root'], reload);
    });
});


gulp.task('start', ['styles', 'theme', 'fonts', 'img', 'layoutImg', 'js', 'root', 'sitemap', 'wp']);

