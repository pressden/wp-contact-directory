import gulp from 'gulp';
import cssnano from 'gulp-cssnano';
import rename from 'gulp-rename';
import sourcemaps from 'gulp-sourcemaps';
import pump from 'pump';
import livereload from 'gulp-livereload';
import filter from 'gulp-filter';

/**
 * Gulp task to run the cssnano task.
 *
 * @method
 * @author Dominic Magnifico, 10up
 * @example gulp cssnano
 * @param   {Function} cb the pipe sequence that gulp should run.
 * @returns {void}
 */
gulp.task('cssnano', cb => {
	const fileDest = './dist/css';
	const fileSrc = ['./dist/*.css'];
	const taskOpts = [
		cssnano({
			autoprefixer: false,
			calc: {
				precision: 8,
			},
			zindex: false,
			convertValues: true,
		}),
	];

	pump(
		[
			gulp.src(fileSrc),
			sourcemaps.init({
				loadMaps: true,
			}),
			cssnano(taskOpts),
			rename(path => {
				path.extname = '.min.css';
			}),
			sourcemaps.write('./'),
			gulp.dest(fileDest),
			filter('**/*.css'),
			livereload(),
		],
		cb,
	);
});
