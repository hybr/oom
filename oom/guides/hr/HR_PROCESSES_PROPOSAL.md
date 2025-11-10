# HR Department Business Processes - Proposal

## Overview

This document proposes 15 comprehensive HR business processes covering the complete employee lifecycle from recruitment to exit. All processes are designed to work with the existing HR entities and follow the same process flow architecture.

---

## **CATEGORY 1: RECRUITMENT & HIRING** (5 Processes)

### 1. Vacancy Creation & Approval Process ✅ (ALREADY EXISTS)
**File:** See `guides/features/VACANCY_CREATION_PROCESS.md`
**Code:** `VACANCY_CREATION`
**Entity:** ORGANIZATION_VACANCY

**Status:** Already documented and implemented
**Purpose:** Multi-level approval workflow for creating job vacancies

**Workflow Summary:**
- Draft Vacancy (HR Manager/Department Head)
- HR Review
- Budget Check Decision
- Finance Approval (for high-budget positions)
- Department Head Approval + Workstation Assignment
- Publish Vacancy

**Key Features:**
- Conditional routing based on budget
- Workstation assignment
- Rejection loops
- Multi-creator support

---

### 2. Application Screening & Shortlisting Process
**Proposed Code:** `APPLICATION_SCREENING_WORKFLOW`
**Primary Entity:** VACANCY_APPLICATION
**Related Entities:** APPLICATION_REVIEW, PERSON, PERSON_EDUCATION, PERSON_SKILL

**Purpose:** Screen, review, and shortlist candidates for interviews

**Proposed Workflow:**
```
START
  ↓
[RECEIVE_APPLICATION] (System - Auto)
  • Candidate submits application
  • Status: SUBMITTED
  • Attach resume, cover letter
  ↓
[AUTO_SCREENING] (System - Auto, 1hr SLA)
  • Check minimum qualifications
  • Education level verification
  • Experience requirement check
  • Skills matching
  • Reject if not meeting minimum criteria
  ↓ (IF PASSED)
[ASSIGN_REVIEWER] (Recruiter, 2hr SLA)
  • Assign to HR recruiter based on workload
  • Create APPLICATION_REVIEW record
  ↓
[INITIAL_REVIEW] (HR Recruiter, 24hr SLA)
  • Review resume and cover letter
  • Evaluate qualifications
  • Check red flags
  • Decision: SHORTLIST / REJECT / REQUEST_INFO
  ↓ (IF REQUEST_INFO)
[REQUEST_CLARIFICATION] (System, 1hr SLA)
  • Send clarification request to candidate
  • Wait for response (48hr deadline)
  ↓ (Loop back to INITIAL_REVIEW)
  ↓ (IF SHORTLIST)
[TECHNICAL_REVIEW] (Hiring Manager, 48hr SLA)
  • Technical skills assessment
  • Experience relevance check
  • Cultural fit evaluation
  • Decision: APPROVE_FOR_INTERVIEW / REJECT
  ↓ (IF APPROVED)
[UPDATE_STATUS] (System, 1hr SLA)
  • Update application status to SHORTLISTED
  • Notify candidate of shortlisting
  • Notify interview scheduler
  ↓
END
```

**Key Business Rules:**
- Auto-reject if minimum qualifications not met
- Require at least 2 reviews before shortlisting
- Shortlisting approval from hiring manager required
- Maximum review time: 72 hours from submission

**Rejection Criteria:**
- Incomplete application
- Below minimum education requirement
- Insufficient experience
- Skills mismatch >60%
- Salary expectation too high (>150% of budget)

---

### 3. Interview Scheduling & Management Process
**Proposed Code:** `INTERVIEW_MANAGEMENT_WORKFLOW`
**Primary Entity:** APPLICATION_INTERVIEW
**Related Entities:** VACANCY_APPLICATION, INTERVIEW_STAGE

**Purpose:** Schedule, conduct, and evaluate candidate interviews

**Proposed Workflow:**
```
START (Triggered when application status = SHORTLISTED)
  ↓
[IDENTIFY_INTERVIEW_STAGES] (Recruiter, 4hr SLA)
  • Determine interview rounds needed
  • Typical: Initial Screening → Technical → HR → Final
  • Assign interviewers for each stage
  ↓
[SCHEDULE_FIRST_INTERVIEW] (Recruiter, 12hr SLA)
  • Check interviewer availability
  • Send interview invite to candidate
  • Book meeting room/video conference
  • Status: SCHEDULED
  ↓
[SEND_REMINDER] (System, Auto)
  • 24 hours before interview
  • Send reminders to all parties
  ↓
[CONDUCT_INTERVIEW] (Interviewer, During scheduled time)
  • Mark interview as IN_PROGRESS
  • Conduct interview
  • Take notes
  ↓
[SUBMIT_FEEDBACK] (Interviewer, 4hr SLA after interview)
  • Rating (1-5 scale)
  • Strengths/Weaknesses
  • Recommendation: PROCEED / REJECT / RECONSIDER
  • Upload interview notes
  ↓
[REVIEW_FEEDBACK] (Hiring Manager, 24hr SLA)
  • Aggregate feedback from all interviewers
  • Decision point:
    - IF positive → Schedule next round OR Move to OFFER
    - IF negative → REJECT
    - IF mixed → Request additional interview
  ↓ (IF NEXT ROUND)
[SCHEDULE_NEXT_INTERVIEW] (Loop back to scheduling)
  ↓ (IF ALL ROUNDS COMPLETE & POSITIVE)
[FINAL_APPROVAL] (Department Head, 24hr SLA)
  • Review all interview feedback
  • Approve for job offer
  • Update application status to INTERVIEW_PASSED
  ↓
[NOTIFY_CANDIDATE] (System, 1hr SLA)
  • Inform candidate of decision
  • If positive: Prepare for offer
  • If negative: Send rejection with feedback
  ↓
END
```

**Key Features:**
- Multi-stage interview support
- Automated scheduling and reminders
- Feedback aggregation
- Rating standardization
- No-show handling
- Rescheduling support

