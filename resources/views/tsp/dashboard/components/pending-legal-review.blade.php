{{-- <div class="col-xl-5 col-lg-6"> --}}

<div class="card pending-legal-card h-100">

    <div class="card-body">

        <h5 class="header-title mb-3">
            Pending Legal Review
        </h5>

        <!-- Top Metrics -->
        <div class="row g-2">

            <!-- Pending Legal -->
            <div class="col-6">

                <div class="legal-metric-card pending">

                    <div class="legal-metric-icon">
                        <i class="fas fa-file"></i>
                    </div>

                    <div class="legal-metric-content">

                        <div class="legal-metric-label">
                            Pending Legal
                        </div>

                        <div class="legal-metric-value" id="pending-legal-value">
                            0
                        </div>

                    </div>

                </div>

            </div>


            <!-- Achievement SLA -->
            <div class="col-6">

                <div class="legal-metric-card sla">

                    <div class="legal-metric-icon">
                        <i class="fas fa-clock"></i>
                    </div>

                    <div class="legal-metric-content">

                        <div class="legal-metric-label">
                            Ach. SLA Legal
                        </div>

                        <div class="legal-metric-value" id="ach-sla-legal-value">
                            0%
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Aging -->
        <div class="aging-title">
            Aging Due Date Agreement
        </div>

        <div class="aging-container">

            <!-- Less than 30 -->
            <div class="aging-item">

                <div class="aging-label">
                    &lt; 30 Days
                </div>

                <div class="aging-value aging-red" id="aging-less-30">
                    0
                </div>

            </div>


            <!-- 30 - 60 -->
            <div class="aging-item">

                <div class="aging-label">
                    30 &lt; 60 Days
                </div>

                <div class="aging-value aging-yellow" id="aging-30-60">
                    0
                </div>

            </div>


            <!-- 60 - 90 -->
            <div class="aging-item">

                <div class="aging-label">
                    60 &lt; 90 Days
                </div>

                <div class="aging-value aging-green" id="aging-60-90">
                    0
                </div>

            </div>

        </div>

    </div>

</div>

{{-- </div> --}}
