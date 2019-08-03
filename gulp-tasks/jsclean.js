import gulp from 'gulp';
import del from 'del';

gulp.task( 'jsclean', () => {
	del( ['./dist/js/*.js', './dist/js/*.js.map'] );
} );
