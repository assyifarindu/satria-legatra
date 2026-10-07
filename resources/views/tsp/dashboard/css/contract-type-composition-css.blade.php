<style>
    .contract-type-composition-card {
        height: 270px !important;
    }

    .contract-type-composition-card .card-body {
        padding: 10px 12px;
    }

    .contract-type-legend {
        display: flex;
        flex-direction: column;
        gap: 8px;
        padding-left: 5px;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 7px;
        width: 100%;
    }

    .legend-dot {
        width: 8px;
        height: 8px;
        min-width: 8px;
        border-radius: 50%;
        display: inline-block;
    }

    .legend-dot.unit {
        background-color: #36B37E;
    }

    .legend-dot.service {
        background-color: #8B5CF6;
    }

    .legend-dot.parts {
        background-color: #FBB03B;
    }

    .legend-dot.reman {
        background-color: #94A3B8;
    }

    .legend-info {
        display: flex;
        align-items: center;
        width: 100%;
    }

    .legend-name {
        font-size: 12px;
        color: #536174;
        white-space: nowrap;
    }

    .legend-value {
        margin-left: auto;
        padding-right: 8px;

        font-size: 12px;
        font-weight: 500;
    }


    /* Warna angka seperti pada gambar */
    .legend-item.unit .legend-value {
        color: #36B37E;
    }

    .legend-item.service .legend-value {
        color: #8B5CF6;
    }

    .legend-item.parts .legend-value {
        color: #FBB03B;
    }

    .legend-item.reman .legend-value {
        color: #94A3B8;
    }

    #contract-type-composition-chart {
        min-height: 240px;
    }
</style>