**Interview Stages (configurable per vacancy):**
- Phone Screening (15-30 min)
- Technical Interview (1-2 hr)
- HR Interview (30-45 min)
- Managerial Interview (1 hr)
- Panel Interview (1-2 hr)

---

### 4. Job Offer Generation & Negotiation Process
**Proposed Code:** `JOB_OFFER_WORKFLOW`
**Primary Entity:** JOB_OFFER
**Related Entities:** VACANCY_APPLICATION, POPULAR_ORGANIZATION_POSITION

**Purpose:** Generate, negotiate, and finalize job offers

**Proposed Workflow:**
```
START (Triggered when application approved for offer)
  ↓
[PREPARE_OFFER] (HR Manager, 24hr SLA)
  • Pull vacancy salary range
  • Review candidate's expected salary
  • Prepare initial offer package
    - Base salary
    - Benefits
    - Joining bonus (if applicable)
    - Start date
    - Terms & conditions
  ↓
[FINANCE_APPROVAL] (Finance Manager, 48hr SLA)
  • Verify budget availability
  • Approve compensation package
  • Decision: APPROVE / REQUEST_REVISION
  ↓ (IF REVISION NEEDED - loop back)
  ↓ (IF APPROVED)
[DEPARTMENT_HEAD_APPROVAL] (Department Head, 24hr SLA)
  • Review and approve final offer
  • Confirm reporting structure
  • Approve start date
  ↓
[GENERATE_OFFER_LETTER] (System, 2hr SLA)
  • Generate formal offer letter
  • Include all terms and conditions
  • Set expiry date (typically 7-14 days)
  • Status: SENT
  ↓
[SEND_OFFER] (HR Coordinator, 4hr SLA)
  • Email offer letter to candidate
  • Schedule offer discussion call
  • Await candidate response
  ↓
[NEGOTIATION] (HR Manager, As needed)
  • IF candidate requests negotiation:
    - Review counter-offer
    - Consult with department head
    - Revise offer if within acceptable range
    - Loop back to approval if significant changes
  • ELSE: Proceed to decision
  ↓
[CANDIDATE_DECISION] (Candidate, By expiry date)
  • ACCEPTED → Move to contract creation
  • REJECTED → Close offer, reopen vacancy
  • COUNTER_OFFER → Loop to negotiation
  • EXPIRED → Close offer, contact candidate
  ↓ (IF ACCEPTED)
[UPDATE_RECORDS] (System, 1hr SLA)
  • Update offer status to ACCEPTED
  • Update application status to OFFER_ACCEPTED
  • Notify all stakeholders
  • Trigger onboarding process
  ↓
END
```

**Negotiation Parameters:**
- Salary: ±10% of initial offer
- Joining date: Flexible within 90 days
- Benefits: Fixed (company policy)
- Joining bonus: Case-by-case (up to 15% of annual salary)

**Offer Expiry:**
- Standard: 7 days
- Senior positions: 14 days
- Automatic reminders at 50% and 80% of expiry period

---

### 5. Employment Contract Creation Process
**Proposed Code:** `EMPLOYMENT_CONTRACT_WORKFLOW`
**Primary Entity:** EMPLOYMENT_CONTRACT
**Related Entities:** JOB_OFFER, PERSON, POPULAR_ORGANIZATION_POSITION

**Purpose:** Generate and execute employment contracts

**Proposed Workflow:**
```
START (Triggered when job offer accepted)
  ↓
[GATHER_INFORMATION] (HR Coordinator, 12hr SLA)
  • Collect candidate documents:
    - ID proof
    - Address proof
    - Education certificates
    - Previous employment records
    - Bank details
    - Emergency contacts
  ↓
[DOCUMENT_VERIFICATION] (HR Officer, 24hr SLA)
  • Verify authenticity of documents
  • Background verification
  • Reference checks
  • Education verification
  • Decision: CLEARED / ISSUES_FOUND
  ↓ (IF ISSUES)
[RESOLVE_ISSUES] (HR Manager, 48hr SLA)
  • Contact candidate for clarification
  • Escalate if serious discrepancies
  • May withdraw offer if fraudulent
  ↓ (IF CLEARED)
[GENERATE_CONTRACT] (Legal/HR, 24hr SLA)
  • Generate employment contract from template
  • Include:
    - Position details
    - Compensation & benefits
    - Probation period (typically 3-6 months)
    - Notice period
    - Non-compete clause
    - Confidentiality agreement
    - Terms & conditions
  ↓
[LEGAL_REVIEW] (Legal Team, 48hr SLA)
  • Review contract for compliance
  • Ensure regulatory requirements met
  • Approve for signature
  ↓
[SEND_FOR_SIGNATURE] (HR Coordinator, 4hr SLA)
  • Email contract to candidate
  • Include e-signature link
  • Set deadline (typically 5 days)
  ↓
[CANDIDATE_SIGNATURE] (Candidate, 5 days)
  • Review and sign contract
  • Upload any pending documents
  ↓
[EMPLOYER_SIGNATURE] (Authorized Signatory, 24hr SLA)
  • HR Head or Department Head signs
  • Finalize contract
  ↓
[CREATE_EMPLOYMENT_RECORD] (System, 2hr SLA)
  • Create EMPLOYMENT_CONTRACT record
  • Status: ACTIVE (or PENDING if start date future)
  • Link to position, department, workstation
  • Generate employee ID
  ↓
[SETUP_EMPLOYEE_ACCOUNTS] (IT/Admin, 48hr SLA)
  • Email account
  • System access
  • Badge/ID card
  • Add to organizational directory
  ↓
[TRIGGER_ONBOARDING] (System, 1hr SLA)
  • Start onboarding process
  • Send welcome email
  • Share onboarding schedule
  ↓
END
```

**Contract Types:**
- FULL_TIME (permanent)
- PART_TIME (permanent/contract)
- CONTRACT (fixed term)
- INTERN (3-6 months)
- TEMPORARY (project-based)

**Probation Periods:**
- Entry level: 3 months
- Mid-level: 3-6 months
- Senior level: 6 months
- Executive: 6-12 months

---

## **CATEGORY 2: EMPLOYEE LIFECYCLE** (5 Processes)

