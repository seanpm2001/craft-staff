import { gql } from 'graphql-tag'

export const PAYRUNS = gql`
    query Payruns($employerId: [ID]) {
        payruns(employerId: $employerId, orderBy: "startDate desc") {
            id,
            employerId
            taxYear
            period
            employeeCount
            startDate @formatDateTime(format:"jS M, Y")
            endDate @formatDateTime(format:"jS M, Y")
            paymentDate @formatDateTime(format:"jS M, Y")
            dateUpdated @formatDateTime(format:"jS M, Y")
            dateSynced:dateUpdated @formatDateTime(format:"Y-m-d H:i")
            employer
            state
            totals{
                totalCost
            }
        }
    }
`

export const PAYRUN = gql`
    query Payrun($id: [QueryArgument]) {
        payrun(id: $id) {
            id,
            paymentDate @formatDateTime(format:"j M, Y")
            dateSynced:dateUpdated @formatDateTime(format:"Y-m-d H:i")
            employerId
            taxYear
            period
            totals {
                totalCost
                gross
                tax
                employerNi
                employeeNi
            }
        }
    }
`