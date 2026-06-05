<section class="admin-analytics-grid" id="analytics-section">
    <article class="panel chart-panel">
        <div class="panel-head">
            <div>
                <h2>Daily Orders</h2>
                <p>Orders received over the last seven days.</p>
            </div>
        </div>
        <canvas data-chart="dailyOrders" data-values='@json($analytics["dailyOrders"])'></canvas>
    </article>

    <article class="panel chart-panel">
        <div class="panel-head">
            <div>
                <h2>Revenue Analytics</h2>
                <p>Fake PHP revenue trend for admin reporting.</p>
            </div>
        </div>
        <canvas data-chart="revenue" data-values='@json($analytics["revenue"])'></canvas>
    </article>

    <article class="panel chart-panel">
        <div class="panel-head">
            <div>
                <h2>Most Ordered Foods</h2>
                <p>Top menu performers by quantity.</p>
            </div>
        </div>
        <canvas data-chart="topFoods" data-values='@json($analytics["topFoods"])'></canvas>
    </article>

    <article class="panel chart-panel">
        <div class="panel-head">
            <div>
                <h2>Top Branches</h2>
                <p>Branches ranked by order activity.</p>
            </div>
        </div>
        <canvas data-chart="topBranches" data-values='@json($analytics["topBranches"])'></canvas>
    </article>
</section>
