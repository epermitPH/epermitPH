<?php

require __DIR__ . "/includes/bootstrap.php";

$pageTitle = "Home";

require __DIR__ . "/includes/header.php";

?>


<!-- =====================================================
     ePERMIT COMBINED LANDING HERO
===================================================== -->

<section class="epermit-combined-hero">




    <!-- ===============================================
         WORKFLOW VISUAL
    ================================================ -->

    <div class="registry-visual">

        <svg
            class="registry-flow"
            viewBox="0 0 900 210"
            xmlns="http://www.w3.org/2000/svg"
            aria-hidden="true"
        >

            <defs>

                <linearGradient
                    id="registryLineGradient"
                    x1="0%"
                    y1="0%"
                    x2="100%"
                    y2="0%"
                >

                    <stop
                        offset="0%"
                        stop-color="#f59e0b"
                    />

                    <stop
                        offset="100%"
                        stop-color="#fbbf24"
                    />

                </linearGradient>

            </defs>



            <!-- DECORATIVE CURVES -->

            <path
                d="
                    M470 10
                    C430 75,
                    440 175,
                    530 235
                "
                fill="none"
                stroke="rgba(255,255,255,.10)"
                stroke-width="2"
            />


            <path
                d="
                    M555 10
                    C500 80,
                    525 190,
                    700 230
                "
                fill="none"
                stroke="rgba(255,255,255,.10)"
                stroke-width="2"
            />



            <!-- MAIN PROCESS LINE -->

            <path
                d="
                   M60 170
                   L130 170
                   L200 130
                   L330 130
                   L410 90
                   L575 90
                   L650 50
                   L835 50
                "
                fill="none"
                stroke="url(#registryLineGradient)"
                stroke-width="5"
                stroke-linecap="round"
                stroke-linejoin="round"
            />



            <!-- SUBMITTED -->

            <circle
                cx="60"
                cy="170"
                r="14"
                fill="none"
                stroke="#f59e0b"
                stroke-width="5"
            />

            <circle
                cx="60"
                cy="195"
                r="4"
                fill="#f59e0b"
            />



            <!-- REVIEWED -->

            <circle
                cx="330"
                cy="130"
                r="14"
                fill="none"
                stroke="#f59e0b"
                stroke-width="5"
            />

            <circle
                cx="330"
                cy="150"
                r="4"
                fill="#f59e0b"
            />



            <!-- APPROVED -->

            <circle
                cx="575"
                cy="90"
                r="14"
                fill="none"
                stroke="#f59e0b"
                stroke-width="5"
            />

            <circle
                cx="575"
                cy="105"
                r="4"
                fill="#f59e0b"
            />



            <!-- VERIFIED ON-SITE -->

            <circle
                cx="835"
                cy="50"
                r="14"
                fill="none"
                stroke="#22c55e"
                stroke-width="5"
            />

            <circle
                cx="835"
                cy="60"
                r="4"
                fill="#22c55e"
            />

        </svg>

    </div>



    <!-- ===============================================
         FOUR MAIN PROCESS STAGES
    ================================================ -->

    <div class="combined-stages">


        <div class="combined-stage">

            <span class="stage-no">
                01
            </span>

            <strong>
                Submitted
            </strong>

        </div>



        <div class="combined-stage">

            <span class="stage-no">
                02
            </span>

            <strong>
                Reviewed
            </strong>

        </div>



        <div class="combined-stage">

            <span class="stage-no">
                03
            </span>

            <strong>
                Approved
            </strong>

        </div>



        <div class="combined-stage active-stage">

            <span class="stage-no">
                04
            </span>

            <strong>
                Verified on-site
            </strong>

        </div>


    </div>



    <!-- ===============================================
         MAIN HERO CONTENT
    ================================================ -->

    <div class="combined-hero-content">


        <!-- ===========================================
             LEFT SIDE
        ============================================ -->

        <div class="combined-hero-copy">


            <span class="hero-mini-label">
                DIGITAL LGU PERMIT WORKFLOW
            </span>


            <h1>

                A verified path from

                <span>
                    design to inspection.
                </span>

            </h1>


            <p>

                Every application, every credential,
                every review, and every approval —
                one traceable record from submission
                to on-site QR verification.

            </p>


            <p class="hero-secondary-text">

                ePermit connects applicants,
                licensed professionals including
                Professional Electronics Engineers
                (PECEs), LGU technical reviewers,
                building officials, and field inspectors
                through one organized digital workflow.

            </p>



            <!-- FUNCTIONAL BUTTONS -->

            <div class="combined-actions">


                <a
                    href="<?= e(
                        url(
                            "register.php"
                        )
                    ) ?>"
                    class="combined-primary-btn"
                >

                    Start an Application

                </a>


                <a
                    href="<?= e(
                        url(
                            "verify.php"
                        )
                    ) ?>"
                    class="combined-secondary-btn"
                >

                    Verify a Permit

                </a>


            </div>

        </div>



        <!-- ===========================================
             RIGHT SIDE — ePERMIT FLOW
        ============================================ -->

        <div class="combined-flow-card">


            <div class="flow-card-heading">

                <span>
                    UPDATED ePERMIT FLOW
                </span>

                <h2>
                    Beyond online submission
                </h2>

                <p>
                    One project record throughout the
                    complete permit lifecycle.
                </p>

            </div>



            <!-- STEP 1 -->

            <div class="flow-step">

                <span class="flow-number">
                    01
                </span>

                <div>

                    <strong>
                        Application & Requirements
                    </strong>

                    <small>
                        Create the project and identify
                        applicable LGU technical requirements.
                    </small>

                </div>

            </div>



            <!-- STEP 2 -->

            <div class="flow-step">

                <span class="flow-number">
                    02
                </span>

                <div>

                    <strong>
                        Professional / PECE Verification
                    </strong>

                    <small>
                        Associate technical submissions
                        with verified professionals.
                    </small>

                </div>

            </div>



            <!-- STEP 3 -->

            <div class="flow-step">

                <span class="flow-number">
                    03
                </span>

                <div>

                    <strong>
                        Discipline-Based Technical Review
                    </strong>

                    <small>
                        Route plans to the appropriate
                        authorized LGU reviewer.
                    </small>

                </div>

            </div>



            <!-- STEP 4 -->

            <div class="flow-step">

                <span class="flow-number">
                    04
                </span>

                <div>

                    <strong>
                        Correction & Resubmission
                    </strong>

                    <small>
                        Return documents for correction
                        without losing previous submissions.
                    </small>

                </div>

            </div>



            <!-- STEP 5 -->

            <div class="flow-step">

                <span class="flow-number">
                    05
                </span>

                <div>

                    <strong>
                        Document Version Control
                    </strong>

                    <small>
                        Preserve original, corrected,
                        revised, and approved versions.
                    </small>

                </div>

            </div>



            <!-- STEP 6 -->

            <div class="flow-step">

                <span class="flow-number">
                    06
                </span>

                <div>

                    <strong>
                        Approved Plan of Record
                    </strong>

                    <small>
                        Identify the exact document version
                        approved by the LGU.
                    </small>

                </div>

            </div>



            <!-- STEP 7 -->

            <div class="flow-step">

                <span class="flow-number">
                    07
                </span>

                <div>

                    <strong>
                        Digital Permit & QR Verification
                    </strong>

                    <small>
                        Link the issued permit to its
                        official digital record.
                    </small>

                </div>

            </div>



            <!-- STEP 8 -->

            <div class="flow-step">

                <span class="
                    flow-number
                    verified-step
                ">
                    08
                </span>

                <div>

                    <strong>
                        Field Inspection & Audit Trail
                    </strong>

                    <small>
                        Verify permits on-site and record
                        inspection findings.
                    </small>

                </div>

            </div>


        </div>

    </div>

