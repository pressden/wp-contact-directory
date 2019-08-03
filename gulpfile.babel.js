import gulp from 'gulp';
import requireDir from 'require-dir';
import runSequence from 'run-sequence';
import livereload from 'gulp-livereload';

requireDir( './gulp-tasks' );

gulp.task( 'js', () => {
	runSequence(
		'jsclean',
		'webpack',
	);
} );

gulp.task( 'css', () => {
	runSequence(
		'cssclean',
		'cssnext',
		'cssnano',
	);
} );

gulp.task( 'watch', () => {
	livereload.listen( { basePath: 'dist' } );
	gulp.watch( ['./assets/css/**/*.css', '!./assets/css/src/**/*.css'], ['css'] );
	gulp.watch( './assets/js/**/*.js', ['js'] );
} );

gulp.task( 'default', () => {
	runSequence(
		'css',
		'js'
	);
} );