### 6. Employee Onboarding Process
**Proposed Code:** `EMPLOYEE_ONBOARDING_WORKFLOW`
**Primary Entity:** EMPLOYMENT_CONTRACT
**Related Entities:** PERSON, WORKSTATION, ORGANIZATION

**Purpose:** Structured onboarding for new employees

**Proposed Workflow:**
```
START (1 week before joining date)
  ↓
[PRE_BOARDING] (HR Coordinator, 7 days before)
  • Send welcome email
  • Share employee handbook
  • Provide office directions/parking info
  • Request final documents if pending
  • Assign onboarding buddy
  ↓
[PREPARE_WORKSPACE] (Admin, 3 days before)
  • Assign workstation (link to WORKSTATION entity)
  • Setup desk and equipment
  • Prepare welcome kit
  • Arrange laptop/computer
  ↓
[DAY 1 - ORIENTATION] (HR + Manager, Day 1)
  • Welcome and office tour
  • Introduction to team
  • IT setup and account activation
  • ID card issuance
  • Sign attendance register
  • Share first-week schedule
  ↓
[WEEK 1 - ADMIN SETUP] (HR + IT, Week 1)
  • Complete HR formalities
  • System training
  • Policy review sessions
  • Safety induction
  • Benefits enrollment
  ↓
[WEEK 2-4 - ROLE TRAINING] (Manager + Buddy, Weeks 2-4)
  • Role-specific training
  • Process orientation
  • Tool training
  • Shadow team members
  • Initial task assignments
  ↓
[30-DAY REVIEW] (Manager, Day 30)
  • Check-in with employee
  • Gather feedback
  • Address concerns
  • Set 90-day goals
  ↓
[60-DAY REVIEW] (Manager, Day 60)
  • Progress assessment
  • Adjust training if needed
  • Prepare for probation review
  ↓
[COMPLETE_ONBOARDING] (HR, Day 90)
  • Final onboarding checklist
  • Collect feedback survey
  • Mark onboarding complete
  • Trigger probation review process
  ↓
END
```

**Onboarding Checklist:**
- Welcome kit received
- Workstation assigned
- IT accounts created
- ID card issued
- Attendance system registered
- Benefits enrolled
- Policies acknowledged
- Training completed
- 30-day & 60-day reviews done

---

### 7. Probation Period Review Process
**Proposed Code:** `PROBATION_REVIEW_WORKFLOW`
**Primary Entity:** EMPLOYMENT_CONTRACT
**Related Entities:** PERSON, POPULAR_ORGANIZATION_POSITION

**Purpose:** Evaluate employee performance during probation

**Proposed Workflow:**
```
START (Triggered 15 days before probation end)
  ↓
[NOTIFY_STAKEHOLDERS] (System, Auto)
  • Alert manager
  • Alert HR
  • Alert employee
  ↓
[MANAGER_ASSESSMENT] (Reporting Manager, 10 days before)
  • Evaluate performance
  • Review deliverables
  • Assess:
    - Job knowledge
    - Quality of work
    - Attendance & punctuality
    - Teamwork
    - Initiative
    - Learning ability
  • Rating: 1-5 scale
  • Recommendation: CONFIRM / EXTEND / TERMINATE
  ↓
[PEER_FEEDBACK] (Team Members, Optional, 7 days before)
  • 360-degree feedback
  • Anonymous survey
  • Team collaboration assessment
  ↓
[HR_REVIEW] (HR Manager, 5 days before)
  • Review attendance records
  • Check disciplinary issues
  • Verify completion of training
  • Compliance check
  ↓
[CONSOLIDATE_FEEDBACK] (HR, 3 days before)
  • Aggregate all feedback
  • Prepare review document
  • Schedule review meeting
  ↓
[PROBATION_REVIEW_MEETING] (Manager + HR, 2 days before)
  • Discuss performance with employee
  • Share feedback
  • Employee self-assessment
  • Decision discussion
  ↓
[DEPARTMENT_HEAD_APPROVAL] (Department Head, 1 day before)
  • Final decision:
    - CONFIRMED → Permanent employment
    - EXTENDED → Extend probation (3 months max)
    - TERMINATED → End employment
  ↓ (IF CONFIRMED)
[UPDATE_CONTRACT] (HR, On probation end date)
  • Update employment status to CONFIRMED
  • Remove probation flag
  • Send confirmation letter
  • Update salary if increment applicable
  ↓ (IF EXTENDED)
[EXTEND_PROBATION] (HR, Before end date)
  • Issue extension letter
  • Set new review date
  • Document extension reasons
  ↓ (IF TERMINATED)
[INITIATE_EXIT] (HR, Before end date)
  • Trigger exit process
  • Settlement processing
  • Access revocation
  ↓
END
```

**Probation Evaluation Criteria:**
- Performance: 40%
- Attendance & Punctuality: 15%
- Learning & Development: 20%
- Teamwork & Collaboration: 15%
- Initiative & Problem Solving: 10%

**Minimum Passing Score:** 60%

---

### 8. Performance Appraisal Process
**Proposed Code:** `PERFORMANCE_APPRAISAL_WORKFLOW`
**Primary Entity:** EMPLOYMENT_CONTRACT
**Related Entities:** PERSON, POPULAR_ORGANIZATION_POSITION

**Purpose:** Annual/periodic performance review and rating

