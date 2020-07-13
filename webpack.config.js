const Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .setManifestKeyPrefix('build/')

    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .enableIntegrityHashes(Encore.isProduction())

    .enableSassLoader()
    .enablePostCssLoader((options) => {
        options.config = {
            path: 'assets/postcss.config.js'
        };
    })
    .enableReactPreset()
    .configureUrlLoader(
        {
            fonts: {limit: 4096},
            images: {limit: 4096}
        }
    )

    .autoProvidejQuery()

    .addEntry('public', './assets/js/public.js')
    .addEntry('login', './assets/js/login.js')
    .addEntry('admin', './assets/js/admin.js')
    .copyFiles({
        from: './assets/img',
        to: 'images/[path][name].[hash:8].[ext]',
    })

    .splitEntryChunks()
    .enableSingleRuntimeChunk()

    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()

    .configureBabel(
        (babelConfig) => {
            if (!Encore.isProduction()) {
                babelConfig.plugins.push('react-hot-loader/babel');
            }
            babelConfig.plugins.push('@babel/plugin-proposal-class-properties');
            babelConfig.plugins.push('@babel/plugin-transform-runtime');
        },
        {
            useBuiltIns: 'usage',
            corejs: 3
        }
    )
;

let config = Encore.getWebpackConfig();
if (!Encore.isProduction()) {
    config.devtool = 'cheap-module-source-map';
    config.resolve.alias['react-dom'] = '@hot-loader/react-dom';
}

module.exports = config;
