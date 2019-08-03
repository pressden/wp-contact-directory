import gulp from 'gulp';
import postcss from 'gulp-postcss';
import sourcemaps from 'gulp-sourcemaps';
import pump from 'pump';

/**
 * Gulp task to run the sass task.
 *
 * @method
 * @author Dominic Magnifico, 10up
 * @example gulp sass
 * @param   {Function} cb the pipe sequence that gulp should run.
 * @returns {void}
 */
gulp.task( 'cssnext', cb => {
	const fileSrc = [
		'./assets/css/frontend/frontend.css',
	];
	const fileDest = './dist';
	const cssNextOpts = {
		features: {
			autoprefixer: {
				browsers: ['last 8 versions'],
			},
		},
	};
	const taskOpts = [
		require( 'postcss-import' ),
		require( 'postcss-cssnext' )( cssNextOpts ),
	];

	pump(
		[
			gulp.src( fileSrc, { allowEmpty: true } ),
			sourcemaps.init( {
				loadMaps: true,
			} ),
			postcss( taskOpts ),
			sourcemaps.write( './css', {
				mapFile( mapFilePath ) {
					return mapFilePath.replace( '.css.map', '.min.css.map' );
				},
			} ),
			gulp.dest( fileDest ),
		],
		cb,
	);
} );
