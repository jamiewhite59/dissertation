module.exports = {
	root: true,
	env: {
		node: true,
	},
	extends: [
		'plugin:vue/vue3-essential',
		'eslint:recommended',
	],
	plugins: [
		'vue',
	],
	globals: {
		'defineProps': true,
		'defineEmits': true,
		'defineExpose': true,
		'defineModel': true,
		'route': true,
	},
	parserOptions: {
		ecmaVersion: 2020,
	},
	rules: {
		// allow async-await
		'generator-star-spacing': 'off',
		// allow debugger during development
		'no-debugger': process.env.NODE_ENV === 'production' ? 'error' : 'off',
		'indent': ['warn', 'tab', {'MemberExpression': 1,},],
		'vue/html-indent': ['error', 'tab',],
		'no-tabs': 0,
		'no-mixed-spaces-and-tabs': ['error', 'smart-tabs',],
		'comma-dangle': ['error', {
			'arrays': 'always',
			'objects': 'always',
			'imports': 'never',
			'exports': 'never',
			'functions': 'never',
		},],
		'space-before-function-paren': ['error','never',],
		// "vue/html-indent": ["error", "tab"],
		'quotes': ['error', 'single',],
		'semi': ['error', 'always',],
		'no-unused-vars': 'off',
		'no-empty-pattern': 'off',
		'keyword-spacing': ['error', { 'after': true, },],
		'vue/max-attributes-per-line': ['error', {
			'singleline': 10,
			'multiline': 2,
		},],
		'vue/html-self-closing': ['error', {
			'html': {
				'void': 'never',
				'normal': 'never',
				'component': 'always',
			},
		},],
		'vue/multi-word-component-names': 'off',
	},
};
