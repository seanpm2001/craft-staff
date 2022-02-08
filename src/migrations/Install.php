<?php
/**
 * staff-management plugin for Craft CMS 3.x
 *
 * Craft Staff Management provides an HR solution for payroll and benefits
 *
 * @link      http://percipio.london
 * @copyright Copyright (c) 2021 Percipio
 */

namespace percipiolondon\staff\migrations;

use craft\helpers\MigrationHelper;
use percipiolondon\staff\db\Table;

use Craft;
use craft\config\DbConfig;
use craft\db\ActiveRecord;
use craft\db\Migration;
use craft\db\Query;
use yii\base\NotSupportedException;

/**
 * Installation Migration
 *
 *
 * @author    Percipio Global Ltd. <support@percipio.london>
 * @since     1.0.0
 */
class Install extends Migration
{
    public $driver;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        if ($this->createTables()) {
            $this->createIndexes();
            $this->addForeignKeys();
            Craft::$app->db->schema->refresh();
            $this->insertDefaultData();
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {

//        $this->dropForeignKeys();
//        $this->dropTables();

        return true;
    }

    /**
     * Creates the tables for Staff Management
     */

    public function createTables()
    {
        $this->createTable(Table::ADDRESSES, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //fields
            'countryId' => $this->integer(),
            'countyId' => $this->integer(),
            'address1' => $this->string(),
            'address2' => $this->string(),
            'address3' => $this->string(),
            'zipCode' => $this->string(),
        ]);

        $this->createTable(Table::AUTO_ENROLMENT, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //FK
            'lastAssessment' => $this->integer(), // @TODO: Create FK to AutoEnrolmentAssesment [id]
            //fields
            'state' => $this->enum('state', ['Automatic', 'OptOut', 'OptIn', 'VoluntaryJoiner', 'ContractualPension', 'CeasedMembership', 'Leaver', 'Excluded', 'Enrol']),
            'stateDate' => $this->dateTime(),
            'ukWorker' => $this->enum('status', ['No', 'Yes', 'Ordinarily']),
            'daysToDeferAssessment' => $this->integer(),
            'postponementData' => $this->dateTime(),
            'deferByMonthsNotDays' => $this->boolean(),
            'exempt' => $this->boolean(),
            'aeExclusionCode' => $this->enum('code', ['NotKnown', 'NotAWorker', 'NotWorkingInUk', 'NoOrdinarilyWorkingInUk', 'OutsideOfAgeRange', 'SingleEmployee', 'CeasedActiveMembershipInPast12Mo', 'CeasedActiveMembership', 'ReceivedWulsInPast12Mo', 'ReceivedWuls', 'Leaving', 'TaxProtection', 'CisSubContractor']),
            'aePostponementLetterSent' => $this->boolean(),
        ]);