**Proposed Workflow:**
```
START (Annual/Semi-annual cycle)
  ↓
[INITIATE_APPRAISAL_CYCLE] (HR, Cycle start)
  • Define appraisal period
  • Set timeline (typically 4-6 weeks)
  • Communicate to all employees
  • Open appraisal forms
  ↓
[SELF-APPRAISAL] (Employee, Week 1-2, SLA: 10 days)
  • Complete self-assessment form
  • List achievements
  • Highlight challenges
  • Set future goals
  • Training needs identification
  ↓
[MANAGER_APPRAISAL] (Reporting Manager, Week 2-3, SLA: 10 days)
  • Review self-appraisal
  • Evaluate against KPIs/goals
  • Rate performance areas:
    - Goal achievement
    - Quality of work
    - Productivity
    - Innovation
    - Leadership (if applicable)
    - Teamwork
  • Overall rating: 1-5 scale
  • Provide detailed feedback
  ↓
[PEER_REVIEW] (Peers, Optional, Week 2-3)
  • 360-degree feedback
  • Team collaboration rating
  • Anonymous submission
  ↓
[NORMALIZE_RATINGS] (HR Analytics, Week 4)
  • Calibration across departments
  • Bell curve distribution
  • Identify outliers
  • Ensure fairness
  ↓
[APPRAISAL_MEETING] (Manager + Employee, Week 4-5)
  • Discuss performance
  • Share rating and feedback
  • Employee acknowledgment
  • Development plan discussion
  • Next year goal setting
  ↓
[DEPARTMENT_HEAD_REVIEW] (Department Head, Week 5)
  • Review all appraisals in department
  • Approve ratings
  • Identify promotion candidates
  • Flag underperformers
  ↓
[HR_REVIEW] (HR Head, Week 5-6)
  • Final calibration
  • Approve increment recommendations
  • Process promotions
  • Plan Performance Improvement Plans (PIP)
  ↓
[MANAGEMENT_APPROVAL] (Leadership Team, Week 6)
  • Approve increment budget allocation
  • Approve promotions
  • Finalize ratings
  ↓
[COMMUNICATE_RESULTS] (HR + Manager, Week 6)
  • Communicate final ratings
  • Issue increment letters
  • Process promotions
  • Initiate PIP for underperformers
  ↓
[UPDATE_RECORDS] (System, Week 6)
  • Update employee records
  • Update salary
  • Update position if promoted
  • Archive appraisal documents
  ↓
END
```

**Rating Scale:**
- 5 = Outstanding (Top 5%)
- 4 = Exceeds Expectations (15-20%)
- 3 = Meets Expectations (60-70%)
- 2 = Needs Improvement (10-15%)
- 1 = Unsatisfactory (<5%)

**Increment Guidelines:**
- Rating 5: 15-20% increment
- Rating 4: 10-15% increment
- Rating 3: 6-10% increment
- Rating 2: 3-5% increment
- Rating 1: 0% increment + PIP

---

### 9. Promotion & Transfer Process
**Proposed Code:** `PROMOTION_TRANSFER_WORKFLOW`
**Primary Entity:** EMPLOYMENT_CONTRACT
**Related Entities:** PERSON, POPULAR_ORGANIZATION_POSITION

**Purpose:** Handle employee promotions and internal transfers

**Proposed Workflow:**
```
START (Triggered by manager recommendation or internal job posting)
  ↓
[INITIATE_REQUEST] (Manager/Employee, Day 1)
  • Type: PROMOTION / LATERAL_TRANSFER / DEPARTMENT_TRANSFER
  • Justification
  • Proposed new position
  • Proposed effective date
  ↓
[HR_ELIGIBILITY_CHECK] (HR, 3 days)
  • Verify tenure (minimum 12-18 months)
  • Check performance history (minimum 3.5/5 rating)
  • Verify no disciplinary issues
  • Decision: ELIGIBLE / NOT_ELIGIBLE
  ↓ (IF NOT ELIGIBLE - END)
  ↓ (IF ELIGIBLE)
[ASSESS_READINESS] (Current Manager, 5 days)
  • Evaluate employee readiness
  • Review skills for new role
  • Provide recommendation
  • Decision: SUPPORT / DO_NOT_SUPPORT
  ↓
[HIRING_MANAGER_INTERVIEW] (Target Position Manager, 1 week)
  • IF PROMOTION: Internal interview
  • IF TRANSFER: Fit assessment
  • Evaluate capability for new role
  • Decision: APPROVE / REJECT
  ↓ (IF REJECTED - END with feedback)
  ↓ (IF APPROVED)
[SALARY_REVISION] (HR + Department Head, 3 days)
  • IF PROMOTION:
    - Calculate increment (typically 10-20%)
    - Update salary band
  • IF LATERAL TRANSFER:
    - No increment (or minimal adjustment)
  ↓
[BUDGET_APPROVAL] (Finance, 2 days)
  • IF new position budget available
  • IF increment within budget
  • Decision: APPROVED / REJECTED
  ↓
[DEPARTMENTHEAD_APPROVAL] (Both Dept Heads, 3 days)
  • Current department head: Release approval
  • New department head: Acceptance approval
  • Decision: BOTH_APPROVE / REJECTED
  ↓
[HR_HEAD_APPROVAL] (HR Head, 2 days)
  • Final approval
  • Verify policy compliance
  • Approve effective date
  ↓
[PREPARE_DOCUMENTS] (HR, 3 days)
  • Generate promotion/transfer letter
  • Update job description
  • Revise employment contract (if needed)
  ↓
[COMMUNICATE_DECISION] (HR + Managers, 1 day)
  • Inform employee
  • Issue official letter
  • Communicate to teams
  • Update organizational chart
  ↓
[TRANSITION_PLANNING] (Both Managers, 2 weeks)
  • Knowledge transfer
  • Handover responsibilities
  • Brief on new role
  ↓
[EFFECTIVE_DATE] (System, On effective date)
  • Update EMPLOYMENT_CONTRACT:
    - New position_id
    - New salary
    - New department
    - New workstation (if applicable)
  • Update system access
  • Update reporting structure
  ↓
[POST_TRANSITION_REVIEW] (New Manager, 30 days)
  • Check-in with employee
  • Address transition challenges
  • Confirm successful transition
  ↓
END
```

**Eligibility Criteria:**
- **Promotion:**
  - Minimum tenure: 18 months
  - Performance rating: ≥3.5/5
  - No active PIP or disciplinary issues

- **Transfer:**
  - Minimum tenure: 12 months
  - Performance rating: ≥3.0/5
  - Business justification required

**Increment Guidelines for Promotion:**
- One level up: 10-15%
- Two levels up: 15-25%
- Leadership role: 20-30%

---

