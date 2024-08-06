module.export = {
    root: true,
    env: {
        browser: true,
        node: true
    },
    extends: [
        'plugin:vue/recommended',
        'eslint:recommended',
        'plugin:prettier/recommended'
    ],
    rules: {
        'no-console': 'warn',
        'no-debugger': 'warn',
        'no-unused-vars': 'warn',
        'vue/valid-define-props': 'error',
        'vue/require-default-prop': 'warn'
    }
}