</section>



<!-- =====================================================
     CORE FEATURES
     EXISTING FUNCTIONALITY / CONTENT RETAINED
===================================================== -->

<section class="landing-feature-section">


    <div class="landing-section-heading">

        <span class="kicker">
            BUILT FOR TRACEABILITY
        </span>

        <h2>
            More than just an online permit application.
        </h2>

        <p class="muted">

            ePermit coordinates the technical review,
            professional verification, document revision,
            approval, permit verification, and inspection
            process in one platform.

        </p>

    </div>



    <div class="grid landing-feature-grid">


        <!-- FEATURE 1 -->

        <div class="card landing-feature-card">

            <div class="feature-icon feature-blue">
                01
            </div>

            <h3>
                Discipline-Level Tracking
            </h3>

            <p class="muted">

                Applicants can see which technical
                discipline is approved, pending,
                under review, or requires correction.

            </p>

        </div>



        <!-- FEATURE 2 -->

        <div class="card landing-feature-card">

            <div class="feature-icon feature-green">
                02
            </div>

            <h3>
                Professional Verification
            </h3>

            <p class="muted">

                Technical documents can be connected
                to verified professionals, including
                PECE for applicable Electronics
                Engineering documents.

            </p>

        </div>



        <!-- FEATURE 3 -->

        <div class="card landing-feature-card">

            <div class="feature-icon feature-orange">
                03
            </div>

            <h3>
                Document Version Control
            </h3>

            <p class="muted">

                Corrections create new versions instead
                of replacing earlier technical submissions.

            </p>

        </div>



        <!-- FEATURE 4 -->

        <div class="card landing-feature-card">

            <div class="feature-icon feature-purple">
                04
            </div>

            <h3>
                Reviewer Routing
            </h3>

            <p class="muted">

                Technical documents are routed to
                authorized LGU reviewers according
                to their assigned discipline.

            </p>

        </div>



        <!-- FEATURE 5 -->

        <div class="card landing-feature-card">

            <div class="feature-icon feature-teal">
                05
            </div>

            <h3>
                Approved Plan of Record
            </h3>

            <p class="muted">

                The system identifies the exact
                technical-document version that
                received final approval.

            </p>

        </div>



        <!-- FEATURE 6 -->

        <div class="card landing-feature-card">

            <div class="feature-icon feature-green">
                06
            </div>

            <h3>
                Inspection Continuity
            </h3>

            <p class="muted">

                The same project record continues
                from permit approval and QR verification
                through field inspection.

            </p>

        </div>


    </div>

</section>