### 10. Employee Exit/Offboarding Process
**Proposed Code:** `EMPLOYEE_OFFBOARDING_WORKFLOW`
**Primary Entity:** EMPLOYMENT_CONTRACT
**Related Entities:** PERSON, WORKSTATION

**Purpose:** Structured exit process for departing employees

**Proposed Workflow:**
```
START (Employee resigns OR termination initiated)
  ↓
[RESIGNATION_SUBMISSION] (Employee, Day 1)
  • Submit resignation letter
  • Mention last working day
  • Reason for leaving (optional)
  ↓ OR
[TERMINATION_INITIATION] (Manager/HR, Day 1)
  • Document termination reason
  • Legal/compliance check
  • Approval from Department Head + HR Head
  ↓
[ACKNOWLEDGE_RESIGNATION] (HR, 1 day)
  • Accept resignation
  • Calculate notice period
  • Verify notice period compliance
  • Issue acknowledgment letter
  ↓
[COUNTER_OFFER] (Manager, Optional, 2 days)
  • IF valuable employee:
    - Discuss reasons for leaving
    - Present counter-offer if appropriate
  • Employee decision:
    - ACCEPT → Withdraw resignation
    - DECLINE → Continue exit
  ↓ (IF EXIT CONTINUES)
[EXIT_INTERVIEW_SCHEDULING] (HR, 1 week before)
  • Schedule exit interview
  • Send exit questionnaire
  ↓
[KNOWLEDGE_TRANSFER] (Employee + Manager, Notice period)
  • Document handover checklist
  • Transfer responsibilities
  • Train replacement (if available)
  • Complete pending tasks
  ↓
[EXIT_INTERVIEW] (HR, 3 days before last day)
  • Conduct exit interview
  • Gather feedback
  • Identify retention issues
  • Discuss future opportunities (alumni network)
  ↓
[CLEARANCE_PROCESS] (Multiple departments, Last week)
  • IT: Return laptop, phone, access cards
  • Finance: Clear advances/loans
  • Admin: Return office keys, parking pass
  • Library: Return books/resources
  • HR: Collect documents
  • All departments sign clearance form
  ↓
[REVOKE_ACCESS] (IT/Admin, Last day)
  • Disable email and system access
  • Revoke building access
  • Deactivate employee ID
  ↓
[FINAL_SETTLEMENT] (Finance + HR, Last day)
  • Calculate final settlement:
    - Salary for notice period worked
    - Unused leave encashment
    - Bonus/incentives (if applicable)
    - Deductions (if any)
  • Issue Form 16 (tax documents)
  • Release Full & Final statement
  ↓
[UPDATE_EMPLOYMENT_CONTRACT] (System, Last day)
  • Update status to COMPLETED/TERMINATED
  • Set end_date
  • Archive employee record
  ↓
[ISSUE_DOCUMENTS] (HR, Within 7 days)
  • Experience certificate
  • Relieving letter
  • Service certificate
  • Any other statutory documents
  ↓
[POST_EXIT_SURVEY] (HR, 30 days after)
  • Send follow-up survey
  • Gather additional feedback
  • Add to alumni network
  ↓
END
```

**Notice Period (as per contract):**
- Entry level: 1 month
- Mid-level: 2 months
- Senior level: 3 months
- Leadership: 3-6 months

**Exit Categories:**
- RESIGNATION (voluntary)
- RETIREMENT
- TERMINATION (performance/misconduct)
- CONTRACT_END
- MUTUAL_SEPARATION
- DEATH (In service)

---

## **CATEGORY 3: LEAVE & ATTENDANCE** (2 Processes)

### 11. Leave Request & Approval Process
**Proposed Code:** `LEAVE_REQUEST_WORKFLOW`
**Primary Entity:** (New entity needed: LEAVE_REQUEST)
**Related Entities:** EMPLOYMENT_CONTRACT, PERSON

**Note:** Requires new LEAVE_REQUEST entity with fields:
- employee_id, leave_type, start_date, end_date, number_of_days, reason, status, approved_by

**Proposed Workflow:**
```
START
  ↓
[SUBMIT_LEAVE_REQUEST] (Employee, Anytime)
  • Select leave type:
    - CASUAL_LEAVE
    - SICK_LEAVE
    - EARNED_LEAVE (Annual)
    - MATERNITY_LEAVE
    - PATERNITY_LEAVE
    - COMP_OFF
    - UNPAID_LEAVE
  • Specify dates
  • Provide reason
  • Attach medical certificate (if sick leave >2 days)
  ↓
[CHECK_LEAVE_BALANCE] (System, Immediate)
  • Verify leave balance
  • Check leave policy compliance
  • Decision: BALANCE_AVAILABLE / INSUFFICIENT_BALANCE
  ↓ (IF INSUFFICIENT)
[NOTIFY_EMPLOYEE] → END
  ↓ (IF SUFFICIENT)
[CHECK_TEAM_COVERAGE] (System, Immediate)
  • Check if team member already on leave same dates
  • Warn manager if coverage issue
  ↓
[MANAGER_APPROVAL] (Reporting Manager, 24hr SLA)
  • Review request
  • Check work deadlines
  • Verify coverage
  • Decision: APPROVE / REJECT / REQUEST_RESCHEDULE
  ↓ (IF REJECTED)
[NOTIFY_REJECTION] → END
  ↓ (IF APPROVED)
[HR_APPROVAL] (HR, Required for leaves >5 days or special leaves)
  • Verify policy compliance
  • Check leave balance
  • Decision: APPROVE / REJECT
  ↓
[UPDATE_LEAVE_BALANCE] (System, Immediate)
  • Deduct from leave balance
  • Update leave tracker
  • Mark dates in attendance calendar
  ↓
[NOTIFY_STAKEHOLDERS] (System, Immediate)
  • Notify employee: Approved
  • Notify manager: Reminder
  • Notify team: Out-of-office notification
  • Update team calendar
  ↓
[PRE_LEAVE_REMINDER] (System, 1 day before)
  • Remind employee to set auto-reply
  • Remind to complete handover
  ↓
[POST_LEAVE_CONFIRMATION] (System, Day after return)
  • Confirm employee has returned
  • Close leave request
  ↓
END
```

