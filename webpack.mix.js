const mix = require('laravel-mix');
const path = require('path');

require('laravel-mix-tailwind');
require('laravel-mix-purgecss');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

// Webpack 4 does not support package.json "exports" field.
// Manually alias @tiptap/pm subpaths to their dist files.
const tiptapPm = path.resolve(__dirname, 'node_modules/@tiptap/pm/dist');
mix.webpackConfig({
    resolve: {
        alias: {
            '@tiptap/pm/changeset':    path.join(tiptapPm, 'changeset/index.js'),
            '@tiptap/pm/collab':       path.join(tiptapPm, 'collab/index.js'),
            '@tiptap/pm/commands':     path.join(tiptapPm, 'commands/index.js'),
            '@tiptap/pm/dropcursor':   path.join(tiptapPm, 'dropcursor/index.js'),
            '@tiptap/pm/gapcursor':    path.join(tiptapPm, 'gapcursor/index.js'),
            '@tiptap/pm/history':      path.join(tiptapPm, 'history/index.js'),
            '@tiptap/pm/inputrules':   path.join(tiptapPm, 'inputrules/index.js'),
            '@tiptap/pm/keymap':       path.join(tiptapPm, 'keymap/index.js'),
            '@tiptap/pm/markdown':     path.join(tiptapPm, 'markdown/index.js'),
            '@tiptap/pm/menu':         path.join(tiptapPm, 'menu/index.js'),
            '@tiptap/pm/model':        path.join(tiptapPm, 'model/index.js'),
            '@tiptap/pm/schema-basic': path.join(tiptapPm, 'schema-basic/index.js'),
            '@tiptap/pm/schema-list':  path.join(tiptapPm, 'schema-list/index.js'),
            '@tiptap/pm/state':        path.join(tiptapPm, 'state/index.js'),
            '@tiptap/pm/tables':       path.join(tiptapPm, 'tables/index.js'),
            '@tiptap/pm/transform':    path.join(tiptapPm, 'transform/index.js'),
            '@tiptap/pm/view':         path.join(tiptapPm, 'view/index.js'),
        },
    },
});

mix.js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css')
   .tailwind('./tailwind.config.js')
   .purgeCss();

if (mix.inProduction()) {
  mix.version();
}
