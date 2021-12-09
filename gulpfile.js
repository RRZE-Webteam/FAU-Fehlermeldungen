'use strict';
/* 
 * Gulp Builder for WordPress Plugin FAU ORGA Breadcrumb
 */
const
    {src, dest, watch, series, parallel} = require('gulp'),
    sass = require('gulp-sass')(require('sass')),
    bump = require('gulp-bump'),
    semver = require('semver'),
    info = require('./package.json'),
    wpPot = require('gulp-wp-pot'),
    touch = require('gulp-touch-cmd'),
    header = require('gulp-header'),
    rename = require('gulp-rename'),
    yargs = require('yargs')
;



/**
 * Template for banner to add to file headers
 */

var banner = [
    '/*!',
    'Plugin Name: <%= info.name %>',
    'Version: <%= info.version %>',
    'GitHub Plugin URI: <%= info.repository.url %>',
    '*/'].join('\n');



/* 
 * Compile main style with SASS and clean them up 
 */
function buildmainstyle() {

  return src([info.source.sass + info.source.cssmain + '.scss'])
   .pipe(header(banner, { info : info }))
    .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
    .pipe(rename(info.source.cssmain + '.css'))
    .pipe(dest(info.dest.css))
    .pipe(touch());
}

/* 
 * Compile main style for dev without postcss minifying
 */

function devbuildmainstyle() {
  return src([info.source.sass + info.source.cssmain + '.scss'])
   .pipe(header(banner, { info : info }))
    .pipe(sass({sourceComments: true, outputStyle: 'expanded'}).on('error', sass.logError))
    .pipe(rename(info.source.cssmain + '.css'))
    .pipe(dest(info.dest.css))
    .pipe(touch());
}



/* 
 * Compile admin style with SASS and clean them up 
 */
function buildadminstyle() {
  //  var plugins = [
  //      autoprefixer(),
  //      cssnano()
 //   ];
  return src([info.source.sass + info.source.cssadmin + '.scss'])
   .pipe(header(banner, { info : info }))
    .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
 //   .pipe(postcss(plugins))
    .pipe(rename(info.source.cssadmin + '.css'))
    .pipe(dest(info.dest.css))
    .pipe(touch());
}




function updatepot()  {
  return src(['**/*.php', '!vendor/**/*.php'])
  .pipe(
      wpPot({
        domain: info.textdomain,
        package: info.name,
	team: info.author,
 	bugReport: info.repository.issues,
	ignoreTemplateNameHeader: true
      })
    )
  .pipe(dest(`languages/${info.textdomain}.pot`))
  .pipe(touch());;
};


/*
 * Update Version on Patch-Level
 *  (All other levels we are doing manually; This level has to update automatically on each build)
 */
function upversionpatch() {
    var newVer = semver.inc(info.version, 'patch');
    return src(['./package.json', info.main])
        .pipe(bump({
            version: newVer
        }))
        .pipe(dest('./'))
	.pipe(touch());
};


/*
 * Update DEV Version on prerelease level.
 *  Reason: in the Theme function, we will recognise the prerelease version by its syntax. 
 *  This will allow the theme automatically switch to the non-minified-files instead of
 *   the minified versions.
 *   In other words: If we use dev, the theme wil load script files without ".min.".  
 */
function devversion() {
    var newVer = semver.inc(info.version, 'prerelease');
    return src(['./package.json', info.main])
        .pipe(bump({
            version: newVer
        }))
	.pipe(dest('./'))
	.pipe(touch());;
};





exports.pot = updatepot;
exports.devversion = devversion;
exports.buildmainstyle = buildmainstyle;


var dev = series(devbuildmainstyle, devversion);

exports.cssdev = devbuildmainstyle;
exports.css = devbuildmainstyle;
exports.dev = dev;
exports.build = series(buildmainstyle, upversionpatch);

exports.default = dev;

