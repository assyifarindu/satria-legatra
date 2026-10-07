<style>
    .dashboard-card {
        height: 270px !important;
    }

    .dashboard-card .card-body {
        padding: 10px 12px;
    }

    .review-metric-card {
        min-height: 92px;
        border-radius: 6px;
        padding: 9px;

        position: relative;
    }

    .review-metric-card.user-review {
        background: #eef7ff;
    }

    .review-metric-card.committee-review {
        background: #f4efff;
    }

    .review-metric-card.bod-review {
        background: #edf9f4;
    }

    .review-icon {
        width: 28px;
        height: 28px;

        border-radius: 50%;

        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 16px;

        margin-bottom: 4px;
    }

    .user-review .review-icon {
        color: #2196f3;
        background: #dceeff;
    }

    .committee-review .review-icon {
        color: #8b5cf6;
        background: #e9ddff;
    }

    .bod-review .review-icon {
        color: #10a879;
        background: #d8f5e9;
    }

    .review-label {
        font-size: 10px;
        color: #536174;

        white-space: nowrap;
    }

    .review-value {
        font-size: 20px;
        font-weight: 600;

        color: #17365d;

        line-height: 1.1;
    }


    /* =====================================================
       SLA
    ===================================================== */

    .sla-title {
        font-size: 12px;
        font-weight: 600;

        color: #17365d;

        margin-top: 10px;
        margin-bottom: 6px;
    }

    .sla-card {
        min-height: 82px;

        border: 1px solid #edf0f4;
        border-radius: 6px;

        padding: 7px;

        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .sla-label {
        font-size: 10px;
        color: #536174;

        max-width: 65px;
        line-height: 1.2;
    }

    .sla-progress-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
    }


    /* Circle */

    .sla-circle {
        width: 52px;
        height: 52px;

        border-radius: 50%;

        display: flex;
        align-items: center;
        justify-content: center;

        position: relative;

        font-size: 12px;
        font-weight: 600;
    }

    .sla-circle::before {
        content: "";

        position: absolute;

        width: 42px;
        height: 42px;

        border-radius: 50%;

        background: #ffffff;
    }

    .sla-circle span {
        position: relative;
        z-index: 1;
    }

    .user-sla {
        background: conic-gradient(#2196f3 0deg,
                #2196f3 0deg,
                #e8edf3 0deg);

        color: #17365d;
    }

    .committee-sla {
        background: conic-gradient(#8b5cf6 0deg,
                #8b5cf6 0deg,
                #e8edf3 0deg);

        color: #17365d;
    }
</style>
