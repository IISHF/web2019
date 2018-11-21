const Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .setManifestKeyPrefix('build/')

    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())

    .enableSassLoader()
    .enablePostCssLoader()
    .enableReactPreset()

    .addEntry('public', './assets/js/public.js')
    .addEntry('admin', './assets/js/admin.js')

    .splitEntryChunks()
    .enableSingleRuntimeChunk()

    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
;

module.exports = Encore.getWebpackConfig();
