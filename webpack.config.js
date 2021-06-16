const path                      = require('path');
const OptimizeCssAssetsPlugin   = require('optimize-css-assets-webpack-plugin');
const CssEntryPlugin            = require('css-entry-webpack-plugin');

const config = {
	entry: {
		index: './src/js/index.js',
		style: './src/scss/style.scss',
	},
	output: {
		filename: 'js/[name].js',
		path: path.resolve(__dirname, 'assets')
	},
	module: {
		rules: [
			{
				test: /\.scss$/,
		/* 		use: ExtractTextPlugin.extract({
					fallback: 'style-loader', */
				  	use: ['css-loader?url=false', 'postcss-loader', 'sass-loader']
				//}),
			},
			{
				test: /\.js$/,
				exclude: /(node_modules)/,
				loader: 'babel-loader',
			}
		]
	},
	plugins: [
		new CssEntryPlugin({
			output: {
			  filename: "/css/[name].css"
			}
		}),
	]
};

//If true JS and CSS files will be minified
if (process.env.NODE_ENV === 'production') {
	config.plugins.push(
		//new UglifyJSPlugin(),
		new OptimizeCssAssetsPlugin()
	);
}

module.exports = config;