**Leave Entitlements (Annual - example):**
- Casual Leave: 12 days
- Sick Leave: 12 days
- Earned Leave: 21 days (increases with tenure)
- Maternity Leave: 26 weeks (as per law)
- Paternity Leave: 5 days
- Comp Off: As earned

**Leave Policies:**
- Casual leave: Max 3 consecutive days
- Sick leave >2 days: Medical certificate required
- Earned leave: Can be carried forward (max 30 days)
- Leave encashment allowed at year-end

---

### 12. Attendance Regularization Process
**Proposed Code:** `ATTENDANCE_REGULARIZATION_WORKFLOW`
**Primary Entity:** (New entity: ATTENDANCE_RECORD)
**Related Entities:** EMPLOYMENT_CONTRACT, PERSON

**Purpose:** Handle missed punches, late arrivals, early departures

**Proposed Workflow:**
```
START (Employee realizes attendance discrepancy)
  ↓
[SUBMIT_REGULARIZATION_REQUEST] (Employee, Within 3 days)
  • Request type:
    - MISSED_PUNCH (forgot to punch in/out)
    - LATE_ARRIVAL (with reason)
    - EARLY_DEPARTURE (with approval)
    - WORK_FROM_HOME (not pre-approved)
  • Provide date and time
  • Explain reason
  • Attach proof (if applicable)
  ↓
[MANAGER_REVIEW] (Reporting Manager, 24hr SLA)
  • Verify claim
  • Check with employee if needed
  • Decision:
    - APPROVE (if genuine)
    - REJECT (if not justified)
    - MARK_AS_ABSENT (if unexplained)
  ↓ (IF APPROVED)
[UPDATE_ATTENDANCE] (System, Immediate)
  • Update attendance record
  • Mark as regularized
  • Update monthly attendance sheet
  ↓ (IF REJECTED)
[DEDUCT_FROM_LEAVE] (System, Immediate)
  • Mark as absent OR
  • Deduct from casual leave balance
  • Update attendance record
  ↓
[NOTIFY_EMPLOYEE] (System, Immediate)
  • Inform of decision
  • Update leave balance (if deducted)
  ↓
[MONTHLY_ATTENDANCE_REVIEW] (Manager, Month-end)
  • Review all regularizations
  • Flag excessive regularizations
  • Counsel employee if pattern noticed
  ↓
END
```

**Regularization Limits:**
- Maximum 3 regularizations per month
- Late arrivals >30 min: Requires manager pre-approval
- Missed punches: Must be requested within 3 working days

---

## **CATEGORY 4: TRAINING & DEVELOPMENT** (2 Processes)

### 13. Training Request & Approval Process
**Proposed Code:** `TRAINING_REQUEST_WORKFLOW`
**Primary Entity:** (New entity: TRAINING_REQUEST)
**Related Entities:** EMPLOYMENT_CONTRACT, PERSON

**Proposed Workflow:**
```
START
  ↓
[SUBMIT_TRAINING_REQUEST] (Employee/Manager, Anytime)
  • Training type:
    - INTERNAL_TRAINING
    - EXTERNAL_TRAINING
    - CERTIFICATION
    - WORKSHOP
    - CONFERENCE
  • Training details
  • Duration
  • Cost
  • Business justification
  • Expected benefits
  ↓
[MANAGER_REVIEW] (Reporting Manager, 48hr SLA)
  • Assess relevance to role
  • Verify business benefit
  • Check team coverage during training
  • Decision: SUPPORT / DO_NOT_SUPPORT
  ↓ (IF NOT SUPPORTED - END)
  ↓ (IF SUPPORTED)
[BUDGET_CHECK] (HR/Finance, 24hr SLA)
  • Check training budget availability
  • Verify cost reasonableness
  • Decision: BUDGET_AVAILABLE / OVER_BUDGET
  ↓ (IF OVER_BUDGET)
[SEEK_EXCEPTION] (Department Head)
  • Business case review
  • Approve exception OR Reject
  ↓ (IF BUDGET OK)
[DEPT_HEAD_APPROVAL] (Department Head, 48hr SLA)
  • Final approval for department
  • Priority assessment
  • Decision: APPROVE / REJECT
  ↓
[HR_APPROVAL] (HR/L&D, 24hr SLA)
  • Process training enrollment
  • Coordinate with vendor
  • Book training dates
  ↓
[TRAINING_AGREEMENT] (HR + Employee, If cost >threshold)
  • Sign training bond
  • Service commitment (typically 1-2 years)
  • Repayment terms if employee leaves early
  ↓
[ENROLL_FOR_TRAINING] (HR, 1 week before)
  • Complete enrollment
  • Share joining instructions
  • Block calendar
  ↓
[ATTEND_TRAINING] (Employee, During training)
  • Attend sessions
  • Complete assignments
  • Obtain certificate
  ↓
[POST-TRAINING_EVALUATION] (Employee, Within 1 week)
  • Submit training feedback
  • Upload certificate
  • Share learnings with team
  ↓
[KNOWLEDGE_SHARING] (Employee, Within 2 weeks)
  • Conduct team session
  • Share key takeaways
  • Update knowledge base
  ↓
[EFFECTIVENESS_REVIEW] (Manager, 3 months later)
  • Assess application of learning
  • Measure improvement
  • ROI evaluation
  ↓
END
```

**Training Budget Allocation:**
- Per employee per year: Varies by level
- Entry level: $500-1000
- Mid-level: $1000-3000
- Senior level: $3000-5000
- Leadership: $5000+

**Training Bond Terms:**
- Training cost <$1000: No bond
- Training cost $1000-$5000: 1 year service
- Training cost >$5000: 2 years service

---

### 14. Training Completion & Certification Tracking
**Proposed Code:** `TRAINING_COMPLETION_WORKFLOW`
**Primary Entity:** (New entity: TRAINING_COMPLETION)
**Related Entities:** TRAINING_REQUEST, PERSON