        $this->createTable(Table::AUTO_ENROLMENT_ASSESSMENT, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //FK
            'action' => $this->integer(), // @TODO: Create FK AutoEnrolmentAssesmentAction [id]
            'employee' => $this->integer(), // @TODO: Create FK Item [id]
            //fields
            'assessmentDate' => $this->dateTime(),
            'employeeState' => $this->enum('state', ['Automatic', 'OptOut', 'OptIn', 'VoluntaryJoiner', 'ContractualPension', 'CeasedMembership', 'Leaver', 'Excluded', 'Enrol']),
            'age' => $this->integer(),
            'ukWorker' => $this->enum('status', ['No', 'Yes', 'Ordinarily']),
            'payPeriod' => $this->enum('period', ['Custom', 'Monthly', 'FourWeekly', 'Fortnightly', 'Weekly', 'Daily']),
            'ordinal' => $this->integer(),
            'earningsInPeriod' => $this->double(),
            'qualifyingEarningsInPeriod' => $this->double(),
            'aeExclusionCode' => $this->enum('code', ['NotKnown', 'NotAWorker', 'NotWorkingInUk', 'NoOrdinarilyWorkingInUk', 'OutsideOfAgeRange', 'SingleEmployee', 'CeasedActiveMembershipInPast12Mo', 'CeasedActiveMembership', 'ReceivedWulsInPast12Mo', 'ReceivedWuls', 'Leaving', 'TaxProtection', 'CisSubContractor']),
            'status' => $this->enum('status', ['Eligible', 'NonEligible', 'Entitled', 'NoDuties']),
            'reason' => $this->string(),
            'assessmentId' => $this->uid(),
        ]);

        $this->createTable(Table::AUTO_ENROLMENT_ASSESSMENT_ACTION, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //fields
            'action' => $this->enum('status', ['NoChange', 'Enrol', 'Exit', 'Inconclusive', 'Postpone']),
            'employeeState' => $this->enum('state', ['Automatic', 'OptOut', 'OptIn', 'VoluntaryJoiner', 'ContractualPension', 'CeasedMembership', 'Leaver', 'Excluded', 'Enrol']),
            'actionCompleted' => $this->boolean(),
            'actionCompletedMessage' => $this->string(),
            'requiredLetter' => $this->enum('status', ['B1', 'B2', 'B3']),
            'pensionSchemeId' => $this->string(),
            'workerGroupId' => $this->string(),
            'letterNotYetSent' => $this->boolean(),
        ]);

        $this->createTable(Table::AUTO_ENROLMENT_SETTINGS, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //FK
            'defaultPension' => $this->integer(), // @TODO: create FK to PensionSelection [id]
            //fields
            'stagingDate' => $this->string(),
            'cyclicalReenrolmentDate' => $this->string(),
            'previousCyclicalReenrolmentDate' => $this->string(),
            'pensionSameAsDefault' => $this->boolean(),
            'daysToDeferAssessment' => $this->integer(),
            'deferByMonthsNotDays' => $this->boolean(),
            'deferEnrolmentBy' => $this->integer(),
            'deferEnrolmentByPeriodType' => $this->string(),
            'includeNonPensionedEmployeesInSubmission' => $this->boolean(),
        ]);

        $this->createTable(Table::BANK_DETAILS, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //fields
            'bankName' => $this->string(),
            'bankBranch' => $this->string(),
            'bankReference' => $this->string(),
            'accountName' => $this->string(),
            'accountNumber' => $this->string(),
            'sortCode' => $this->string(),
            'note' => $this->string(),
        ]);

        $this->createTable(Table::CIS_DETAILS, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //FK
            'verification' => $this->integer(), // @TODO: Create FK CisVerificationDetails [id]
            //fields
            'type' => $this->enum('type', ['SoleTrader', 'Partnership', 'Company', 'Trust']),
            'utr' => $this->string(),
            'tradingName' => $this->string(),
            'companyUtr' => $this->string(),
            'companyNumber' => $this->string(),
            'vatRegistered' => $this->boolean(),
            'vatNumber' => $this->string(),
            'vatRate' => $this->double(),
            'reverseChargeVAT' => $this->boolean(),
        ]);

        $this->createTable(Table::CIS_PARTNERSHIP, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //fields
            'name' => $this->string(),
            'utr' => $this->string(),
        ]);

        $this->createTable(Table::CIS_SUBCONTRACTOR, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //FK
            'item' => $this->integer(), // @TODO: Create FK Items [id]
            'name' => $this->integer(), // @TODO: Create FK RtiEmployeeName [id]
            'partnership' => $this->integer(), // @TODO: Create FK CisPartnership [id]
            'address' => $this->integer(), // @TODO: Create FK RtiEmployeeAddress [id]
            //fields
            'employeeUniqueId' => $this->string(),
            'emailStatementTo' => $this->string(),
            'numberOfPayments' => $this->integer(),
            'displayName' => $this->string(),
            'action' => $this->string(),
            'type'  => $this->string(),
            'tradingName' => $this->string(),
            'worksRef' => $this->string(),
            'unmatchedRate' => $this->string(),
            'utr' => $this->string(),
            'crn' => $this->string(),
            'nino' => $this->string(),
            'telephone' => $this->string(),
            'totalPaymentsUnrounded' => $this->string(),
            'costOfMaterialsUnrounded' => $this->string(),
            'umbrellaFee' => $this->string(),
            'validationMsg' => $this->string(),
            'verificationNumber' => $this->string(),
            'totalPayments' => $this->string(),
            'costOfMaterials' => $this->string(),
            'totalDeducted' => $this->string(),
            'matched' => $this->string(),
            'taxTreatment' => $this->string(),
        ]);

        $this->createTable(Table::CIS_VERIFICATION_DETAILS, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //FK
            'verificationResponse' => $this->integer(), // @TODO: Create FK CisSubcontractor [id] // Links to the CisSubContractor Table, as a Verification Response return a CisSubContractor Object
            //fields
            'manuallyEntered' => $this->boolean(),
            'matchInsteadOfVerify' => $this->boolean(),
            'number' => $this->string(),
            'date' => $this->dateTime(),
            'taxStatus' => $this->enum('status', ['Gross', 'NetOfStandardDeduction', 'NotOfHigherDeduction']),
            'verificationRequest' => $this->string(),
        ]);

        $this->createTable(Table::COUNTRIES, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //fields
            'name' => $this->string()->notNull(),
            'iso' => $this->string(3)->notNull(),
            'sortOrder' => $this->integer(),
        ]);

        $this->createTable(Table::CUSTOM_PAY_CODES, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //FK
            'payCodeCode' => $this->string(), //@TODO: Create FK to PayCode [code]
            'pensionSchemeId' => $this->integer(), //@TODO: Create FK to PensionScheme [id]
        ]);

        $this->createTable(Table::DEPARTMENT, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //fields
            'code' => $this->string(),
            'title' => $this->string(),
            // @DISCUSS - needed? Could use better things
            'color' => $this->string(),
            // @DISCUSS - needed? Can query this ourselves
            'employeeCount' => $this->integer(),
        ]);

        $this->createTable(Table::DOCUMENTS, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //FK
            'itemId' => $this->integer(), //@TODO: Create FK to Items [id]
            'noteId' => $this->integer(), //@TODO: Create FK to Note [id]
        ]);

        $this->createTable(Table::DIRECTORSHIP_DETAILS, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //fields
            'isDirector' => $this->boolean(),
            'startDate' => $this->dateTime(),
            'leaveDate' => $this->dateTime(),
            'niAlternativeMethod' => $this->boolean(),
        ]);

        $this->createTable(Table::EMPLOYEES, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //FK
            'employerId' => $this->integer()->notNull(),
            'userId' => $this->integer(),
            'personalDetailsId' => $this->integer(), // @TODO: create FK to PensionSelection [id]
            'employmentDetailsId' => $this->integer(), // @TODO: create ID to table ( FK )
            'autoEnrolmentId' => $this->integer(), // @TODO: create ID to table ( FK )
            'leaveSettingsId' => $this->integer(), // @TODO: create ID to table ( FK )
            'rightToWorkId' => $this->integer(), // @TODO: create ID to table ( FK )
            'bankDetailsId' => $this->integer(), // @TODO: create ID to table ( FK: bankName )
            'payOptionsId' => $this->integer(), // @TODO: create ID to table ( FK )
            //fields
            'staffologyId' => $this->string(255)->notNull(),
            'isDirector' => $this->boolean(),
            'status' => $this->enum('status', ['Current', 'Former', 'Upcoming'])->notNull(),
            'aeNotEnroledWarning' => $this->boolean()->defaultValue(0),
            'niNumber' => $this->string(255),
            'sourceSystemId' => $this->string(255),
        ]);

        $this->createTable(Table::EMPLOYERS, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            'slug' => $this->string(255)->notNull(),
            //FK
            'addressId' => $this->integer(), // @TODO: create FK to Addresses [id]
            'defaultPayOptionsId' => $this->integer(), // @TODO: create FK to PayOptions [id]
            'bankDetails' => $this->string(), // @TODO: create FK to BankDetails [bankName]
            'hmrcDetailsId' => $this->integer(), // @TODO: create FK to HmrcDetails [id]
            'defaultPensionId' => $this->integer(), // @TODO: create FK to PensionScheme [id]
            'rtiSubmissionSettingsId' => $this->integer(), // @TODO: create FK to RtiSubmissionSettings [id]
            'autoEnrolmentSettingsId' => $this->integer(), // @TODO: create FK to AutoEnrolmentSettings [id]
            'leaveSettingsId' => $this->integer(), // @TODO: create FK to LeaveSettings [id]
            'settingsId' => $this->integer(), // @TODO: create FK to EmployerSettings [id]
            'umbrellaSettingsId' => $this->integer(), // @TODO: create FK to umbrellaSettings [id]
//            'customPayCodes' => $this->integer(),  // Added an internal relation table CustomPayCodes to store this [id] into
            //fields
            'staffologyId' => $this->string(255)->notNull(),
            'name' => $this->string(255)->notNull(),
            'crn' => $this->string(),
            'logoUrl' => $this->string(),
            'alternativeId' => $this->string(),
            'bankPaymentsCsvFormat' => $this->enum('status', ['StandardCsv', 'Telleroo', 'BarclaysBacs', 'SantanderBacs', 'Sif', 'Revolut', 'Standard18FasterPayments', 'Standard18Bacs', 'Bankline', 'BanklineBulk', 'StandardCsvBacs', 'LloydsMultipleStandardCsvBacs', 'LloydsV11CsvBacs', 'CoOpBulkCsvBacs', 'CoOpFasterPaymentsCsv']),
            'bacsServiceUserNumber' => $this->string(),
            'bacsBureauNumber' => $this->string(),
            'rejectInvalidBankDetails' => $this->boolean(),
            'bankPaymentsReferenceFormat' => $this->string(),
            'useTenantRtiSubmissionSettings' => $this->boolean(),
            'employeeCount' => $this->integer(),
            'subcontractorCount' => $this->integer(),
            'startYear' => $this->string(255)->notNull(),
            'currentYear' => $this->string(255)->notNull(),
            'supportAccessEnabled' => $this->boolean(),
            'archived' => $this->boolean(),
            'canUseBureauFeatures' => $this->string(),
            'sourceSystemId' => $this->string(),
        ]);

        $this->createTable(Table::EMPLOYER_SETTINGS, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //fields
            'allowNegativePay' => $this->boolean(),
        ]);

        $this->createTable(Table::EMPLOYMENT_DETAILS, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //fields
            'cisSubContractor' => $this->boolean(),
            'payrollCode' => $this->string(),
            'jobTitle' => $this->string(),
            'onHold' => $this->boolean(),
            'onFurlough' => $this->boolean(),
            'furloughStart' => $this->dateTime(),
            'furloughEnd' => $this->dateTime(),
            'furloughCalculationBasis' => $this->enum('calculation', ['ActualPaidAmount', 'DailyReferenceAmount', 'MonthlyReferenceAmount']),
            'furloughCalculationBasisAmount' => $this->double(),
            'partialFurlough' => $this->boolean(),
            'furloughHoursNormallyWorked' => $this->double(),
            'furloughHoursOnFurlough' => $this->double(),
            'isApprentice' => $this->boolean(),
            'apprenticeshipStartDate' => $this->dateTime(),
            'apprenticeshipEndDate' => $this->dateTime(),
            'workingPattern' => $this->string(),
            'forcePreviousPayrollCode' => $this->string(),
            'starterDetails' => $this->integer(),
            'directorshipDetails' => $this->integer(),
            'leaverDetails' => $this->integer(),
            'cis' => $this->integer(),
            'department' => $this->integer(),
            // @DISCUSS - Staffology provides an Item Array, so should hold multiple id's?
            'posts' => $this->integer(),
        ]);

        $this->createTable(Table::FPS_FIELDS, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //fields
            'offPayrollWorker' => $this->boolean(),
            'irregularPaymentPattern' => $this->boolean(),
            'nonIndividual' => $this->boolean(),
            'hoursNormallyWorked' => $this->enum('hours', ['LessThan16', 'MoreThan16', 'MoreThan24', 'MoreThan30', 'NotRegular']),
        ]);

        $this->createTable(Table::HISTORY, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //FK
            'employerId' => $this->integer()->notNull(),
            'employeeId' => $this->integer()->notNull(),
            'administerId' => $this->integer(), // This can be null
            //fields
            'message' => $this->string(255)->notNull(),
            'type' => $this->string()->notNull(),
        ]);

        $this->createTable(Table::HMRC_DETAILS, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //fields
            'officeNumber' => $this->string(),
            'payeReference' => $this->string(),
            'accountsOfficeReference' => $this->string(),
            'econ' => $this->string(),
            'utr' => $this->string(),
            'coTax' => $this->string(),
            'employmentAllowance' => $this->boolean(),
            'employmentAllowanceMaxClaim' => $this->double(),
            'smallEmployersRelief' => $this->boolean(),
            'apprenticeshipLevy' => $this->boolean(),
            'apprenticeshipLevyAllowance' => $this->double(),
            'quarterlyPaymentSchedule' => $this->boolean(),
            'includeEmploymentAllowanceOnMonthlyJournal' => $this->boolean(),
            'carryForwardUnpaidLiabilities' => $this->boolean(),
        ]);

        $this->createTable(Table::ITEMS, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //fields
            'itemId' => $this->string(),
            'name' => $this->string(),
            'metadata' => $this->longText(),
            'url' => $this->string(),
        ]);

        $this->createTable(Table::LEAVE_SETTINGS, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //fields
            'useDefaultHolidayType' => $this->boolean(),
            'useDefaultAllowanceResetDate' => $this->boolean(),
            'useDefaultAllowance' => $this->boolean(),
            'useDefaultAccruePaymentInLieu' => $this->boolean(),
            'useDefaultAccruePaymentInLieuRate' => $this->boolean(),
            'useDefaultAccruePaymentInLieuAllGrossPay' => $this->boolean(),
            'useDefaultAccruePaymentInLieuPayAutomatically' => $this->boolean(),
            'useDefaultAccrueHoursPerDay' => $this->boolean(),
            'allowanceResetDate' => $this->dateTime(),
            'allowance' => $this->double(),
            'adjustment' => $this->double(),
            'allowanceUsed' => $this->double(),
            'allowanceUsedPreviousPeriod' => $this->double(),
            'allowanceRemaining' => $this->double(),
            'holidayType' => $this->enum('type', ['Days', 'Accrual_Money', 'Accrual_Days']),
            'accrueSetAmount' => $this->boolean(),
            'accrueHoursPerDay' => $this->double(),
            'showAllowanceOnPayslip' => $this->boolean(),
            'showAhpOnPayslip' => $this->boolean(),
            'accruePaymentInLieuRate' => $this->double(),
            'accruePaymentInLieuAllGrossPay' => $this->boolean(),
            'accruePaymentInLieuPayAutomatically' => $this->boolean(),
            'accruedPaymentLiability' => $this->double(),
            'accruedPaymentAdjustment' => $this->double(),
            'accruedPaymentPaid' => $this->double(),
            'accruedPaymentBalance' => $this->double(),
        ]);

        $this->createTable(Table::LEAVER_DETAILS, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //fields
            'hasLeft' => $this->boolean(),
            'leaveDate' => $this->dateTime(),
            'isDeceased' => $this->boolean(),
            'paymentAfterLeaving' => $this->boolean(),
            'p45Sent' => $this->boolean(),
        ]);

        $this->createTable(Table::NOTE, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //FK
            'employee' => $this->integer(), // @TODO: Create FK to Items [id]
            //fields
            'noteDate' => $this->string(),
            'noteText' => $this->string(),
            'createdBy' => $this->string(),
            'updatedBy' => $this->string(),
            'type' => $this->enum('type', ['General', 'RtwProof', 'P45']),
            'documentCount' => $this->integer(),
//            'documents' => $this->integer(), // created Documents relations table
        ]);


        $this->createTable(Table::OVERSEAS_EMPLOYER_DETAILS, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //fields
            'overseasEmployer' => $this->boolean(),
            'overseasSecondmentStatus' => $this->enum('status', ['MoreThan183Days', 'LessThan183Days', 'BothInAndOutOfUK']),
            'eeaCitizen' => $this->boolean(),
            'epm6Scheme' => $this->boolean(),
        ]);

        $this->createTable(Table::PAYLINES, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //fields
            'value' => $this->double(),
            'rate' => $this->double(),
            'multiplier' => $this->double(),
            'description' => $this->string(),
            'attachmentOrderId' => $this->string(),
            'pensionId' => $this->string(),
            'code' => $this->string(),
        ]);

        $this->createTable(Table::PAY_CODES, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //fields
            'title' => $this->string()->notNull(),
            'code' => $this->string()->notNull(),
            'defaultValue' => $this->double(),
            'isDeduction' => $this->boolean(),
            'isNiable' => $this->boolean(),
            'isTaxable' => $this->boolean(),
            'isPensionable' => $this->boolean(),
            'isAttachable' => $this->boolean(),
            'isRealTimeClass1aNiable' => $this->boolean(),
            'isNotContributingToHolidayPay' => $this->boolean(),
            'isQualifyingEarningsForAe' => $this->boolean(),
            'isNotTierable' => $this->boolean(),
            'isTcp_Tcls' => $this->boolean(),
            'isTcp_Pp' => $this->boolean(),
            'isTcp_Op' => $this->boolean(),
            'isFlexiDd_DeathBenefit' => $this->boolean(),
            'isFlexiDd_Pension' => $this->boolean(),
            'calculationType' => $this->enum('type', ['FixedAmount', 'PercentageOfGross', 'PercentageOfNet', 'MultipleOfHourlyRate']),
            'hourlyRateMultiplier' => $this->double(),
            'isSystemCode' => $this->boolean(),
            'isControlCode' => $this->boolean(),
        ]);

        $this->createTable(Table::PAY_OPTIONS, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //FK
            'taxAndNiId' => $this->integer(), // @TODO: Create FK to TaxAndNi [id]
            'fpsFieldsId' => $this->integer(), // @TODO: Create FK to fpsFields [id]
            'regularPayLinesId' => $this->integer(), // @TODO: Create FK to PayLines [id] // could hold multiple values/integers
            //fields
            'period' => $this->enum('period', ['Custom', 'Monthly', 'FourWeekly', 'Fortnightly', 'Weekly', 'Daily']),
            'ordinal' => $this->integer(),
            'payAmount' => $this->double(),
            'basis' => $this->enum('basis', ['Hourly', 'Daily', 'Monthly']),
            'nationalMinimumWage' => $this->boolean(),
            'payAmountMultiplier' => $this->double(),
            'baseHourlyRate' => $this->double(),
            'autoAdjustForLeave' => $this->boolean(),
            'method' => $this->enum('method', ['Cash', 'Cheque', 'Credit', 'DirectDebit']),
            'payCode' => $this->string(),
            'withholdTaxRefundIfPayIsZero' => $this->boolean(),
            'mileageVehicleType' => $this->enum('type', ['Car', 'Motorcycle', 'Cycle']),
            'mapsMiles' => $this->integer(),
        ]);

        $this->createTable(Table::PAYRUN, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //FK
            'totalsId' => $this->integer()->notNull(), // @TODO: create FK to PayRunTotals [id]
            'employerId' => $this->integer()->notNull()->defaultValue(null),
            //fields
            'staffologyId' => $this->string(255),
            'taxYear' => $this->string(255)->notNull()->defaultValue(''),
            'taxMonth' => $this->integer()->notNull()->defaultValue(0),
            'payPeriod' => $this->string(255)->notNull()->defaultValue(''),
            'ordinal' => $this->integer()->notNull()->defaultValue(1),
            'period' => $this->integer()->notNull()->defaultValue(1),
            'startDate' => $this->dateTime()->notNull(),
            'endDate' => $this->dateTime()->notNull(),
            'paymentDate' => $this->dateTime()->notNull(),
            'employeeCount' => $this->integer()->notNull()->defaultValue(0),
            'subContractorCount' => $this->integer()->notNull()->defaultValue(0),
            'state' => $this->string(255)->notNull()->defaultValue(''),
            'isClosed' => $this->boolean()->notNull(),
            'dateClosed' => $this->dateTime(),
            'url' => $this->string()->defaultValue(''),
        ]);

        $this->createTable(Table::PAYRUN_ENTRIES, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            // FK
            'noteId' => $this->integer(), // @TODO: create FK to Note [id]
            'priorPayrollCodeId' => $this->string(255)->defaultValue(''), // @TODO: create own table --> create ID to table (FK)
            'payOptionsId' => $this->integer(), // @TODO: create own table --> create ID to table (FK)
            'pensionSummaryId' => $this->integer(),// @TODO: create own table --> create ID to table (FK)
            'totalsId' => $this->integer(), // @TODO: create own table --> create ID to table (FK)
            'periodOverridesId' => $this->integer(), // @TODO: create own table --> create ID to table (FK)
            'totalsYtdId' => $this->integer(),// @TODO: create own table --> create ID to table (FK)
            'totalsYtdOverridesId' => $this->integer(),// @TODO: create own table --> create ID to table (FK)
            'nationalInsuranceCalculationId' => $this->integer(), // @TODO: create own table --> create ID to table (FK)
            'umbrellaPaymentId' => $this->integer(), // @TODO: create own table --> create ID to table (FK)
            'employeeId' => $this->integer(), // @TODO: create own table --> create ID to table (FK)
            'employerId' => $this->integer()->notNull()->defaultValue(null),
            'payRunId' => $this->integer()->notNull()->defaultValue(0),
            // fields
            'staffologyId' => $this->string(255)->notNull(),
            'taxYear' => $this->string(255)->defaultValue(''),
            'startDate' => $this->dateTime(),
            'endDate' => $this->dateTime(),
            'bacsSubReference' => $this->string(255)->defaultValue(''),
            'bacsHashcode' => $this->string(255)->defaultValue(''),
            'percentageOfWorkingDaysPaidAsNormal' => $this->double()->defaultValue(0),
            'workingDaysNotPaidAsNormal' => $this->double()->defaultValue(0),
            'payPeriod' => $this->string(255)->defaultValue(''),
            'ordinal' => $this->integer()->defaultValue(1),
            'period' => $this->integer()->defaultValue(1),
            'isNewStarter' => $this->boolean(),
            'unpaidAbsence' => $this->boolean(),
            'hasAttachmentOrders' => $this->boolean(),
            'paymentDate' => $this->dateTime(),
            'forcedCisVatAmount' => $this->double()->defaultValue(0),
            'holidayAccrued' => $this->double()->defaultValue(0),
            'state' => $this->string(255)->defaultValue('Open'),
            'isClosed' => $this->boolean(),
            'manualNi' => $this->boolean(),
            'payrollCodeChanged' => $this->boolean(),
            'aeNotEnroledWarning' => $this->boolean(),
            'fps' => $this->longText(),
            'receivingOffsetPay' => $this->boolean(),
            'paymentAfterLearning' => $this->boolean(),
            'pdf' => $this->string()->defaultValue(''),
        ]);

        $this->createTable(Table::PAYRUN_LOG, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            // FK
            'employerId' => $this->integer()->notNull()->defaultValue(0),
            'payRunId' => $this->integer()->notNull()->defaultValue(0),
            // fields
            'employeeCount' => $this->integer()->notNull()->defaultValue(0),
            'taxYear' => $this->string(255)->notNull()->defaultValue(''),
            'lastPeriodNumber' => $this->integer()->notNull()->defaultValue(0),
            'url' => $this->string(255)->notNull()->defaultValue(0),
        ]);

        $this->createTable(Table::PAYRUN_TOTALS, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            // fields
            'basicPay' => $this->double(),
            'gross' => $this->double(),
            'grossForNi' => $this->double(),
            'grossNotSubjectToEmployersNi' => $this->double(),
            'grossForTax' => $this->double(),
            'employerNi' => $this->double(),
            'employeeNi' => $this->double(),
            'employerNiOffPayroll' => $this->double(),
            'realTimeClass1ANi' => $this->double(),
            'tax' => $this->double(),
            'netPay' => $this->double(),
            'adjustments' => $this->double(),
            'additions' => $this->double(),
            'takeHomePay' => $this->double(),
            'nonTaxOrNICPmt' => $this->double(),
            'itemsSubjectToClass1NIC' => $this->double(),
            'dednsFromNetPay' => $this->double(),
            'tcp_Tcls' => $this->double(),
            'tcp_Pp' => $this->double(),
            'tcp_Op' => $this->double(),
            'flexiDd_Death' => $this->double(),
            'flexiDd_Death_NonTax' => $this->double(),
            'flexiDd_Pension' => $this->double(),
            'flexiDd_Pension_NonTax' => $this->double(),
            'smp' => $this->double(),
            'spp' => $this->double(),
            'sap' => $this->double(),
            'shpp' => $this->double(),
            'spbp' => $this->double(),
            'ssp' => $this->double(),
            'studentLoanRecovered' => $this->double(),
            'postgradLoanRecovered' => $this->double(),
            'pensionableEarnings' => $this->double(),
            'pensionablePay' => $this->double(),
            'nonTierablePay' => $this->double(),
            'employeePensionContribution' => $this->double(),
            'employeePensionContributionAvc' => $this->double(),
            'employerPensionContribution' => $this->double(),
            'empeePenContribnsNotPaid' => $this->double(),
            'empeePenContribnsPaid' => $this->double(),
            'attachmentOrderDeductions' => $this->double(),
            'cisDeduction' => $this->double(),
            'cisVat' => $this->double(),
            'cisUmbrellaFee' => $this->double(),
            'cisUmbrellaFeePostTax' => $this->double(),
            'pbik' => $this->double(),
            'mapsMiles' => $this->integer(),
            'umbrellaFee' => $this->double(),
            'appLevyDeduction' => $this->double(),
            'paymentAfterLeaving' => $this->double(),
            'taxOnPaymentAfterLeaving' => $this->double(),
            'nilPaid' => $this->integer(),
            'leavers' => $this->integer(),
            'starters' => $this->integer(),
            'totalCost' => $this->double(),
        ]);

        $this->createTable(Table::PENSION_ADMINISTRATOR, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //FK
            'address' => $this->integer(), //@TODO: create FK to Addresses [id]
            //fields
            'name' => $this->string(),
            'email' => $this->string(),
            'telephone' => $this->string(),
        ]);

        $this->createTable(Table::PENSION_PROVIDER, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //FK
            'address' => $this->integer(), // @TODO: create FK to Addresses [id]
            //fields
            'name' => $this->string()->notNull(),
            'accountNo' => $this->string(),
            'portal' => $this->string(),
            'website' => $this->string(),
            'telephone' => $this->string(),
            'papdisVersion' => $this->enum('papdisVersion', ['PAP10', 'PAP11']),
            'papdisProviderId' => $this->string(),
            'papdisEmployerId' => $this->string(),
            'csvFormat' => $this->enum('csvFormat', ['Papdis', 'Nest', 'NowPensions', 'TeachersPensionMdc', 'TeachersPensionMcr']),
            'excludeNilPaidFromContributions' => $this->boolean(),
            'payPeriodDateAdjustment' => $this->integer(),
            'miscBoolean1' => $this->boolean(),
            'miscBoolean2' => $this->boolean(),
            'miscString1' => $this->string(),
            'miscString2' => $this->string(),
            'optOutWindow' => $this->integer(),
            'optOutWindowIsMonths' => $this->boolean(),
        ]);

        $this->createTable(Table::PENSION_SELECTION, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //FK
            'pensionScheme' => $this->integer(), // @TODO: create FK to PensionSchema [id]
            //fields
            'pensionSchemeId' => $this->string(),
            'workerGroupId' => $this->string(),
        ]);

        $this->createTable(Table::PENSION_SCHEME, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //FK
            'provider' => $this->integer(), // @TODO: create FK to PensionProvider [id]
            'administrator' => $this->integer(), // @TODO: create FK to PensionAdministrator [id]
            'bankDetails' => $this->integer(), // @TODO: create FK to BankDetails [id]
            'customPayCodes' => $this->integer(), // @TODO: create own PayCodes --> create FK to PayCodes [code]
            //fields
            'name' => $this->string()->notNull(),
            'pensionRule' => $this->enum('period', ['ReliefAtSource', 'SalarySacrifice', 'NetPayArrangement']),
            'qualifyingScheme' => $this->boolean(),
            'disableAeLetters' => $this->boolean(),
            'subtractBasicRateTax' => $this->boolean(),
            'payMethod' =>$this->enum('period', ['Cash', 'Cheque', 'Credit', 'DirectDebit']),
            'useCustomPayCodes' => $this->boolean(),
        ]);

        $this->createTable(Table::PENSIONER_PAYROLL, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //fields
            'inReceiptOfPension' => $this->boolean(),
            'bereaved' => $this->boolean(),
            'amount' => $this->double(),
        ]);

        $this->createTable(Table::PERMISSIONS, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //fields
            'name' => $this->string(255)->notNull()->defaultValue(''),
        ]);

        $this->createTable(Table::PERMISSIONS_USERS, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //FK
            'permissionId' => $this->integer()->notNull()->defaultValue(0),
            'userId' => $this->integer()->defaultValue(null),
            'employeeId' => $this->integer()->notNull()->defaultValue(0),
        ]);

        // staff_personal_details table
        // TODO:
        // "PersonalDetails": {
        //      "PartnerDetails": null
        //    },
        $this->createTable(Table::PERSONAL_DETAILS, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //FK
            'employeeId' => $this->integer()->notNull(),
            'addressId' => $this->integer()->notNull(),
            //fields
            'maritalStatus' => $this->enum('status', ['Single', 'Married', 'Divorced', 'Widowed', 'CivilPartnership', 'Unknown'])->notNull(),
            'title' => $this->string(255),
            'firstName' => $this->string(255)->notNull(),
            'middleName' => $this->string(255),
            'lastName' => $this->string(255)->notNull(),
            'email' => $this->string(255)->notNull(),
            'emailPayslip' => $this->boolean()->notNull(),
            'passwordProtectPayslip' => $this->boolean()->notNull(),
            'pdfPassword' => $this->string(255),
            'telephone' => $this->string(255),
            'mobile' => $this->string(255),
            'dob' => $this->dateTime()->notNull(),
            'statePensionAge' => $this->integer()->notNull(),
            'gender' => $this->enum('gender', ['Male', 'Female'])->notNull(),
            'niNumber' => $this->string(255)->notNull(),
            'passportNumber' => $this->string(255)->notNull(),
        ]);

        $this->createTable(Table::REQUESTS, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //FK
            'employerId' => $this->integer()->notNull(),
            'employeeId' => $this->integer()->notNull(),
            'administerId' => $this->integer()->notNull(),
            //fields
            'dateAdministered' => $this->dateTime()->notNull(),
            'data' => $this->longText(),
            'section' => $this->string()->notNull(),
            'element' => $this->string()->notNull(),
            'status' => $this->string()->notNull(),
            'note' => $this->string(255),
        ]);

        $this->createTable(Table::RIGHT_TO_WORK, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //fields
            'checked' => $this->boolean(),
            'documentType' => $this->enum('type', ['Other', 'Visa', 'Passport', 'BirthCertificate', 'IdentityCard', 'Sharecode']),
            'documentRef' => $this->string(),
            'documentExpiry' => $this->dateTime(),
            'note' => $this->mediumText(),
        ]);

        $this->createTable(Table::RTI_CONTACT, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //fields
            'firstName' => $this->string(),
            'lastName' => $this->string(),
            'email' => $this->string(),
            'telephone' => $this->string(),
        ]);

        $this->createTable(Table::RTI_EMPLOYEE_ADDRESS, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //fields
            'line' => $this->longText(),
            'postcode_v1' => $this->string(), //staffology api call --> postcode
            'postcode_v2' => $this->string(), //staffology api call --> postCode
            'ukPostcode' => $this->string(),
            'country' => $this->string(),
        ]);

        $this->createTable(Table::RTI_EMPLOYEE_NAME, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //fields
            'ttl' => $this->string(),
            'fore' => $this->longText(),
            'initials' => $this->string(),
            'sur' => $this->string(),
        ]);

        $this->createTable(Table::RTI_SUBMISSION_SETTINGS, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //FK
            'contact' => $this->integer(), // @TODO: create table --> create FK to RtiContact [id]
            'agent' => $this->integer(), // @TODO: create table --> create FK to RtiAgent [id]
            //fields
            'senderType' =>$this->enum('declaration', ['ActingInCapacity', 'Agent', 'Bureau', 'Company', 'Employer', 'Government', 'Individual', 'Other', 'Partnership', 'Trust']),
            'senderId' => $this->string(),
            'password' => $this->string(),
            'excludeNilPaid' => $this->boolean(),
            'includeHashCrossRef' => $this->boolean(),
            'autoSubmitFps' => $this->boolean(),
            'testInLive' => $this->boolean(),
            'useTestGateway' => $this->boolean(),
            'overrideTimestampValue' => $this->string(),
        ]);

        $this->createTable(Table::STARTER_DETAILS, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //fields
            'startDate' => $this->dateTime(),
            'starterDeclaration' => $this->enum('declaration', ['A', 'B', 'C', 'Unknown']),
            'overseasEmployerDetails' => $this->integer(),
            'pensionerPayroll' => $this->integer(),
        ]);

        $this->createTable(Table::TAX_AND_NI, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //fields
            'niTable' => $this->string(),
            'secondaryClass1NotPayable' => $this->boolean(),
            'postgradLoan' => $this->boolean(),
            'postgraduateLoanStartDate' => $this->dateTime(),
            'postgraduateLoanEndDate' => $this->dateTime(),
            'studentLoan' => $this->boolean(),
            'studentLoanStartDate' => $this->dateTime(),
            'studentLoanEndDate' => $this->dateTime(),
            'taxCode' => $this->string(),
            'week1Month1' => $this->boolean(),
        ]);

        $this->createTable(Table::UMBRELLA_SETTINGS, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            // fields
            'enabled' => $this->boolean(),
            'chargePerTimesheet' => $this->double(),
            'apprenticeshipLevyDednRate' => $this->double(),
            'holidayRate' => $this->double(),
            'dpsbCode' => $this->string(),
            'expensesCode' => $this->string(),
            'grossDeductionCode' => $this->string(),
            'holidayCode' => $this->string(),
            'cisFeeCode' => $this->string(),
            'detailFeeInComment' => $this->boolean(),
        ]);

        $this->createTable(Table::USERS, [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            //FK
            'metadata' => $this->longText()->notNull(), // @TODO: create own table
            // fields
            'staffologyId' => $this->string(255)->notNull(),
        ]);

    }

    /**
     * Drop the tables
     */
    public function dropTables() {
//        $this->dropTableIfExists(Table::EMPLOYEES);
//        $this->dropTableIfExists(Table::EMPLOYERS);
//        $this->dropTableIfExists(Table::HISTORY);
//        $this->dropTableIfExists(Table::PAYRUN);
//        $this->dropTableIfExists(Table::PAYRUN_LOG);
//        $this->dropTableIfExists(Table::PAYRUN_ENTRIES);
//        $this->dropTableIfExists(Table::PERMISSIONS);
//        $this->dropTableIfExists(Table::PERMISSIONS_USERS);
//        $this->dropTableIfExists(Table::PERSONAL_DETAILS);
//        $this->dropTableIfExists(Table::REQUESTS);
//        $this->dropTableIfExists(Table::USERS);

        return null;
    }

    /**
     * Creates the indexes
     */
    public function createIndexes()
    {
        //$this->createIndex(null, Table::STAFF_EMPLOYERS, 'name', false);
        //$this->createIndex(null, Table::STAFF_EMPLOYEES, 'niNumber', false);
        //$this->createIndex(null, Table::STAFF_REQUESTS, 'element', false);
        //$this->createIndex(null, Table::STAFF_HISTORY, 'type', false);
    }

    /**
     * Removes the foreign keys
     */
    public function dropForeignKeys()
    {
        $tables = [
            Table::ADDRESSES,
            Table::AUTO_ENROLMENT,
            Table::AUTO_ENROLMENT_ASSESSMENT,
            Table::AUTO_ENROLMENT_ASSESSMENT_ACTION,
            Table::BANK_DETAILS,
            Table::CIS_DETAILS,
            Table::CIS_PARTNERSHIP,
            Table::CIS_SUBCONTRACTOR,
            Table::CIS_VERIFICATION_DETAILS,
            Table::COUNTRIES,
            Table::DEPARTMENT,
            Table::DIRECTORSHIP_DETAILS,
            Table::EMPLOYEES,
            Table::EMPLOYERS,
            Table::EMPLOYMENT_DETAILS,
            Table::FPS_FIELDS,
            Table::HISTORY,
            Table::HMRC_DETAILS,
            Table::ITEMS,
            Table::LEAVE_SETTINGS,
            Table::LEAVER_DETAILS,
            Table::OVERSEAS_EMPLOYER_DETAILS,
            Table::PAY_OPTIONS,
            Table::PAYLINES,
            Table::PAYRUN,
            Table::PAYRUN_LOG,
            Table::PAYRUN_ENTRIES,
            Table::PENSIONER_PAYROLL,
            Table::PERMISSIONS,
            Table::PERMISSIONS_USERS,
            Table::PERSONAL_DETAILS,
            Table::REQUESTS,
            Table::RIGHT_TO_WORK,
            Table::RTI_EMPLOYEE_ADDRESS,
            Table::RTI_EMPLOYEE_NAME,
            Table::STARTER_DETAILS,
            Table::TAX_AND_NI,
            Table::USERS
        ];

        foreach ($tables as $table) {
            $this->_dropForeignKeyToAndFromTable($table);
        }
        
    }

    /**
     * Insert the default data.
     */
    public function insertDefaultData()
    {
        $this->_createPermissions();
        $this->_defaultCountries();
    }

    /**
     * Insert default countries data.
     */
    private function _defaultCountries()
    {
        $countries = [
            ['ENG', 'England'],
            ['NIR', 'Northern Ireland'],
            ['SCT', 'Scotland'],
            ['WLS', 'Wales'],
            ['UKM', 'United Kingdom'],
            ['OUK', 'Outside of the UK'],
        ];

        $orderNumber = 1;
        foreach ($countries as $key => $country) {
            $country[] = $orderNumber;
            $countries[$key] = $country;
            $orderNumber++;
        }

        $this->batchInsert(Table::COUNTRIES, ['iso', 'name', 'sortOrder'], $countries);
    }

    /**
     * Creates the foreign keys needed for the Records used by the plugin
     *
     * @return void
     */
    protected function addForeignKeys()
    {
        // staff_employer table
        $this->addForeignKey(
            $this->db->getForeignKeyName(Table::STAFF_EMPLOYERS, 'id'),
            Table::STAFF_EMPLOYERS,
            'id',
            \craft\db\Table::ELEMENTS,
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            $this->db->getForeignKeyName(Table::STAFF_EMPLOYERS, 'siteId'),
            Table::STAFF_EMPLOYERS,
            'siteId',
            \craft\db\Table::SITES,
            'id',
            'CASCADE',
            'CASCADE'
        );

        // staff_employee table
        $this->addForeignKey(
            $this->db->getForeignKeyName(Table::STAFF_EMPLOYEES, 'id'),
            Table::STAFF_EMPLOYEES,
            'id',
            \craft\db\Table::ELEMENTS,
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            $this->db->getForeignKeyName(Table::STAFF_EMPLOYEES, 'siteId'),
            Table::STAFF_EMPLOYEES,
            'siteId',
            \craft\db\Table::SITES,
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            $this->db->getForeignKeyName(Table::STAFF_EMPLOYEES, 'userId'),
            Table::STAFF_EMPLOYEES,
            'userId',
            \craft\db\Table::USERS,
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            $this->db->getForeignKeyName(Table::STAFF_EMPLOYEES, 'employerId'),
            Table::STAFF_EMPLOYEES,
            'employerId',
            Table::STAFF_EMPLOYERS,
            'id',
            'CASCADE',
            'CASCADE'
        );

        // staff_payrun_log table
        $this->addForeignKey(
            $this->db->getForeignKeyName(Table::STAFF_PAYRUN_LOG, 'siteId'),
            Table::STAFF_PAYRUN_LOG,
            'siteId',
            \craft\db\Table::SITES,
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            $this->db->getForeignKeyName(Table::STAFF_PAYRUN_LOG, 'employerId'),
            Table::STAFF_PAYRUN_LOG,
            'employerId',
            Table::STAFF_EMPLOYERS,
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            $this->db->getForeignKeyName(Table::STAFF_PAYRUN_LOG, 'payRunId'),
            Table::STAFF_PAYRUN_LOG,
            'payRunId',
            Table::STAFF_PAYRUN,
            'id',
            'CASCADE',
            'CASCADE'
        );

        // staff_payrun table
        $this->addForeignKey(
            $this->db->getForeignKeyName(Table::STAFF_PAYRUN, 'id'),
            Table::STAFF_PAYRUN,
            'id',
            \craft\db\Table::ELEMENTS,
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            $this->db->getForeignKeyName(Table::STAFF_PAYRUN, 'siteId'),
            Table::STAFF_PAYRUN,
            'siteId',
            \craft\db\Table::SITES,
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            $this->db->getForeignKeyName(Table::STAFF_PAYRUN, 'employerId'),
            Table::STAFF_PAYRUN,
            'employerId',
            Table::STAFF_EMPLOYERS,
            'id',
            'CASCADE',
            'CASCADE'
        );

        // staff_payrunentries table
        $this->addForeignKey(
            $this->db->getForeignKeyName(Table::STAFF_PAYRUNENTRIES, 'id'),
            Table::STAFF_PAYRUNENTRIES,
            'id',
            \craft\db\Table::ELEMENTS,
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            $this->db->getForeignKeyName(Table::STAFF_PAYRUNENTRIES, 'siteId'),
            Table::STAFF_PAYRUNENTRIES,
            'siteId',
            \craft\db\Table::SITES,
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            $this->db->getForeignKeyName(Table::STAFF_PAYRUNENTRIES, 'payRunId'),
            Table::STAFF_PAYRUNENTRIES,
            'payRunId',
            Table::STAFF_PAYRUN,
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            $this->db->getForeignKeyName(Table::STAFF_PAYRUNENTRIES, 'employerId'),
            Table::STAFF_PAYRUNENTRIES,
            'employerId',
            Table::STAFF_EMPLOYERS,
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            $this->db->getForeignKeyName(Table::STAFF_PAYRUNENTRIES, 'employeeId'),
            Table::STAFF_PAYRUNENTRIES,
            'employeeId',
            Table::STAFF_EMPLOYEES,
            'id',
            'CASCADE',
            'CASCADE'
        );

        // staff_permissions_users
        $this->addForeignKey(
            $this->db->getForeignKeyName(Table::STAFF_PERMISSIONS_USERS, 'userId'),
            Table::STAFF_PERMISSIONS_USERS,
            'userId',
            \craft\db\Table::USERS,
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            $this->db->getForeignKeyName(Table::STAFF_PERMISSIONS_USERS, 'employeeId'),
            Table::STAFF_PERMISSIONS_USERS,
            'employeeId',
            Table::STAFF_EMPLOYEES,
            'id',
            'CASCADE',
            'CASCADE'
        );

        // staff_personal_detail
        $this->addForeignKey(
            $this->db-getForeignKeyName(Table::STAFF_PERSONAL_DETAILS, 'employeeId'),
            Table::STAFF_PERSONAL_DETAILS,
            'employeeId',
            Table::STAFF_EMPLOYEES,
            'id',
            'CASCADE',
            'CASCADE'
        );

        // staff_requests
        $this->addForeignKey(
            $this->db->getForeignKeyName(Table::STAFF_REQUESTS, 'employerId'),
            Table::STAFF_REQUESTS,
            'id',
            Table::STAFF_EMPLOYERS,
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            $this->db->getForeignKeyName(Table::STAFF_REQUESTS, 'employeeId'),
            Table::STAFF_REQUESTS,
            'id',
            Table::STAFF_EMPLOYEES,
            'id',
            'CASCADE',
            'CASCADE'
        );

        // staff_user table
        $this->addForeignKey(
            $this->db->getForeignKeyName(Table::STAFF_USERS, 'siteId'),
            Table::STAFF_USERS,
            'siteId',
            \craft\db\Table::SITES,
            'id',
            'CASCADE',
            'CASCADE'
        );

        // staff_request table
        $this->addForeignKey(
            $this->db->getForeignKeyName(Table::STAFF_REQUESTS, 'employerId'),
            Table::STAFF_REQUESTS,
            'id',
            Table::STAFF_EMPLOYERS,
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            $this->db->getForeignKeyName(Table::STAFF_REQUESTS, 'employeeId'),
            Table::STAFF_REQUESTS,
            'id',
            Table::STAFF_EMPLOYEES,
            'id',
            'CASCADE',
            'CASCADE'
        );

        // staff_history table
        $this->addForeignKey(
            $this->db->getForeignKeyName(Table::STAFF_HISTORY, 'employerId'),
            Table::STAFF_HISTORY,
            'id',
            Table::STAFF_EMPLOYERS,
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            $this->db->getForeignKeyName(Table::STAFF_HISTORY, 'employeeId'),
            Table::STAFF_HISTORY,
            'id',
            Table::STAFF_EMPLOYEES,
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * Create the permissions for the Company Users
     */
    private function _createPermissions()
    {
        $rows = [];

        $rows[] = ['access:employers'];
        $rows[] = ['access:employer'];
        $rows[] = ['access:groupbenefits'];
        $rows[] = ['access:voluntarybenefits'];
        $rows[] = ['manage:notifications'];
        $rows[] = ['manage:employees'];
        $rows[] = ['manage:employer'];
        $rows[] = ['manage:benefits'];
        $rows[] = ['purchase:groupbenefits'];
        $rows[] = ['purchase:voluntarybenefits'];

        $this->batchInsert(Table::STAFF_PERMISSIONS, ['name'], $rows);
    }
    /**
     * Returns if the table exists.
     *
     * @param string $tableName
     * @param \yii\db\Migration|null $migration
     * @return bool If the table exists.
     * @throws NotSupportedException
     */
    private function _tableExists(string $tableName): bool
    {
        $schema = $this->db->getSchema();
        $schema->refresh();
        $rawTableName = $schema->getRawTableName($tableName);
        $table = $schema->getTableSchema($rawTableName);

        return (bool)$table;
    }

    /**
     * @param string $tableName
     * @throws NotSupportedException
     */
    private function _dropForeignKeyToAndFromTable(string $tableName)
    {
        if ($this->_tableExists($tableName)) {
            MigrationHelper::dropAllForeignKeysToTable($tableName, $this);
            MigrationHelper::dropAllForeignKeysOnTable($tableName, $this);
        }
    }
}
