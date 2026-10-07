<style>
    .pending-legal-card {
        height: 270px !important;
    }

    .pending-legal-card .card-body {
        padding: 10px 12px;
    }

    .legal-metric-card {
        min-height: 90px;
        border-radius: 8px;

        display: flex;
        align-items: center;

        padding: 12px;

        gap: 12px;
    }

    .legal-metric-card.pending {
        background: #eef7ff;
    }

    .legal-metric-card.sla {
        background: #f4f0ff;
    }

    .legal-metric-icon {
        width: 38px;
        height: 38px;

        border-radius: 8px;

        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 22px;
    }

    .legal-metric-card.pending .legal-metric-icon {
        color: #2196f3;
        background: #dceeff;
    }

    .legal-metric-card.sla .legal-metric-icon {
        color: #8b5cf6;
        background: #e9ddff;
    }

    .legal-metric-content {
        display: flex;
        flex-direction: column;

        min-width: 0;
    }

    .legal-metric-label {
        font-size: 12px;
        color: #536174;
        white-space: nowrap;
    }

    .legal-metric-value {
        font-size: 24px;
        font-weight: 600;
        color: #17365d;

        line-height: 1.2;

        margin-top: 4px;
    }


    /* Aging */

    .aging-title {
        font-size: 13px;
        font-weight: 600;

        color: #17365d;

        margin-top: 10px;
        margin-bottom: 8px;
    }

    .aging-container {
        display: flex;

        border: 1px solid #edf0f4;
        border-radius: 6px;

        overflow: hidden;
    }

    .aging-item {
        width: 33.333%;

        text-align: center;

        padding: 8px 4px;
    }

    .aging-item+.aging-item {
        border-left: 1px solid #edf0f4;
    }

    .aging-label {
        font-size: 11px;
        font-weight: 600;

        color: #536174;

        white-space: nowrap;
    }

    .aging-value {
        font-size: 20px;
        font-weight: 600;

        margin-top: 3px;
    }

    .aging-red {
        color: #ef4444;
    }

    .aging-yellow {
        color: #f59e0b;
    }

    .aging-green {
        color: #10b981;
    }
</style>
