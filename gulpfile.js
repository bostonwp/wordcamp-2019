const gulp = require( 'gulp' );
var sass = require( 'gulp-sass' );
var postcss = require( 'gulp-postcss' );
var autoprefixer = require( 'autoprefixer' );
var livereload = require('gulp-livereload');

function css() {
	var processors = [
		autoprefixer( { browsers: ['last 2 versions'] } )
	];
	return gulp.src( './sass/style.scss' )
		.pipe( sass().on( 'error', sass.logError ) )
		.pipe( postcss( processors ) )
		.pipe( gulp.dest( '.' ) )
		.pipe( livereload() );
}

function watch() {
	livereload.listen();
	gulp.watch( 'sass/**/*.scss', css );
}

exports.css = css;
exports.default = gulp.series( css, watch );
