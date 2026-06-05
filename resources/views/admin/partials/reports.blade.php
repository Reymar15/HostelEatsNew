<section class="panel admin-table-gap" id="reports-section">
    <div class="panel-head">
        <div>
            <h2>Reports</h2>
            <p>Download-ready fake report cards for daily sales, order summaries, and revenue.</p>
        </div>
        <button type="button" class="primary-action" data-fake-download>Download PDF Report</button>
    </div>

    <div class="report-grid">
        <article class="report-card">
            <span>D</span>
            <h3>Daily Sales Report</h3>
            <p>PHP{{ number_format(($adminStats['total_revenue'] ?? 0) / 3, 2) }} estimated sales today.</p>
            <button type="button" data-fake-download>Download</button>
        </article>
        <article class="report-card">
            <span>O</span>
            <h3>Order Summary Report</h3>
            <p>{{ $adminStats['total_orders'] ?? 0 }} orders across active and completed states.</p>
            <button type="button" data-fake-download>Download</button>
        </article>
        <article class="report-card">
            <span>R</span>
            <h3>Revenue Report</h3>
            <p>Gross revenue: PHP{{ number_format($adminStats['total_revenue'] ?? 0, 2) }}.</p>
            <button type="button" data-fake-download>Download</button>
        </article>
    </div>
</section>