**Proposed Workflow:**
```
START (After training completion)
  ↓
[SUBMIT_CERTIFICATE] (Employee, Within 1 week)
  • Upload training certificate
  • Share training materials
  • Complete training evaluation form
  ↓
[HR_VERIFICATION] (HR/L&D, 3 days)
  • Verify certificate authenticity
  • Update training records
  • Add to employee profile
  ↓
[UPDATE_SKILL_MATRIX] (HR, 1 day)
  • Update employee skill matrix
  • Add certification to profile
  • Update on internal job portal
  ↓
[CERTIFICATION_RENEWAL_REMINDER] (System, Before expiry)
  • Track certification validity
  • Remind employee 60 days before expiry
  • Trigger renewal process if needed
  ↓
END
```

---

## **CATEGORY 5: EMPLOYEE RELATIONS** (2 Processes)

### 15. Employee Grievance Handling Process
**Proposed Code:** `EMPLOYEE_GRIEVANCE_WORKFLOW`
**Primary Entity:** (New entity: GRIEVANCE_CASE)
**Related Entities:** PERSON, EMPLOYMENT_CONTRACT

**Proposed Workflow:**
```
START
  ↓
[FILE_GRIEVANCE] (Employee, Anytime)
  • Grievance category:
    - WORKPLACE_HARASSMENT
    - DISCRIMINATION
    - UNFAIR_TREATMENT
    - POLICY_VIOLATION
    - SALARY_ISSUE
    - WORK_ENVIRONMENT
    - MANAGER_CONFLICT
    - OTHER
  • Describe issue
  • Attach evidence (if any)
  • Option: Anonymous OR Named
  ↓
[ACKNOWLEDGE_RECEIPT] (System, Immediate)
  • Generate grievance ID
  • Send acknowledgment to employee
  • Maintain confidentiality
  ↓
[ASSIGN_INVESTIGATOR] (HR Head, 24hr SLA)
  • Assign neutral HR investigator
  • Senior if serious allegation
  • External if involves senior management
  ↓
[INITIAL_ASSESSMENT] (Investigator, 48hr SLA)
  • Review grievance details
  • Assess severity
  • Classify:
    - CRITICAL (harassment, discrimination)
    - HIGH (policy violations)
    - MEDIUM (conflicts, complaints)
    - LOW (suggestions, feedback)
  ↓
[GATHER_INFORMATION] (Investigator, 5-10 days)
  • Interview complainant
  • Interview accused (if named)
  • Interview witnesses
  • Collect evidence
  • Document findings
  ↓
[PRELIMINARY_FINDINGS] (Investigator, 2 days)
  • Analyze information
  • Draft preliminary report
  • Identify violations (if any)
  ↓
[GRIEVANCE_COMMITTEE_REVIEW] (If serious)
  • Present to grievance committee
  • Committee members: HR + Management + Employee Rep
  • Review findings
  • Recommend action
  ↓
[RESOLUTION_DECISION] (HR Head/Committee, 3 days)
  • Decision:
    - GRIEVANCE_SUBSTANTIATED → Take action
    - GRIEVANCE_UNSUBSTANTIATED → Close with explanation
    - INSUFFICIENT_EVIDENCE → Request more information
    - MEDIATION_REQUIRED → Arrange mediation
  ↓ (IF SUBSTANTIATED)
[TAKE_ACTION] (HR + Management, 5 days)
  • Disciplinary action (if applicable):
    - WARNING (verbal/written)
    - SUSPENSION
    - DEMOTION
    - TERMINATION
  • Corrective measures:
    - Policy update
    - Team restructuring
    - Additional training
  ↓
[COMMUNICATE_DECISION] (HR, 2 days)
  • Inform complainant of outcome
  • Inform accused of decision
  • Maintain confidentiality
  • Document in personnel files
  ↓
[MONITOR_SITUATION] (HR, 3 months)
  • Follow-up with complainant
  • Ensure no retaliation
  • Monitor workplace behavior
  ↓
[CLOSE_GRIEVANCE] (HR, After monitoring)
  • Close grievance case
  • Archive documentation
  • Update grievance statistics
  ↓
END
```

**Grievance Resolution SLAs:**
- Critical grievances: 15 days
- High priority: 30 days
- Medium priority: 45 days
- Low priority: 60 days

**Confidentiality Guarantee:**
- All grievances handled confidentially
- Limited access to case details
- Protection against retaliation

---

### 16. Disciplinary Action Process
**Proposed Code:** `DISCIPLINARY_ACTION_WORKFLOW`
**Primary Entity:** (New entity: DISCIPLINARY_CASE)
**Related Entities:** PERSON, EMPLOYMENT_CONTRACT

