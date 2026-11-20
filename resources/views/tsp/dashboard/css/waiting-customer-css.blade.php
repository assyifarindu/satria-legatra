<style>
    .waiting-customer-chart {
        min-height: 190px;
    }

    .waiting-customer-title {
        font-size: 11px;
        font-weight: 600;
        color: #17365d;

        margin-bottom: 8px;
    }

    .waiting-customer-list {
        display: flex;
        flex-direction: column;
        gap: 7px;
    }

    .waiting-customer-item {
        display: flex;
        align-items: center;

        gap: 8px;

        font-size: 10px;
        color: #536174;

        line-height: 1.2;
    }

    .waiting-customer-dot {
        width: 8px;
        height: 8px;

        min-width: 8px;

        border-radius: 50%;
    }

    .waiting-customer-name {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;

        flex: 1;
    }
</style>
