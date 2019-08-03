import gulp from 'gulp';
import pump from 'pump';
import webpack from 'webpack';
import webpackStream from 'webpack-stream';
import livereload from 'gulp-livereload';

function processWebpack( src, conf, dest, cb, mode ) {
	const config = require( conf ); // eslint-disable-line
	config.mode  = mode;

	if ( mode === 'production' ) {
		config.output.filename = '[name].min.js';
	} else {
		config.output.filename = '[name].js';
	}

	pump(
		[
			gulp.src( src ),
			webpackStream( config, webpack ),
			gulp.dest( dest ),
			livereload(),
		],
		cb,
	);
}

gulp.task( 'webpack', () => {
	const src = '../assets/js/**/*.js';
	const conf = '../webpack.config.babel.js';
	const dest = './dist/js';
	processWebpack( src, conf, dest, null, 'production' );
	processWebpack( src, conf, dest, null, 'development' );
} );
