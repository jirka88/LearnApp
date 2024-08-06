module.exports = {
    testEnvironment: 'jsdom',
    transform: {
        '^.+\\.vue$': '@vue/vue3-jest',
        '^.+\\js$': 'babel-jest',
        '^.+\\.(ts|tsx)$': 'ts-jest'
    },
    testRegex: '(/tests/Unit/Frontend/.*|(\\.|/)(test|spec))\\.(js|ts)$',
    moduleNameMapper: {
        '^@/(.*)$': '<rootDir>/resources/js/$1',
        '^~/(.*)$': '<rootDir>/resources/$1'
    },
    moduleFileExtensions: ['vue', 'js', 'ts', 'tsx'],
    coveragePathIgnorePatterns: ['/node_modules/', '/tests/'],
    coverageReporters: ['text', 'json-summary'],
    transformIgnorePatterns: ['/node_modules/'],
    // Fix in order for vue-test-utils to work with Jest 29
    // https://test-utils.vuejs.org/migration/#test-runners-upgrade-notes
    testEnvironmentOptions: {
        customExportConditions: ['node', 'node-addons']
    }
}
