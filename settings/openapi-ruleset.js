// Copied from: https://github.com/IBM/openapi-validator/blob/main/docs/ibm-cloud-rules.md#replace-a-rule-from-ibm-cloudopenapi-ruleset

const ibmCloudValidationRules = require('@ibm-cloud/openapi-ruleset');                           // Note 1
const { propertyCasingConvention } = require('@ibm-cloud/openapi-ruleset/src/functions');
const { schemas } = require('@ibm-cloud/openapi-ruleset-utilities/src/collections');

module.exports = {
    extends: ibmCloudValidationRules,
    rules: {
        'ibm-property-casing-convention': {                                                          // Note 2
            description: 'Property names must follow camel case',
            message: '{{error}}',
            resolved: true,                                                                            // Note 3
            given: schemas,                                                                            // Note 4
            severity: 'warn',
            then: {
                function: propertyCasingConvention,                                                      // Note 5
                functionOptions: {                                                                       // Note 6
                    type: 'camel'
                }
            }
        }
    }
};