<section class="panel admin-table-gap" id="foods-table">
    <div class="panel-head">
        <div>
            <h2>Food Management</h2>
            <p>Manage menu items, availability, branch filters, and fake inventory state.</p>
        </div>
        <button type="button" class="primary-action" data-admin-modal="food-modal">Add Food</button>
    </div>

    <div class="admin-filter-bar">
        <input type="search" placeholder="Search food..." data-admin-search="foods-table">
        <select data-admin-filter="foods-table" data-filter-key="branch">
            <option value="">All branches</option>
            @foreach ($branches as $branch)
                <option value="{{ strtolower($branch['name']) }}">{{ $branch['name'] }}</option>
            @endforeach
        </select>
        <select data-admin-filter="foods-table" data-filter-key="category">
            <option value="">All categories</option>
            @foreach ($categories as $category)
                <option value="{{ strtolower($category['name']) }}">{{ $category['name'] }}</option>
            @endforeach
        </select>
    </div>

    <div class="table-wrap">
        <table class="history-table admin-data-table" id="foods-table">
            <thead>
                <tr>
                    <th>Food</th>
                    <th>Branch</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Available</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($foods as $food)
                    <tr data-searchable="{{ strtolower($food['name'].' '.$food['branch'].' '.$food['category']) }}" data-branch="{{ strtolower($food['branch']) }}" data-category="{{ strtolower($food['category']) }}">
                        <td>
                            <div class="admin-media-cell">
                                <img src="{{ $food['photo'] }}" alt="{{ $food['name'] }}" data-fallback-src="{{ $food['fallback_image'] }}">
                                <span><strong>{{ $food['name'] }}</strong><small>{{ $food['tag'] }}</small></span>
                            </div>
                        </td>
                        <td>{{ $food['branch'] }}</td>
                        <td>{{ $food['category'] }}</td>
                        <td>PHP{{ number_format($food['price'], 2) }}</td>
                        <td><span class="{{ $food['stock'] < 10 ? 'stock-low' : '' }}">{{ $food['stock'] }}</span></td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" {{ $food['available'] ? 'checked' : '' }}>
                                <span></span>
                            </label>
                        </td>
                        <td>
                            <div class="row-actions">
                                <button type="button" data-admin-modal="food-modal">Edit</button>
                                <button type="button" class="danger">Delete</button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
