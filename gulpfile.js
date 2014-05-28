var gulp = require('gulp'),
	clean = require('gulp-clean'),
	sass = require('gulp-sass'),
	concat = require('gulp-concat'),
	todo = require('gulp-todo'),
	rename = require('gulp-rename'),
	imagemin = require('gulp-imagemin'),
	minifyCSS = require('gulp-minify-css'),
	uglify = require('gulp-uglify'),
	paths = {
		scripts: './js/*.js',
		styles: ['./css/*.css', '!./css/enhance.css'],
		sass: {
			src: './sass/*.scss',
			dest: './dist/css/'
		},
		images: './images/**/*',
		fonts: './fonts/**/*'
	};

gulp.task('scripts', function() {
	return gulp.src(paths.scripts)
		.pipe(gulp.dest('./dist/js/'))
		.pipe(uglify())
		.pipe(rename(function (path) {
			path.extname = '.min.js';
		}))
		.pipe(gulp.dest('./dist/js/'));
});

gulp.task('styles-move-enhanced', function () {
	return gulp.src('./css/enhance.css')
		.pipe(gulp.dest('./dist/css/'))
		.pipe(minifyCSS())
		.pipe(rename(function (path) {
			path.extname = '.min.css';
		}))
		.pipe(gulp.dest('./dist/css/'))
});

gulp.task('styles', ['styles-move-enhanced'], function() {
	return gulp.src(paths.styles)
		.pipe(concat('styles.css'))
		.pipe(gulp.dest('./dist/css/'))
		.pipe(minifyCSS())
		.pipe(rename(function (path) {
			path.extname = '.min.css';
		}))
		.pipe(gulp.dest('./dist/css/'));
});

gulp.task('sass', function () {
	return gulp.src(paths.sass.src)
		.pipe(sass({
			error: function (css) {
				console.log(css);
				console.log(stats);
			}
		}))
		.pipe(gulp.dest(paths.sass.dest));
});

gulp.task('images', function() {
	return gulp.src(paths.images)
		.pipe(imagemin())
		.pipe(gulp.dest('./dist/images/'));
});

gulp.task('fonts', function () {
	return gulp.src(paths.fonts)
		.pipe(gulp.dest('./dist/fonts/'));
});

gulp.task('build-todo', function() {
	return gulp.src(paths.scripts)
		.pipe(todo())
		.pipe(gulp.dest('.'));
});

gulp.task('clean', function() {
	return gulp.src(['dist/css', 'dist/js'], {read: false})
		.pipe(clean());
});

gulp.task('watch', function() {
	gulp.watch(paths.scripts, ['scripts']);
	gulp.watch(paths.styles, ['styles']);
	gulp.watch(paths.images, ['images']);
	gulp.watch(paths.sass.src, ['sass']);
});

gulp.task('default', ['clean'], function() {
	gulp.start('scripts', 'styles', 'images', 'fonts', 'sass', 'build-todo');
});