**Proposed Workflow:**
```
START (Violation/misconduct reported)
  ↓
[REPORT_INCIDENT] (Manager/HR, Immediate)
  • Violation category:
    - ATTENDANCE_ISSUES
    - PERFORMANCE_ISSUES
    - BEHAVIORAL_ISSUES
    - POLICY_VIOLATION
    - CODE_OF_CONDUCT_BREACH
    - FRAUD/THEFT
    - INSUBORDINATION
  • Document incident
  • Gather initial evidence
  ↓
[PRELIMINARY_INVESTIGATION] (Manager + HR, 48hr)
  • Verify facts
  • Collect evidence
  • Document timeline
  • Interview witnesses
  ↓
[ASSESS_SEVERITY] (HR, 1 day)
  • Classify violation:
    - MINOR (first-time, no major impact)
    - MODERATE (repeated or moderate impact)
    - MAJOR (serious violation)
    - GROSS_MISCONDUCT (immediate termination grounds)
  ↓
[ISSUE_SHOW_CAUSE_NOTICE] (HR, 2 days)
  • Prepare show cause notice
  • State allegations clearly
  • Request explanation
  • Set response deadline (typically 2-3 days)
  ↓
[EMPLOYEE_RESPONSE] (Employee, 3 days)
  • Submit written explanation
  • Provide evidence/witnesses
  • Accept OR Deny allegations
  ↓
[CONDUCT_HEARING] (If major violation, HR + Management)
  • Formal hearing
  • Employee can bring representative
  • Present evidence
  • Hear employee's defense
  • Document proceedings
  ↓
[DISCIPLINARY_COMMITTEE_DECISION] (Committee, 5 days)
  • Review all evidence
  • Consider employee response
  • Past record review
  • Decide:
    - NO_ACTION (if unfounded)
    - WARNING (verbal/written)
    - FINAL_WARNING
    - SUSPENSION (with/without pay)
    - DEMOTION
    - TERMINATION
  ↓ (IF WARNING)
[ISSUE_WARNING_LETTER] (HR, 2 days)
  • Document warning
  • Specify violation
  • Set expectations
  • State consequences of recurrence
  • Add to employee file
  • Validity: 6-12 months
  ↓ (IF SUSPENSION)
[PROCESS_SUSPENSION] (HR, Immediate)
  • Issue suspension order
  • Specify duration
  • State terms (paid/unpaid)
  • Communicate to all stakeholders
  ↓ (IF TERMINATION)
[TERMINATE_EMPLOYMENT] (HR + Legal, 5 days)
  • Issue termination letter
  • State grounds for termination
  • Calculate settlement
  • Initiate exit process
  • Revoke access immediately
  ↓
[APPEAL_OPPORTUNITY] (Employee, Optional, 7 days)
  • IF employee disagrees:
    - Submit appeal
    - Review by senior management
    - Final decision
  ↓
[MONITOR_IMPROVEMENT] (Manager, 3-6 months)
  • For warnings: Monitor behavior
  • Regular check-ins
  • Document improvements
  • Clear warning if improved
  ↓
[CLOSE_CASE] (HR, After monitoring period)
  • Close disciplinary case
  • Update employee record
  • Archive documentation
  ↓
END
```

**Progressive Discipline Approach:**
1. **First offense (minor):** Verbal warning
2. **Second offense:** Written warning
3. **Third offense:** Final written warning
4. **Fourth offense:** Suspension
5. **Fifth offense OR major violation:** Termination

**Gross Misconduct (Immediate Termination):**
- Theft or fraud
- Physical violence
- Sexual harassment
- Substance abuse at workplace
- Serious data breach
- Falsification of documents

---

## **ENTITY REQUIREMENTS**

To implement all proposed processes, the following new entities need to be created:

### New Entities Needed:

1. **LEAVE_REQUEST**
   - employee_id, leave_type, start_date, end_date, number_of_days, reason, status, approved_by, approval_date

2. **LEAVE_BALANCE**
   - employee_id, leave_type, total_entitled, used, balance, year

3. **ATTENDANCE_RECORD**
   - employee_id, date, punch_in, punch_out, status (PRESENT/ABSENT/HALF_DAY/ON_LEAVE), is_regularized

4. **TRAINING_REQUEST**
   - employee_id, training_name, training_type, provider, cost, start_date, end_date, status, approved_by

5. **TRAINING_COMPLETION**
   - employee_id, training_request_id, completion_date, certificate_url, score, feedback

6. **PERFORMANCE_APPRAISAL**
   - employee_id, appraisal_period, self_rating, manager_rating, final_rating, increment_percentage, promotion_recommended

7. **GRIEVANCE_CASE**
   - employee_id, category, description, severity, status, assigned_to, resolution, closed_date

8. **DISCIPLINARY_CASE**
   - employee_id, violation_type, incident_date, action_taken, warning_expiry_date, status

9. **ONBOARDING_CHECKLIST**
   - employee_id, checklist_item, status, completed_date, completed_by

10. **EXIT_CLEARANCE**
    - employee_id, department, clearance_status, cleared_by, cleared_date, pending_items

---

## **IMPLEMENTATION PRIORITY**

### **Phase 1: Critical (Months 1-2)**
1. Vacancy Creation & Approval ✅ (Already exists)
2. Application Screening & Shortlisting
3. Interview Management
4. Job Offer Workflow
5. Employment Contract Creation

### **Phase 2: Essential (Months 3-4)**
6. Employee Onboarding
7. Leave Request & Approval
8. Attendance Regularization
9. Employee Exit/Offboarding

### **Phase 3: Important (Months 5-6)**
10. Probation Review
11. Performance Appraisal
12. Training Request & Approval

### **Phase 4: Advanced (Months 7-8)**
13. Promotion & Transfer
14. Training Completion Tracking
15. Employee Grievance Handling
16. Disciplinary Action

---

## **INTEGRATION POINTS**

### Process Chains:
```
RECRUITMENT CHAIN:
Vacancy Creation → Application Screening → Interview Management
→ Job Offer → Contract Creation → Onboarding → Probation Review

PERFORMANCE CHAIN:
Onboarding → Probation Review → Performance Appraisal
→ Promotion/Transfer → Training

EXIT CHAIN:
Resignation/Termination → Exit Interview → Clearance → Settlement
```

### Cross-Process Triggers:
- **Job Offer Accepted** → Trigger Contract Creation
- **Contract Signed** → Trigger Onboarding
- **Probation Period Ends** → Trigger Probation Review
- **Appraisal Cycle Starts** → Trigger Performance Appraisal
- **High Performer** → Suggest Promotion Process
- **Resignation Submitted** → Trigger Exit Process

---

## **NEXT STEPS**

1. **Review & Approve** this proposal
2. **Prioritize processes** based on business needs
3. **Create new entities** (LEAVE_REQUEST, TRAINING_REQUEST, etc.)
4. **Develop SQL process definitions** for each approved process
5. **Configure positions & permissions** for HR roles
6. **Build UI forms** for each process step
7. **Set up notifications** (email/SMS)
8. **Test workflows** with sample data
9. **Train HR staff** on new processes
10. **Go live** in phases

---

## **SUMMARY**

This comprehensive HR process framework covers:

✅ **15 Core HR Processes** across 5 categories
✅ **Complete Employee Lifecycle** from recruitment to exit
✅ **80+ Workflow Steps** with detailed specifications
✅ **SLA definitions** for each task
✅ **Decision points** and conditional routing
✅ **Integration with existing entities**
✅ **10 New entity requirements** identified
✅ **Implementation roadmap** provided

All processes follow the same architecture as marketplace processes and can be implemented using the existing process flow system.
