import{g as e}from"./useApolloClient.40cb325f.js";const a=e`
    query BenefitVariant($id: [QueryArgument]) {
        BenefitVariant(id: $id) {
            id
            name
            policy {
                  id
                  internalCode
                  providerId
                  employerId
                  benefitTypeId
                  status
                  policyName
                  policyNumber
                  policyHolder
                  policyStartDate
                  policyRenewalDate
                  paymentFrequency
                  commissionRate
                  description
            }
            totalRewardsStatement {
                  title
                  monetaryValue
                  startDate
                  endDate
            }
            employees {
                  id
                  personalDetails{
                        firstName
                        lastName
                  }
            }
        }
    }
`,i=e`
    query BenefitVariants($employeeId: Int, $policyId: Int) {
        BenefitVariants(policyId: $policyId, employeeId: $employeeId, orderBy: "name ASC") {
            id
            name
            policy {
                  id
                  internalCode
                  providerId
                  employerId
                  benefitTypeId
                  status
                  policyName
                  policyNumber
                  policyHolder
                  policyStartDate
                  policyRenewalDate
                  paymentFrequency
                  commissionRate
                  description
            }
            totalRewardsStatement {
                  title
                  monetaryValue
                  startDate
                  endDate
            }
            employees {
                  id
                  personalDetails{
                        firstName
                        lastName
                  }
            }
        }
    }
`,o=e`
    query BenefitVariantEligibleEmployees($policyId: Int!) {
          BenefitVariantEligibleEmployees(policyId: $policyId) {
                id,
                personalDetails {
                      firstName
                      lastName
                }
          }
    }
`,l=e`
    mutation AddEmployee($employeeId: Int!, $variantId: Int!) {
          AddEmployee(employeeId: $employeeId, variantId: $variantId) {
                id,
                personalDetails {
                    firstName
                    lastName
                }
          }
    }
`,n=e`
    mutation RemoveEmployee($employeeId: Int!, $variantId: Int!) {
          RemoveEmployee(employeeId: $employeeId, variantId: $variantId) {
                id
          }
    }

`;export{l as A,n as R,o as V,a,i as b};
//# sourceMappingURL=variants.de1667b3.js.map
